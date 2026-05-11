<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Session;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display a list of sessions for which to mark attendance.
     */
public function index(Request $request)
{
    $date = $request->input('date', \Carbon\Carbon::today()->toDateString());

    $query = Session::with(['module', 'group', 'teacherProfile.user']);

    if ($date) {
        $query->whereDate('start_time', $date);
    }

    $sessions = $query->orderBy('start_time')->get();

    return view('admin.attendance.index', compact('sessions', 'date'));
}

    /**
     * Show the attendance marking form for a session.
     */
    public function show(Session $session)
    {
        // Load the group's students
        $students = $session->group->studentProfiles()->with('user')->get();
        
        // Load existing records for this session
        $existingRecords = \App\Models\AttendanceRecord::where('session_id', $session->id)->get()->pluck('status', 'student_profile_id');

        return view('admin.attendance.show', compact('session', 'students', 'existingRecords'));
    }

    /**
     * Store the attendance records for the session.
     */
    public function store(Request $request, Session $session)
    {
        $request->validate([
            'attendance' => 'nullable|array',
            'attendance.*' => 'in:present,absent,late',
        ]);

        $attendanceData = $request->attendance ?? [];
        $submittedStudentIds = array_keys($attendanceData);

        // Delete records for students who are no longer marked (cleared/unmarked)
        \App\Models\AttendanceRecord::where('session_id', $session->id)
            ->whereNotIn('student_profile_id', $submittedStudentIds)
            ->delete();

        foreach ($attendanceData as $studentId => $status) {
            \App\Models\AttendanceRecord::updateOrCreate(
                [
                    'session_id' => $session->id,
                    'student_profile_id' => $studentId,
                ],
                [
                    'status' => $status,
                    'date' => Carbon::parse($session->start_time)->toDateString(),
                ]
            );
        }

        return redirect()->route('admin.attendance.index')->with('success', 'Attendance saved successfully!');
    }
}
