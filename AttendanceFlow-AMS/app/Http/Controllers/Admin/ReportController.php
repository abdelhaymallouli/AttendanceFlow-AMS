<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\StudentProfile;
use App\Models\Justification;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // 1. Overview Stats
        $totalRecords = AttendanceRecord::count();
        $presentRecords = AttendanceRecord::whereIn('status', ['present', 'late'])->count();
        $absentRecords = AttendanceRecord::where('status', 'absent')->count();
        $lateRecords = AttendanceRecord::where('status', 'late')->count();
        
        $avgAttendance = $totalRecords > 0 ? round(($presentRecords / $totalRecords) * 100, 1) : 0;
        $absenceRate = $totalRecords > 0 ? round(($absentRecords / $totalRecords) * 100, 1) : 0;
        
        $totalJustifications = Justification::count();
        $approvedJustifications = Justification::where('status', 'approved')->count();
        $justifiedRate = $absentRecords > 0 ? round(($approvedJustifications / $absentRecords) * 100, 1) : 0;

        // 2. Class Performance Ranking
        $groups = Group::withCount(['studentProfiles'])->get()->map(function($group) {
            $studentIds = $group->studentProfiles->pluck('id');
            $total = AttendanceRecord::whereIn('student_profile_id', $studentIds)->count();
            $present = AttendanceRecord::whereIn('student_profile_id', $studentIds)
                ->whereIn('status', ['present', 'late'])->count();
            
            $group->attendance_rate = $total > 0 ? round(($present / $total) * 100, 1) : 0;
            return $group;
        })->sortByDesc('attendance_rate');

        // 3. At-Risk Students (< 90%)
        $atRiskStudents = StudentProfile::with('user')->get()->map(function($student) {
            $total = AttendanceRecord::where('student_profile_id', $student->id)->count();
            $present = AttendanceRecord::where('student_profile_id', $student->id)
                ->whereIn('status', ['present', 'late'])->count();
            
            $student->attendance_rate = $total > 0 ? round(($present / $total) * 100, 1) : 0;
            $student->absences_count = AttendanceRecord::where('student_profile_id', $student->id)
                ->where('status', 'absent')->count();
            
            return $student;
        })->filter(function($student) {
            return $student->attendance_rate < 90 && $student->absences_count > 0;
        })->sortBy('attendance_rate');

        // 4. Monthly Trend (Mocked logic for now or real if data spans months)
        $monthlyTrend = AttendanceRecord::select(
                DB::raw('MONTH(date) as month'),
                DB::raw('count(*) as total'),
                DB::raw('count(case when status != "absent" then 1 end) as present')
            )
            ->groupBy('month')
            ->get()
            ->map(function($item) {
                return [
                    'month' => date("M", mktime(0, 0, 0, $item->month, 10)),
                    'rate' => round(($item->present / $item->total) * 100, 1)
                ];
            });

        return view('admin.reports', compact(
            'avgAttendance', 'absenceRate', 'lateRecords', 'justifiedRate', 
            'groups', 'atRiskStudents', 'monthlyTrend', 'approvedJustifications'
        ));
    }
}
