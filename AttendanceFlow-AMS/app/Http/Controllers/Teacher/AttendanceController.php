<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    protected AttendanceService $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

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

        // Prepare session data for Alpine.js component
        $sessionsData = $sessions->map(function($session) {
            $start = \Carbon\Carbon::parse($session->start_time);
            $end = \Carbon\Carbon::parse($session->end_time);
            return [
                'id' => $session->id,
                'start_time' => $start->format('Y-m-d H:i:s'),
                'time' => $start->format('H:i') . ' - ' . $end->format('H:i'),
                'duration' => $end->diffInHours($start),
                'module' => $session->module->name,
                'group' => $session->group->name,
                'url' => route('teacher.sessions.attendance.show', $session->id)
            ];
        })->values();

        return view('teacher.attendance.index', compact('sessions', 'date', 'sessionsData'));
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
        $existingRecords = $this->attendanceService->getSessionAttendance($session->id)->pluck('status', 'student_profile_id');

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

        $sessionDate = \Carbon\Carbon::parse($session->start_time)->toDateString();
        foreach ($request->attendance as $studentId => $status) {
            $this->attendanceService->markAttendance((int) $studentId, $session->id, $status, $sessionDate);
        }

        $redirectRoute = Auth::user()->hasRole('admin') ? 'admin.dashboard' : 'teacher.dashboard';
        return redirect()->route($redirectRoute)->with('success', 'Attendance saved successfully!');
    }
}
