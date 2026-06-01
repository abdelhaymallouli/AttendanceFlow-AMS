<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportingService;

class ReportController extends Controller
{
    private ReportingService $reportingService;

    public function __construct(ReportingService $reportingService)
    {
        $this->reportingService = $reportingService;
    }

    public function index()
    {
        return view('admin.reports', [
            'overview' => $this->reportingService->getOverviewStats(),
            'groups' => $this->reportingService->getGroupRanking(),
            'atRiskStudents' => $this->reportingService->getAtRiskStudents(),
            'monthlyTrend' => $this->reportingService->getMonthlyTrend(),
        ]);
    }
}