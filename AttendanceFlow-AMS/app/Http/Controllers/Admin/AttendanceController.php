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
    $date = $request->input('date');

    if (!$date || $date === 'undefined' || !strtotime($date)) {
        $date = \Carbon\Carbon::today()->toDateString();

        return redirect()->route('admin.attendance.index', [
            'date' => $date
        ]);
    }

    $start = \Carbon\Carbon::parse($date)->startOfDay();
    $end = \Carbon\Carbon::parse($date)->endOfDay();

    $sessions = Session::with(['module', 'group', 'teacherProfile.user'])
        ->whereBetween('start_time', [$start, $end])
        ->orderBy('start_time')
        ->get();

    $allSessionsData = $sessions->map(function ($session) {
        $start = \Carbon\Carbon::parse($session->start_time);
        $end = \Carbon\Carbon::parse($session->end_time);

        return [
            'id' => $session->id,
            'start_time' => $start->format('Y-m-d H:i:s'),
            'time' => $start->format('H:i') . ' - ' . $end->format('H:i'),
            'duration' => $end->diffInHours($start),
            'module' => $session->module->name ?? '',
            'group' => $session->group->name ?? '',
            'teacher' => $session->teacherProfile->user->name ?? '',
            'url' => route('admin.attendance.show', ['session' => $session->id]),
        ];
    })->values();

    return view('admin.attendance.index', [
        'sessions' => $sessions,
        'date' => $date,
        'allSessionsData' => $allSessionsData
    ]);
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
        $sessionDate = Carbon::parse($session->start_time)->toDateString();

        // Delete records for students who are no longer marked (cleared/unmarked)
        \App\Models\AttendanceRecord::where('session_id', $session->id)
            ->whereNotIn('student_profile_id', $submittedStudentIds)
            ->delete();

        $attendanceService = app(\App\Services\AttendanceService::class);

        foreach ($attendanceData as $studentId => $status) {
            $attendanceService->markAttendance((int) $studentId, $session->id, $status, $sessionDate);
        }

        return redirect()
            ->route('admin.attendance.index', ['date' => $sessionDate])
            ->with('success', 'Attendance saved successfully!');
    }
}
