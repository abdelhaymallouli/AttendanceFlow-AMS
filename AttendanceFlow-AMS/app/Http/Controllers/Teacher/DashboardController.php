<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Services\ReportingService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected ReportingService $reportingService;

    public function __construct(ReportingService $reportingService)
    {
        $this->reportingService = $reportingService;
    }

    public function index()
    {
        $teacherProfile = Auth::user()->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->route('login')->withErrors(['email' => 'Teacher profile not found.']);
        }

        $data = $this->reportingService->getTeacherDashboardData($teacherProfile->id);

        return view('teacher.dashboard', compact(
            'teacherProfile', 
            'data'
        ));
    }
}
