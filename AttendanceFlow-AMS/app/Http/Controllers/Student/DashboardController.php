<?php

namespace App\Http\Controllers\Student;

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
        $user = Auth::user();
        $studentProfile = $user->studentProfile;
        
        if (!$studentProfile) {
            return redirect()->route('home')->with('error', 'Student profile not found.');
        }

        $data = $this->reportingService->getStudentDashboardData($studentProfile->id);
        
        $studentProfile = $data['studentProfile'];
        $stats = [
            'attendance_rate' => $data['attendanceRate'],
            'total_absences' => $data['totalAbsences'],
            'upcoming_sessions' => $data['upcomingSessions'],
        ];
        $recentAbsences = $data['recentAbsences'];
        $recentHistory = $data['recentHistory'];
        $notifications = \App\Models\Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('student.dashboard', compact('studentProfile', 'stats', 'recentAbsences', 'recentHistory', 'notifications'));
    }
}
