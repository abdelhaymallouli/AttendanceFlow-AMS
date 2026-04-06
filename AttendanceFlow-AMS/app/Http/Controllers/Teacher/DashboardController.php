<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the teacher dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->route('login')->withErrors(['email' => 'Teacher profile not found.']);
        }

        // Get all sessions for this teacher
        $sessions = Session::where('teacher_profile_id', $teacherProfile->id)
            ->with(['module', 'group'])
            ->orderBy('start_time', 'asc')
            ->get();

        // Determine current session (if any)
        $now = Carbon::now();
        $currentSession = $sessions->filter(function($session) use ($now) {
            return $now->between($session->start_time, $session->end_time);
        })->first();

        // Basic Stats
        $stats = [
            'total_students' => $user->teacherProfile->groups()->withCount('studentProfiles')->get()->sum('student_profiles_count'),
            'today_sessions_count' => $sessions->filter(fn($s) => Carbon::parse($s->start_time)->isToday())->count(),
            'pending_justifications' => \App\Models\Justification::where('status', 'pending')->count(),
            'avg_attendance' => 92, // Still mocked for now
        ];

        return view('teacher.dashboard', compact('teacherProfile', 'sessions', 'currentSession', 'stats'));
    }
}
