<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    public function show(Request $request, Appointment $appointment)
    {
        // Security check: Only the consultant or client of the appointment can join
        $user = $request->user();
        if ($appointment->client_id !== $user->id && $appointment->consultant_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $roomName = $request->query('room', 'consultapp-' . $appointment->id);

        return view('frontend.meeting.jitsi', compact('appointment', 'roomName', 'user'));
    }
}
