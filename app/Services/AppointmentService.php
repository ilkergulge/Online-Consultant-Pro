<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class AppointmentService
{
    public function createAppointment(User $client, array $bookingDetails, string $transactionId, float $amount)
    {
        DB::beginTransaction();

        try {
            $consultant = User::findOrFail($bookingDetails['consultant_id']);
            $scheduledAt = Carbon::parse($bookingDetails['date'] . ' ' . $bookingDetails['time']);

            // Here we would typically check again if the slot is still available
            // to prevent double booking race conditions.

            $appointment = Appointment::create([
                'client_id' => $client->id,
                'consultant_id' => $consultant->id,
                'scheduled_at' => $scheduledAt,
                'duration_minutes' => $consultant->consultantProfile->slot_duration ?? 60,
                'status' => 'confirmed',
            ]);

            $videoConferenceService = new VideoConferenceService();
            $meetingLink = $videoConferenceService->generateLink($appointment);

            $appointment->update([
                'meeting_link' => $meetingLink
            ]);

            $gateway = \App\Models\SystemSetting::where('key', 'active_payment_gateway')->value('value') ?? 'stripe';

            Payment::create([
                'appointment_id' => $appointment->id,
                'user_id' => $client->id,
                'amount' => $amount,
                'currency' => 'TRY',
                'status' => 'completed',
                'payment_method' => $gateway,
                'transaction_id' => $transactionId,
            ]);

            DB::commit();

            return $appointment;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
