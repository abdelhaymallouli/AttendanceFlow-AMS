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
        $stats = [
            'total_students' => StudentProfile::count(),
            'total_teachers' => TeacherProfile::count(),
            'pending_justifications' => 7, // Mocked
            'global_attendance' => 88, // Mocked
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
