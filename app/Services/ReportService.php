<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Payment;
use App\Models\User;

class ReportService
{
    public function getDashboardMetrics()
    {
        return [
            'total_users' => User::count(),
            'total_consultants' => User::where('role', 'consultant')->count(),
            'total_appointments' => Appointment::count(),
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            'recent_appointments' => Appointment::with(['client', 'consultant'])->orderBy('created_at', 'desc')->take(5)->get(),
        ];
    }
}
