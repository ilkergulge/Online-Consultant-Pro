<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Exception;

class VideoConferenceService
{
    public function generateLink(Appointment $appointment)
    {
        $integration = SystemSetting::where('key', 'active_video_integration')->value('value') ?? 'jitsi';

        if ($integration === 'zoom') {
            return $this->createZoomMeeting($appointment);
        }

        return $this->createJitsiMeeting($appointment);
    }

    protected function createZoomMeeting(Appointment $appointment)
    {
        $accountId = SystemSetting::where('key', 'zoom_account_id')->value('value');
        $clientId = SystemSetting::where('key', 'zoom_client_id')->value('value');
        $clientSecret = SystemSetting::where('key', 'zoom_client_secret')->value('value');

        if (!$accountId || !$clientId || !$clientSecret) {
            // Fallback to Jitsi if Zoom is not fully configured
            return $this->createJitsiMeeting($appointment);
        }

        try {
            // 1. Get Access Token via Server-to-Server OAuth
            $tokenResponse = Http::withBasicAuth($clientId, $clientSecret)
                ->post("https://zoom.us/oauth/token", [
                    'grant_type' => 'account_credentials',
                    'account_id' => $accountId,
                ]);

            if (!$tokenResponse->successful()) {
                throw new Exception("Zoom OAuth failed: " . $tokenResponse->body());
            }

            $accessToken = $tokenResponse->json()['access_token'];

            // 2. Create Meeting
            $meetingResponse = Http::withToken($accessToken)
                ->post("https://api.zoom.us/v2/users/me/meetings", [
                    'topic' => 'Consultation with ' . $appointment->consultant->name,
                    'type' => 2, // Scheduled meeting
                    'start_time' => $appointment->scheduled_at->format('Y-m-d\TH:i:s\Z'),
                    'duration' => $appointment->duration_minutes,
                    'timezone' => 'UTC',
                    'settings' => [
                        'host_video' => true,
                        'participant_video' => true,
                        'join_before_host' => false,
                        'waiting_room' => true,
                    ]
                ]);

            if (!$meetingResponse->successful()) {
                throw new Exception("Zoom Meeting creation failed: " . $meetingResponse->body());
            }

            return $meetingResponse->json()['join_url'];

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Zoom Integration Error: ' . $e->getMessage());
            // Safe fallback
            return $this->createJitsiMeeting($appointment);
        }
    }

    protected function createJitsiMeeting(Appointment $appointment)
    {
        // Generate a unique, unpredictable room name
        $roomName = 'consultapp-' . $appointment->id . '-' . Str::random(12);

        // Return internal route that will render the Jitsi iframe
        return route('meeting.show', ['appointment' => $appointment->id, 'room' => $roomName]);
    }
}
