<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Show the attendance marking form for a session.
     */
    public function show(Session $session)
    {
        // Authorization check (Admins can access everything, Teachers only their own)
        if (!Auth::user()->hasRole('admin') && $session->teacher_profile_id !== Auth::user()->teacherProfile->id) {
            abort(403, 'Unauthorized access to this session.');
        }

        // Load the group's students
        $students = $session->group->studentProfiles()->with('user')->get();
        
        // Load existing records for this session
        $existingRecords = AttendanceRecord::where('session_id', $session->id)->get()->pluck('status', 'student_profile_id');

        return view('teacher.attendance.show', compact('session', 'students', 'existingRecords'));
    }

    /**
     * Store the attendance records for the session.
     */
    public function store(Request $request, Session $session)
    {
        // Authorization check
        if (!Auth::user()->hasRole('admin') && $session->teacher_profile_id !== Auth::user()->teacherProfile->id) {
            abort(403);
        }

        $request->validate([
            'attendance' => 'required|array',
            'attendance.*' => 'required|in:present,absent,late',
        ]);

        foreach ($request->attendance as $studentId => $status) {
            AttendanceRecord::updateOrCreate(
                [
                    'session_id' => $session->id,
                    'student_profile_id' => $studentId,
                ],
                [
                    'status' => $status,
                    'date' => \Carbon\Carbon::parse($session->start_time)->toDateString(),
                ]
            );
        }

        $redirectRoute = Auth::user()->hasRole('admin') ? 'admin.dashboard' : 'teacher.dashboard';
        return redirect()->route($redirectRoute)->with('success', 'Attendance saved successfully!');
    }
}
