<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Session;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $studentProfile = $user->studentProfile;
        
        if (!$studentProfile) {
            return redirect()->route('home')->with('error', 'Student profile not found.');
        }

        $attendanceRecords = $studentProfile->attendanceRecords()->with('session.module')->get();
        $totalSessions = $attendanceRecords->count();
        $presentSessions = $attendanceRecords->whereIn('status', ['present', 'late'])->count();
        $attendanceRate = $totalSessions > 0 ? round(($presentSessions / $totalSessions) * 100) : 100;

        $totalAbsenceHours = $attendanceRecords->where('status', 'absent')->sum(fn($r) => $r->session->duration_hours ?? 0);

        $stats = [
            'attendance_rate' => $attendanceRate,
            'total_absences' => round($totalAbsenceHours, 1),
            'upcoming_sessions' => Session::where('group_id', $studentProfile->group_id)
                ->where('start_time', '>=', now())
                ->orderBy('start_time', 'asc')
                ->with('module')
                ->take(3)
                ->get(),
        ];

        $recentAbsences = $attendanceRecords->whereIn('status', ['absent', 'late'])
            ->sortByDesc('date')
            ->take(10)
            ->values();

        $recentHistory = $attendanceRecords->sortByDesc('date')->take(5)->values();

        return view('student.dashboard', compact('studentProfile', 'stats', 'recentAbsences', 'recentHistory'));
    }
}
