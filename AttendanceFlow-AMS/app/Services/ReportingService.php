<?php

namespace App\Services;

use App\Models\AttendanceRecord;
use App\Models\Group;
use App\Models\StudentProfile;
use App\Models\Justification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * ReportingService
 * Central analytics & reporting layer.
 */
class ReportingService extends BaseService
{
    /**
     * GROUP REPORT (existing)
     */
    public function generateGroupReport(int $groupId, string $startDate, string $endDate): array
    {
        $this->logInfo("Generating attendance report for group {$groupId}");

        $records = AttendanceRecord::whereHas('studentProfile', function ($query) use ($groupId) {
                $query->where('group_id', $groupId);
            })
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        return $this->calculateStats($records);
    }

    /**
     * PURE STATS CALCULATION (unchanged logic)
     */
    public function calculateStats(Collection $records): array
    {
        $total = $records->count();
        $present = $records->where('status', 'present')->count();
        $absent = $records->whereIn('status', ['absent', 'justified'])->count();

        $rate = $total > 0 ? round(($present / $total) * 100, 2) : 0;

        return [
            'total_records' => $total,
            'present' => $present,
            'absent_total' => $absent,
            'attendance_rate' => $rate . '%',
        ];
    }

    /**
     * 1. OVERVIEW STATS (moved from controller)
     */
    public function getOverviewStats(): array
    {
        $totalRecords = AttendanceRecord::count();

        $presentRecords = AttendanceRecord::whereIn('status', ['present', 'late'])->count();

        $absentRecords = AttendanceRecord::whereIn('status', [
            'absent_unexcused',
            'absent_excused'
        ])->count();

        $lateRecords = AttendanceRecord::where('status', 'late')->count();

        $avgAttendance = $totalRecords > 0
            ? round(($presentRecords / $totalRecords) * 100, 1)
            : 0;

        $absenceRate = $totalRecords > 0
            ? round(($absentRecords / $totalRecords) * 100, 1)
            : 0;

        $totalJustifications = Justification::count();

        $approvedJustifications = Justification::where('status', 'approved')->count();

        $justifiedRate = $absentRecords > 0
            ? round(($approvedJustifications / $absentRecords) * 100, 1)
            : 0;

        return [
            'avgAttendance' => $avgAttendance,
            'absenceRate' => $absenceRate,
            'lateRecords' => $lateRecords,
            'justifiedRate' => $justifiedRate,
            'approvedJustifications' => $approvedJustifications,
        ];
    }

    /**
     * 2. GROUP PERFORMANCE RANKING
     */
    public function getGroupRanking()
    {
        return Group::with('studentProfiles')
            ->get()
            ->map(function ($group) {

                $studentIds = $group->studentProfiles->pluck('id');

                $total = AttendanceRecord::whereIn('student_profile_id', $studentIds)->count();

                $present = AttendanceRecord::whereIn('student_profile_id', $studentIds)
                    ->whereIn('status', ['present', 'late'])
                    ->count();

                $group->attendance_rate = $total > 0
                    ? round(($present / $total) * 100, 1)
                    : 0;

                return $group;
            })
            ->sortByDesc('attendance_rate')
            ->values();
    }

    /**
     * 3. AT-RISK STUDENTS
     */
    public function getAtRiskStudents(float $threshold = 90)
    {
        return StudentProfile::with('user')
            ->get()
            ->map(function ($student) {

                $total = AttendanceRecord::where('student_profile_id', $student->id)->count();

                $present = AttendanceRecord::where('student_profile_id', $student->id)
                    ->whereIn('status', ['present', 'late'])
                    ->count();

                $absences = AttendanceRecord::where('student_profile_id', $student->id)
                    ->whereIn('status', ['absent_unexcused', 'absent_excused'])
                    ->count();

                $student->attendance_rate = $total > 0
                    ? round(($present / $total) * 100, 1)
                    : 0;

                $student->absences_count = $absences;

                return $student;
            })
            ->filter(function ($student) use ($threshold) {
                return $student->attendance_rate < $threshold
                    && $student->absences_count > 0;
            })
            ->sortBy('attendance_rate')
            ->values();
    }

