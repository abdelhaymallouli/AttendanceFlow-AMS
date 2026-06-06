<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\Session;
use App\Models\StudentProfile;
use App\Services\AttendanceService;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function __construct(
        protected AttendanceService $attendanceService,
        protected NotificationService $notifications,
    ) {
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

        $sessions = Session::with(['module', 'group', 'attendanceRecords'])
            ->where('teacher_profile_id', $teacherProfile->id)
            ->whereDate('start_time', $date)
            ->orderBy('start_time')
            ->get();

        $now = now();
        $sessionsData = $sessions->map(function ($session) use ($now) {
            $start = \Carbon\Carbon::parse($session->start_time);
            $end = \Carbon\Carbon::parse($session->end_time);
            $studentCount = $session->group?->studentProfiles()->count() ?? 0;
            $recordCount = $session->attendanceRecords->count();
            $isPast = $now->greaterThan($end);
            $isFuture = $now->lessThan($start);
            // Closed ONLY when the time window has elapsed.
            // While the session is in progress, even with records present, the
            // teacher can still re-open and correct mistakes.
            $isClosed = $isPast;

            return [
                'id' => $session->id,
                'start_time' => $start->format('Y-m-d H:i:s'),
                'end_time' => $end->format('Y-m-d H:i:s'),
                'time' => $start->format('H:i') . ' - ' . $end->format('H:i'),
                'duration' => $end->diffInHours($start),
                'module' => $session->module->name,
                'group' => $session->group->name,
                'student_count' => $studentCount,
                'record_count' => $recordCount,
                'status' => $isClosed ? 'closed' : ($isFuture ? 'upcoming' : 'in_progress'),
                'is_closed' => $isClosed,
                'is_future' => $isFuture,
                'is_in_progress' => ! $isFuture && ! $isClosed,
                'url' => $isClosed
                    ? null
                    : route('teacher.sessions.qr.show', $session->id),
            ];
        })->values();

        return view('teacher.attendance.index', compact('sessions', 'date', 'sessionsData'));
    }

    /**
     * Old show route — kept for backward compatibility.
     * Now redirects to the QR workspace which handles both projection + manual override.
     */
    public function show(Session $session): RedirectResponse
    {
        if (!Auth::user()->hasRole('admin') && $session->teacher_profile_id !== Auth::user()->teacherProfile->id) {
            abort(403, 'Unauthorized access to this session.');
        }

        return redirect()->route('teacher.sessions.qr.show', $session->id);
    }

    /**
     * Store the attendance records for the session.
     */
    public function store(Request $request, Session $session)
    {
        if (!Auth::user()->hasRole('admin') && $session->teacher_profile_id !== Auth::user()->teacherProfile->id) {
            abort(403);
        }

        $request->validate([
            'attendance' => 'required|array',
            'attendance.*' => 'required',
        ]);

        $sessionDate = \Carbon\Carbon::parse($session->start_time)->toDateString();

        // Pre-load all students in the group in one query to avoid N+1 in the notification path.
        $studentMap = StudentProfile::with('user')
            ->whereIn('id', array_keys($request->attendance))
            ->get()
            ->keyBy('id');

        foreach ($request->attendance as $studentId => $payload) {
            if (is_string($payload)) {
                $status = $payload;
                $note = null;
            } elseif (is_array($payload)) {
                $status = $payload['status'] ?? null;
                $note = $payload['note'] ?? null;
            } else {
                continue;
            }

            if (! in_array($status, ['present', 'absent', 'late'], true)) {
                continue;
            }

            $record = $this->attendanceService->markAttendance(
                (int) $studentId,
                $session->id,
                $status,
                $sessionDate,
                'manual',
                $note
            );

            // Fire notifications
            $student = $studentMap->get((int) $studentId);
            if (! $student || ! $student->user) {
                continue;
            }

            if ($status === 'absent') {
                $this->notifications->notifyStudentAbsent(
                    userId: $student->user->id,
                    sessionId: $session->id,
                    moduleName: $session->module->name ?? 'Séance',
                    attendanceRecordId: $record?->id,
                );
                $this->notifications->notifyTeacherAbsenceMarked(
                    userId: Auth::id(),
                    sessionId: $session->id,
                    studentName: $student->user->name,
                    moduleName: $session->module->name ?? 'Séance',
                );
            } elseif ($status === 'present' || $status === 'late') {
                $this->notifications->notifyStudentPresent(
                    userId: $student->user->id,
                    sessionId: $session->id,
                    moduleName: $session->module->name ?? 'Séance',
                );
            }
        }

        // After saving, bounce back to the session selector
        return redirect()
            ->route('teacher.attendance.index', ['date' => $sessionDate])
            ->with('success', "Présences enregistrées pour la séance de {$session->module->name}.");
    }
}
