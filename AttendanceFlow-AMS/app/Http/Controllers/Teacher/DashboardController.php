<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\AttendanceRecord;
use App\Models\Justification;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;  

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $teacherProfile = $user->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->route('login')->withErrors(['email' => 'Teacher profile not found.']);
        }

        $sessionIds = $teacherProfile->sessions()->pluck('id');

        $sessions = Session::whereIn('id', $sessionIds)
            ->with(['module', 'group'])
            ->whereDate('start_time', Carbon::today())
            ->orderBy('start_time', 'asc')
            ->get();

        $now = Carbon::now();
        $currentSession = $sessions->filter(function($session) use ($now) {
            return $now->between($session->start_time, $session->end_time);
        })->first();

        $sessionsData = $sessions->map(fn($s) => [
            'id' => $s->id,
            'start_time' => $s->start_time->format('H:i'),
            'end_time' => $s->end_time->format('H:i'),
            'time' => $s->start_time->format('H:i') . ' - ' . $s->end_time->format('H:i'),
            'duration_hours' => $s->duration_hours,
            'type' => $s->type,
            'typeLabel' => $s->type === 'lecture' ? 'Lecture' : ($s->type === 'td' ? 'TD' : 'TP'),
            'moduleName' => $s->module->name,
            'groupName' => $s->group->name,
            'studentsCount' => $s->group->studentProfiles()->count(),
            'status' => 'upcoming',
            'url' => route('teacher.sessions.attendance.show', $s->id),
        ]);

        $totalRecords = AttendanceRecord::whereIn('session_id', $sessionIds)->count();
        $presentRecords = AttendanceRecord::whereIn('session_id', $sessionIds)->where('status', 'present')->count();

        $stats = [
            'total_students' => $teacherProfile->groups()->withCount('studentProfiles')->get()->sum('student_profiles_count'),
            'today_sessions_count' => $sessions->filter(fn($s) => Carbon::parse($s->start_time)->isToday())->count(),
            'pending_justifications' => Justification::where('status', 'pending')->count(),
            'avg_attendance' => $totalRecords > 0 ? round($presentRecords / $totalRecords * 100) : 0,
        ];

        $teacherGroups = $teacherProfile->groups()
            ->withCount(['studentProfiles'])
            ->get()
            ->map(function ($group) use ($teacherProfile) {
                $sessionIds = Session::where('teacher_profile_id', $teacherProfile->id)
                    ->where('group_id', $group->id)
                    ->pluck('id');

                $total = AttendanceRecord::whereIn('session_id', $sessionIds)->count();
                $present = AttendanceRecord::whereIn('session_id', $sessionIds)
                    ->where('status', 'present')
                    ->count();

                return [
                    'id' => $group->id,
                    'name' => $group->name,
                    'studentCount' => $group->student_profiles_count,
                    'attendanceRate' => $total > 0 ? round($present / $total * 100) : 0,
                ];
            })->values();

        $sessionIdsCollection = $sessionIds;

        $recentAttendance = AttendanceRecord::whereIn('session_id', $sessionIdsCollection)
            ->with(['studentProfile.user', 'session.module'])
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($r) => [
                'type' => 'attendance',
                'icon' => 'check',
                'iconBg' => 'bg-green-100',
                'iconColor' => 'text-green-600',
                'bg' => 'bg-green-50',
                'message' => "{$r->studentProfile->user->name} marked as {$r->status}",
                'detail' => $r->session->module->name,
                'time' => $r->created_at ? $r->created_at->diffForHumans() : '',
            ]);

        $groupIds = $teacherProfile->groups()->pluck('groups.id');

        $recentJustifications = Justification::whereIn('student_profile_id', function($q) use ($groupIds) {
                $q->select('id')->from('student_profiles')
                  ->whereIn('group_id', $groupIds);
            })
            ->with(['studentProfile.user'])
            ->latest()
            ->take(3)
            ->get()
            ->map(fn($j) => [
                'type' => 'justification',
                'icon' => 'file-check',
                'iconBg' => $j->status === 'accepted' ? 'bg-green-100' : ($j->status === 'rejected' ? 'bg-red-100' : 'bg-blue-100'),
                'iconColor' => $j->status === 'accepted' ? 'text-green-600' : ($j->status === 'rejected' ? 'text-red-600' : 'text-blue-600'),
                'bg' => $j->status === 'accepted' ? 'bg-green-50' : ($j->status === 'rejected' ? 'bg-red-50' : 'bg-blue-50'),
                'message' => "Justification {$j->status} for {$j->studentProfile->user->name}",
                'detail' => $j->reason,
                'time' => $j->updated_at ? $j->updated_at->diffForHumans() : '',
            ]);

        $recentActivity = $recentAttendance->concat($recentJustifications)
            ->sortByDesc('time')
            ->take(7)
            ->values();

        return view('teacher.dashboard', compact(
            'teacherProfile', 'sessionsData', 'currentSession', 'stats', 'recentActivity', 'teacherGroups'
        ));
    }
}
