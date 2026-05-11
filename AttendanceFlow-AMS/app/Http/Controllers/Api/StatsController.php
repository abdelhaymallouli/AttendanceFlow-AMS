<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\Justification;
use App\Models\Session;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StatsController extends Controller
{
    public function getAdminStats()
    {
        $today = Carbon::now()->toDateString();
        
        $totalStudents = StudentProfile::count();
        $totalTeachers = TeacherProfile::count();
        $pendingJustifications = Justification::where('status', 'pending')->count();
        
        // Real attendance calculation based on seeded AttendanceRecord table
        $totalAttendanceRecords = \App\Models\AttendanceRecord::count();
        $presentRecords = \App\Models\AttendanceRecord::whereIn('status', ['present', 'late'])->count();
        $attendanceRate = $totalAttendanceRecords > 0 ? round(($presentRecords / $totalAttendanceRecords) * 100, 1) : 0;
        
        return response()->json([
            'total_students' => $totalStudents,
            'total_teachers' => $totalTeachers,
            'pending_justifications' => $pendingJustifications,
            'attendance_rate' => $attendanceRate,
            'date' => Carbon::now()->format('l, j F Y')
        ]);
    }

    public function getStudentStats($id)
    {
        $student = StudentProfile::with('user', 'group')->findOrFail($id);
        
        $totalSessions = Session::where('group_id', $student->group_id)->count();
        $absences = AttendanceRecord::where('student_profile_id', $id)->where('status', 'absent')->count();
        $present = AttendanceRecord::where('student_profile_id', $id)->whereIn('status', ['present', 'late'])->count();
        $totalRecords = $absences + $present;
        $rate = $totalRecords > 0 ? round(($present / $totalRecords) * 100) : 0;
        $pending = Justification::where('student_profile_id', $id)->where('status', 'pending')->count();

        return response()->json([
            'student_id' => $student->matricule,
            'name' => $student->user->name,
            'group' => $student->group->name,
            'attendance_rate' => $rate,
            'absences' => $absences,
            'pending_justifications' => $pending
        ]);
    }
}