    /**
     * 4. MONTHLY TREND
     */
    public function getMonthlyTrend(): array
    {
        return AttendanceRecord::select(
                DB::raw('MONTH(date) as month'),
                DB::raw('count(*) as total'),
                DB::raw('count(case when status != "absent_unexcused" and status != "absent_excused" then 1 end) as present')
            )
            ->groupBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => date("M", mktime(0, 0, 0, $item->month, 10)),
                    'rate' => $item->total > 0
                        ? round(($item->present / $item->total) * 100, 1)
                        : 0
                ];
            })
            ->values()
            ->toArray();
    }

    /**
     * Get students with optional search and group filters, with pagination.
     */
    public function getStudentsWithFilters(?string $search = null, ?int $groupId = null, int $perPage = 10): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = StudentProfile::with(['user', 'group']);

        if ($search) {
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('matricule', 'like', "%{$search}%");
            });
        }

        if ($groupId) {
            $query->where('group_id', $groupId);
        }

        return $query->paginate($perPage);
    }

    /**
     * Get the total count of students.
     */
    public function getTotalStudentCount(): int
    {
        return StudentProfile::count();
    }

    /**
     * Get the count of students considered "at-risk" based on attendance rate.
     */
    public function getAtRiskStudentsCount(float $threshold = 90): int
    {
        return StudentProfile::all()->filter(function($student) use ($threshold) {
            $total = AttendanceRecord::where('student_profile_id', $student->id)->count();
            $present = AttendanceRecord::where('student_profile_id', $student->id)
                ->whereIn('status', ['present', 'late'])->count();
            $rate = $total > 0 ? ($present / $total) * 100 : 100;
            return $rate < $threshold;
        })->count();
    }

    /**
     * Get admin overview dashboard statistics.
     */
    public function getAdminOverview(): array
    {
        $totalStudents = StudentProfile::count();
        $totalTeachers = \App\Models\TeacherProfile::count();
        $pendingJustifications = Justification::where('status', 'pending')->count();
        
        $totalAttendanceRecords = AttendanceRecord::count();
        $presentRecords = AttendanceRecord::whereIn('status', ['present', 'late'])->count();
        $attendanceRate = $totalAttendanceRecords > 0 ? round(($presentRecords / $totalAttendanceRecords) * 100, 1) : 0;
        
        return [
            'total_students' => $totalStudents,
            'total_teachers' => $totalTeachers,
            'pending_justifications' => $pendingJustifications,
            'attendance_rate' => $attendanceRate,
            'date' => \Carbon\Carbon::now()->format('l, j F Y')
        ];
    }

    /**
     * Get individual student's stats.
     */
    public function getStudentStats(int $id): array
    {
        $student = StudentProfile::with('user', 'group')->findOrFail($id);
        
        $totalSessions = \App\Models\Session::where('group_id', $student->group_id)->count();
        $absences = AttendanceRecord::where('student_profile_id', $id)->where('status', 'absent')->count();
        $present = AttendanceRecord::where('student_profile_id', $id)->whereIn('status', ['present', 'late'])->count();
        $totalRecords = $absences + $present;
        $rate = $totalRecords > 0 ? round(($present / $totalRecords) * 100) : 0;
        $pending = Justification::where('student_profile_id', $id)->where('status', 'pending')->count();

        return [
            'student_id' => $student->matricule,
            'user_id' => $student->user_id,
            'name' => $student->user->name,
            'group' => $student->group->name,
            'attendance_rate' => $rate,
            'absences' => $absences,
            'pending_justifications' => $pending
        ];
    }

    /**
     * Get all dashboard data for a student.
     */
    public function getStudentDashboardData(int $studentProfileId): array
    {
        $studentProfile = StudentProfile::with('user', 'group')->findOrFail($studentProfileId);
        $attendanceRecords = AttendanceRecord::where('student_profile_id', $studentProfileId)->with('session.module')->get();
        
        $totalSessions = $attendanceRecords->count();
        $presentSessions = $attendanceRecords->whereIn('status', ['present', 'late'])->count();
        $attendanceRate = $totalSessions > 0 ? round(($presentSessions / $totalSessions) * 100) : 100;
        
        $totalAbsenceHours = $attendanceRecords->whereIn('status', ['absent_unexcused', 'absent_excused'])->sum(fn($r) => $r->session->duration_hours ?? 0);
        
        $upcomingSessions = \App\Models\Session::where('group_id', $studentProfile->group_id)
            ->where('start_time', '>=', now())
            ->orderBy('start_time', 'asc')
            ->with('module')
            ->take(3)
            ->get();
            
        $recentAbsences = $attendanceRecords->whereIn('status', ['absent_unexcused', 'absent_excused', 'late'])
            ->sortByDesc('date')
            ->take(10)
            ->values();

        $recentHistory = $attendanceRecords->sortByDesc('date')->take(5)->values();
        
        return [
            'studentProfile' => $studentProfile,
            'attendanceRate' => $attendanceRate,
            'totalAbsences' => round($totalAbsenceHours, 1),
            'upcomingSessions' => $upcomingSessions,
            'recentAbsences' => $recentAbsences,
            'recentHistory' => $recentHistory,
        ];
    }

    /**
     * Get all dashboard data for a teacher.
     */
    public function getTeacherDashboardData(int $teacherProfileId): array
    {
        $teacherProfile = \App\Models\TeacherProfile::findOrFail($teacherProfileId);
        $sessionIds = \App\Models\Session::where('teacher_profile_id', $teacherProfileId)->pluck('id');
        
        $sessions = \App\Models\Session::whereIn('id', $sessionIds)
            ->with(['module', 'group'])
            ->whereDate('start_time', \Carbon\Carbon::today())
            ->orderBy('start_time', 'asc')
            ->get();

        $now = \Carbon\Carbon::now();
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
            'today_sessions_count' => $sessions->filter(fn($s) => \Carbon\Carbon::parse($s->start_time)->isToday())->count(),
            'pending_justifications' => Justification::where('status', 'pending')->count(),
            'avg_attendance' => $totalRecords > 0 ? round($presentRecords / $totalRecords * 100) : 0,
        ];

        $teacherGroups = $teacherProfile->groups()
            ->withCount(['studentProfiles'])
            ->get()
            ->map(function ($group) use ($teacherProfile) {
                $sessionIds = \App\Models\Session::where('teacher_profile_id', $teacherProfile->id)
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

        $recentAttendance = AttendanceRecord::whereIn('session_id', $sessionIds)
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

        return [
            'teacherProfile' => $teacherProfile,
            'sessionsData' => $sessionsData,
            'currentSession' => $currentSession,
            'stats' => $stats,
            'recentActivity' => $recentActivity,
            'teacherGroups' => $teacherGroups,
        ];
    }
}