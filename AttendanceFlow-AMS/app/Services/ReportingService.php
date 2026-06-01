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
}