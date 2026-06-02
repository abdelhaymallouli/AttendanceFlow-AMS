<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportingService;

class DashboardController extends Controller
{
    protected ReportingService $reportingService;

    public function __construct(ReportingService $reportingService)
    {
        $this->reportingService = $reportingService;
    }

    public function index()
    {
        $overview = $this->reportingService->getAdminOverview();

        $stats = [
            'total_students' => $overview['total_students'],
            'total_teachers' => $overview['total_teachers'],
            'pending_justifications' => $overview['pending_justifications'],
            'global_attendance' => $overview['attendance_rate'],
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
