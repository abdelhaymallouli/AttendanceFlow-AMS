<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRecords = AttendanceRecord::count();
        $presentRecords = AttendanceRecord::whereIn('status', ['present', 'late'])->count();
        $attendanceRate = $totalRecords > 0 ? round(($presentRecords / $totalRecords) * 100) : 100;

        $stats = [
            'total_students' => StudentProfile::count(),
            'total_teachers' => TeacherProfile::count(),
            'pending_justifications' => \App\Models\Justification::where('status', 'pending')->count(),
            'global_attendance' => $attendanceRate,
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
