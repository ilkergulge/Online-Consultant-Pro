<?php

namespace App\Services;

use App\Models\SystemSetting;
use App\Models\User;
use Exception;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Illuminate\Http\Request;

class PaymentService
{
    public function processPayment(User $consultant, array $bookingDetails, string $bookingToken)
    {
        $gateway = SystemSetting::where('key', 'active_payment_gateway')->value('value') ?? 'stripe';

        if ($gateway === 'stripe') {
            return $this->processStripe($consultant, $bookingDetails, $bookingToken);
        } elseif ($gateway === 'iyzico') {
            return $this->processIyzico($consultant, $bookingDetails, $bookingToken);
        } elseif ($gateway === 'shopier') {
            return $this->processShopier($consultant, $bookingDetails, $bookingToken);
        }

        throw new Exception("Payment gateway not supported yet.");
    }

    protected function processStripe(User $consultant, array $bookingDetails, string $bookingToken)
    {
        $secret = SystemSetting::where('key', 'stripe_secret_key')->value('value') ?? env('STRIPE_SECRET_KEY');
        Stripe::setApiKey($secret);

        $amount = (float) $consultant->consultantProfile->hourly_rate;

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'try',
                    'product_data' => [
                        'name' => 'Consultation with ' . $consultant->name,
                        'description' => 'Date: ' . $bookingDetails['date'] . ' Time: ' . $bookingDetails['time'],
                    ],
                    'unit_amount' => (int) round($amount * 100), // Stripe expects cents, use int to avoid float precision issues
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('checkout.success') . '?token=' . $bookingToken . '&session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.failure'),
        ]);

        return $session->url;
    }

    public function verifyPayment(Request $request, string $sessionId)
    {
        $gateway = SystemSetting::where('key', 'active_payment_gateway')->value('value') ?? 'stripe';

        if ($gateway === 'stripe') {
            $secret = SystemSetting::where('key', 'stripe_secret_key')->value('value') ?? env('STRIPE_SECRET_KEY');
            Stripe::setApiKey($secret);
            $session = StripeSession::retrieve($sessionId);
            return $session->payment_status === 'paid';
        } elseif ($gateway === 'iyzico') {
            // Iyzico requires fetching the result using the token
            $options = new \Iyzipay\Options();
            $options->setApiKey(SystemSetting::where('key', 'iyzico_api_key')->value('value'));
            $options->setSecretKey(SystemSetting::where('key', 'iyzico_secret_key')->value('value'));
            $options->setBaseUrl("https://api.iyzipay.com");

            $req = new \Iyzipay\Request\RetrieveCheckoutFormRequest();
            $req->setLocale(\Iyzipay\Model\Locale::TR);
            $req->setToken($sessionId); // For Iyzico, the token from the post is the session id

            $checkoutForm = \Iyzipay\Model\CheckoutForm::retrieve($req, $options);
            return $checkoutForm->getPaymentStatus() === 'SUCCESS';
        } elseif ($gateway === 'shopier') {
            // Shopier verification involves checking the signature from POST data
            $postData = $request->all();
            $secret = SystemSetting::where('key', 'shopier_api_secret')->value('value');
            if(isset($postData['signature']) && isset($postData['random_nr']) && isset($postData['id'])) {
               $expected = base64_encode(hash_hmac('sha256',$postData['random_nr'].$postData['id'], $secret, true));
               return $postData['signature'] === $expected && $postData['status'] === 'success';
            }

            // Only fallback to mock if explicitly running locally and config allows mock
            if(app()->environment('local') && strpos($sessionId, 'shopier_mock_tx_') !== false) {
                return true;
            }
            return false;
        }

        return false;
    }

    protected function processIyzico(User $consultant, array $bookingDetails, string $bookingToken)
    {
        $amount = (float) $consultant->consultantProfile->hourly_rate;

        $options = new \Iyzipay\Options();
        $options->setApiKey(SystemSetting::where('key', 'iyzico_api_key')->value('value'));
        $options->setSecretKey(SystemSetting::where('key', 'iyzico_secret_key')->value('value'));
        $options->setBaseUrl("https://api.iyzipay.com");

        $request = new \Iyzipay\Request\CreateCheckoutFormInitializeRequest();
        $request->setLocale(\Iyzipay\Model\Locale::TR);
        $request->setConversationId($bookingToken);
        $request->setPrice($amount);
        $request->setPaidPrice($amount);
        $request->setCurrency(\Iyzipay\Model\Currency::TL);
        $request->setBasketId("B" . $bookingDetails['consultant_id']);
        $request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
        $request->setCallbackUrl(route('checkout.success') . '?token=' . $bookingToken);

        $buyer = new \Iyzipay\Model\Buyer();
        $buyer->setId($bookingDetails['client_id']);
        $buyer->setName("Client Name");
        $buyer->setSurname("Client Surname");
        $buyer->setGsmNumber("+905555555555");
        $buyer->setEmail("email@email.com");
        $buyer->setIdentityNumber("74300864791");
        $buyer->setLastLoginDate("2015-10-05 12:43:35");
        $buyer->setRegistrationDate("2013-04-21 15:12:09");
        $buyer->setRegistrationAddress("Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1");
        $buyer->setIp("85.34.78.112");
        $buyer->setCity("Istanbul");
        $buyer->setCountry("Turkey");
        $buyer->setZipCode("34732");
        $request->setBuyer($buyer);

        $shippingAddress = new \Iyzipay\Model\Address();
        $shippingAddress->setContactName("Jane Doe");
        $shippingAddress->setCity("Istanbul");
        $shippingAddress->setCountry("Turkey");
        $shippingAddress->setAddress("Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1");
        $shippingAddress->setZipCode("34742");
        $request->setShippingAddress($shippingAddress);

        $billingAddress = new \Iyzipay\Model\Address();
        $billingAddress->setContactName("Jane Doe");
        $billingAddress->setCity("Istanbul");
        $billingAddress->setCountry("Turkey");
        $billingAddress->setAddress("Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1");
        $billingAddress->setZipCode("34742");
        $request->setBillingAddress($billingAddress);

        $basketItems = array();
        $firstBasketItem = new \Iyzipay\Model\BasketItem();
        $firstBasketItem->setId("BI101");
        $firstBasketItem->setName("Consultation");
        $firstBasketItem->setCategory1("Consulting");
        $firstBasketItem->setItemType(\Iyzipay\Model\BasketItemType::VIRTUAL);
        $firstBasketItem->setPrice($amount);
        $basketItems[0] = $firstBasketItem;
        $request->setBasketItems($basketItems);

        $checkoutFormInitialize = \Iyzipay\Model\CheckoutFormInitialize::create($request, $options);

        if ($checkoutFormInitialize->getStatus() == "success") {
            return $checkoutFormInitialize->getPaymentPageUrl();
        } else {
            throw new Exception("Iyzico initialization failed: " . $checkoutFormInitialize->getErrorMessage());
        }
    }

    protected function processShopier(User $consultant, array $bookingDetails, string $bookingToken)
    {
        // Direct generation logic for shopier instead of requiring a full SDK
        $amount = (float) $consultant->consultantProfile->hourly_rate;
        $apiKey = SystemSetting::where('key', 'shopier_api_key')->value('value');
        $apiSecret = SystemSetting::where('key', 'shopier_api_secret')->value('value');

        // To submit to shopier, we actually need to render a form that auto submits.
        // For API redirect mode, Shopier allows direct generation:
        $price = $amount;
        $order_id = $bookingToken;

        $client = User::find($bookingDetails['client_id']);

        $args = array(
            'API_key' => $apiKey,
            'website_index' => 1,
            'platform_order_id' => $order_id,
            'product_name' => 'Consultation',
            'product_type' => 1, //1 for download/virtual
            'buyer_name' => $client->name ?? 'Client',
            'buyer_surname' => 'User',
            'buyer_email' => $client->email ?? 'client@test.com',
            'buyer_account_age' => 0,
            'buyer_id_nr' => 0,
            'buyer_phone' => '05555555555',
            'billing_address' => 'Digital Delivery',
            'billing_city' => 'Istanbul',
            'billing_country' => 'Turkey',
            'billing_postcode' => '34000',
            'shipping_address' => 'Digital Delivery',
            'shipping_city' => 'Istanbul',
            'shipping_country' => 'Turkey',
            'shipping_postcode' => '34000',
            'total_order_value' => $price,
            'currency' => 0, //0 for TRY
            'setting_transfer' => 1, // Send to success url automatically
            'setting_success_url' => route('checkout.success') . '?token=' . $bookingToken,
            'setting_cancel_url' => route('checkout.failure'),
        );

        $data = $args["random_nr"] = rand(100000,999999);
        $signature = hash_hmac('sha256', $order_id . $price . $data, $apiSecret, true);
        $signature = base64_encode($signature);
        $args['signature'] = $signature;

        // Cache the args to render them on a local intermediary page
        \Illuminate\Support\Facades\Cache::put('shopier_args_' . $bookingToken, $args, now()->addMinutes(10));

        return route('checkout.shopier', ['token' => $bookingToken]);
    }
}
