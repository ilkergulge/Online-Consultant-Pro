<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsultantEarningController extends Controller
{
    public function index()
    {
        $earnings = DB::table('payments')
            ->join('appointments', 'payments.appointment_id', '=', 'appointments.id')
            ->join('users', 'appointments.consultant_id', '=', 'users.id')
            ->select('users.name as consultant_name', DB::raw('SUM(payments.amount) as total_earnings'), DB::raw('COUNT(payments.id) as total_appointments'))
            ->where('payments.status', 'completed')
            ->groupBy('users.id', 'users.name')
            ->get();

        return view('admin.consultant_earnings.index', compact('earnings'));
    }
}
