<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display a list of the teacher's sessions for attendance marking.
     */
    public function index(Request $request)
    {
        $teacherProfile = Auth::user()->teacherProfile;

        if (!$teacherProfile) {
            return redirect()->route('teacher.dashboard')->with('error', 'Teacher profile not found.');
        }

        $date = $request->input('date', Carbon::today()->toDateString());

        $sessions = Session::with(['module', 'group'])
            ->where('teacher_profile_id', $teacherProfile->id)
            ->whereDate('start_time', $date)
            ->orderBy('start_time')
            ->get();

        return view('teacher.attendance.index', compact('sessions', 'date'));
    }

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

        $studentsData = $students->map(fn($s) => [
            'id' => $s->id,
            'name' => $s->user->name,
            'email' => $s->user->email,
            'matricule' => $s->matricule,
        ]);

        // Load existing records for this session
        $existingRecords = AttendanceRecord::where('session_id', $session->id)->get()->pluck('status', 'student_profile_id');

        return view('teacher.attendance.show', compact('session', 'students', 'studentsData', 'existingRecords'));
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
