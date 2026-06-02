<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ReportingService;

class StatsController extends Controller
{
    protected ReportingService $reportingService;

    public function __construct(ReportingService $reportingService)
    {
        $this->reportingService = $reportingService;
    }

    public function getAdminStats()
    {
        return response()->json($this->reportingService->getAdminOverview());
    }

    public function getStudentStats($id)
    {
        return response()->json($this->reportingService->getStudentStats($id));
    }
}
