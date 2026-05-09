<?php

namespace App\Services;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public static function sendSms(string $phone, string $message)
    {
        $apiKey = SystemSetting::where('key', 'sms_api_key')->value('value');

        if (!$apiKey) {
            Log::warning("SMS failed: No SMS API Key configured. Message to {$phone}: {$message}");
            return false;
        }

        // Mock implementation for Netgsm/Twilio etc.
        // In a real scenario, an Http::post request would be made here to the chosen provider.
        Log::info("SMS sent to {$phone}: {$message}");
        return true;
    }
}
