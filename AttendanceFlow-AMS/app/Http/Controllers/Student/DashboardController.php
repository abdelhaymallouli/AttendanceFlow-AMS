<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $studentProfile = $user->studentProfile;

        // Calculate real stats
        $totalSessions = $studentProfile->attendanceRecords()->count();
        $presentSessions = $studentProfile->attendanceRecords()->whereIn('status', ['present', 'late'])->count();
        $attendanceRate = $totalSessions > 0 ? round(($presentSessions / $totalSessions) * 100) : 100;

        $stats = [
            'attendance_rate' => $attendanceRate,
            'total_absences' => $studentProfile->attendanceRecords()->where('status', 'absent')->count() * 2.5, // assuming 2.5h per session
            'justified_absences' => $studentProfile->attendanceRecords()->where('status', 'justified')->count() * 2.5,
            'upcoming_sessions' => Session::where('group_id', $studentProfile->group_id)
                ->where('start_time', '>=', now())
                ->orderBy('start_time', 'asc')
                ->take(3)
                ->get(),
        ];

        return view('student.dashboard', compact('studentProfile', 'stats'));
    }
}
