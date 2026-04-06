<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentProfile;
use App\Models\Group;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = StudentProfile::with(['user', 'group']);

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('matricule', 'like', "%{$search}%");
            });
        }

        // Filter by Group
        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
        }

        $students = $query->paginate(10);
        $groups = Group::all();

        // Stats
        $totalStudents = StudentProfile::count();
        $atRiskCount = StudentProfile::all()->filter(function($student) {
            $total = AttendanceRecord::where('student_profile_id', $student->id)->count();
            $present = AttendanceRecord::where('student_profile_id', $student->id)
                ->whereIn('status', ['present', 'late'])->count();
            $rate = $total > 0 ? ($present / $total) * 100 : 100;
            return $rate < 90;
        })->count();

        return view('admin.students.index', compact('students', 'groups', 'totalStudents', 'atRiskCount'));
    }
}
