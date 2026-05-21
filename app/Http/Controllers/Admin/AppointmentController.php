<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['client', 'consultant'])->orderBy('scheduled_at', 'desc')->get();
        return view('admin.appointments.index', compact('appointments'));
    }

    public function edit(Appointment $appointment)
    {
        return view('admin.appointments.edit', compact('appointment'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'meeting_link' => 'nullable|url',
        ]);
        $appointment->update($data);
        return redirect()->route('admin.appointments.index')->with('success', 'Appointment updated.');
    }
}
