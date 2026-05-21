<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ReportService;

class DashboardController extends Controller
{
    public function index(ReportService $reportService)
    {
        $metrics = $reportService->getDashboardMetrics();
        return view('admin.dashboard', compact('metrics'));
    }
}
