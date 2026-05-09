<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\PaymentService;
use App\Services\AppointmentService;
use App\Http\Requests\Checkout\StoreCheckoutRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    protected $paymentService;
    protected $appointmentService;

    public function __construct(PaymentService $paymentService, AppointmentService $appointmentService)
    {
        $this->paymentService = $paymentService;
        $this->appointmentService = $appointmentService;
    }

    public function process(StoreCheckoutRequest $request, User $consultant)
    {
        if ($consultant->role !== 'consultant') {
            abort(404);
        }

        // Cache booking details temporarily
        $bookingToken = Str::random(32);
        $bookingDetails = [
            'consultant_id' => $consultant->id,
            'client_id' => $request->user()->id,
            'date' => $request->date,
            'time' => $request->time,
        ];

        Cache::put('booking_' . $bookingToken, $bookingDetails, now()->addMinutes(15));

        // Use Payment gateway to setup session and return URL
        $url = $this->paymentService->processPayment($consultant, $bookingDetails, $bookingToken);

        return redirect($url);
    }

    public function success(Request $request)
    {
        // Iyzico sends token via POST 'token' instead of query string.
        // Stripe uses session_id.
        // We strictly read the booking token from the query string to avoid collision with Iyzico's POST 'token'.
        $token = $request->query('token');
        $sessionId = $request->input('session_id') ?? $request->query('session_id') ?? $request->input('token'); // iyzico uses 'token' as its checkout token id.

        if (!$token || !Cache::has('booking_' . $token)) {
            return redirect()->route('checkout.failure')->with('error', 'Session expired or invalid.');
        }

        // We MUST strictly verify the payment.
        if (!$sessionId || !$this->paymentService->verifyPayment($request, $sessionId)) {
            return redirect()->route('checkout.failure')->with('error', __('frontend.payment_failed_desc'));
        }

        $bookingDetails = Cache::get('booking_' . $token);

        try {
            $client = User::findOrFail($bookingDetails['client_id']);
            $consultant = User::findOrFail($bookingDetails['consultant_id']);
            $amount = (float) $consultant->consultantProfile->hourly_rate;

            $this->appointmentService->createAppointment($client, $bookingDetails, $sessionId ?? 'txn_fake', $amount);

            Cache::forget('booking_' . $token);

            return view('frontend.checkout.success');
        } catch (\Exception $e) {
            return redirect()->route('checkout.failure')->with('error', 'Payment succeeded but booking failed. Please contact support.');
        }
    }

    public function failure()
    {
        return view('frontend.checkout.failure');
    }
}
