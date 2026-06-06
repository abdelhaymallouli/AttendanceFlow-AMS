<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\Session;
use App\Services\AttendanceService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function __construct(
        private readonly AttendanceService $attendance,
    ) {
    }

    /**
     * "Registre des présences" — index page.
     *
     * For a given date, lists every session of every group, with stats
     * (present, absent, late, unmarked) and quick links to:
     *   - the per-session editor (table view, inline edit + save)
     *   - the per-session consult view (read-only)
     *   - the live QR workspace
     */
    public function index(Request $request)
    {
        $date = $request->input('date');

        if (!$date || $date === 'undefined' || !strtotime($date)) {
            $date = Carbon::today()->toDateString();

            return redirect()->route('admin.attendance.index', ['date' => $date]);
        }

        $start = Carbon::parse($date)->startOfDay();
        $end   = Carbon::parse($date)->endOfDay();

        $sessions = Session::with(['module', 'group', 'teacherProfile.user'])
            ->whereBetween('start_time', [$start, $end])
            ->orderBy('start_time')
            ->get();

        // Load every attendance record for those sessions in one query.
        $sessionIds = $sessions->pluck('id');
        $records = AttendanceRecord::whereIn('session_id', $sessionIds)->get();

        $allSessionsData = $sessions->map(function (Session $session) use ($records) {
            $start = Carbon::parse($session->start_time);
            $end   = Carbon::parse($session->end_time);

            $sessionRecords = $records->where('session_id', $session->id);
            $present = $sessionRecords->where('status', 'present')->count();
            $late    = $sessionRecords->where('status', 'late')->count();
            $absent  = $sessionRecords->where('status', 'absent')->count();
            $marked  = $sessionRecords->count();

            $totalStudents = $session->group
                ? $session->group->studentProfiles()->count()
                : 0;
            $unmarked = max($totalStudents - $marked, 0);
            $fillRate = $totalStudents > 0
                ? round((($present + $late) / $totalStudents) * 100)
                : 0;

            return [
                'id'             => $session->id,
                'start_time'     => $start->format('Y-m-d H:i:s'),
                'time'           => $start->format('H:i') . ' - ' . $end->format('H:i'),
                'duration'       => $end->diffInHours($start),
                'module'         => $session->module->name ?? '',
                'group'          => $session->group->name ?? '',
                'group_filiere'  => $session->group?->filiere?->name,
                'teacher'        => $session->teacherProfile?->user?->name ?? '',
                'type'           => $session->type,
                'is_active'      => now()->between($start, $end),
                'is_past'        => now()->greaterThan($end),
                'present'        => $present,
                'absent'         => $absent,
                'late'           => $late,
                'unmarked'       => $unmarked,
                'total_students' => $totalStudents,
                'fill_rate'      => $fillRate,
                'edit_url'       => route('admin.attendance.show', ['session' => $session->id]),
                'consult_url'    => route('admin.attendance.show', ['session' => $session->id, 'mode' => 'consult']),
                'qr_url'         => route('admin.sessions.qr.show', ['session' => $session->id]),
            ];
        })->values();

        $totals = [
            'sessions' => $sessions->count(),
            'present'  => $allSessionsData->sum('present'),
            'absent'   => $allSessionsData->sum('absent'),
            'late'     => $allSessionsData->sum('late'),
        ];

        return view('admin.attendance.index', [
            'sessions'        => $sessions,
            'date'            => $date,
            'allSessionsData' => $allSessionsData,
            'totals'          => $totals,
        ]);
    }

    /**
     * Per-session editor / consult view.
     *
     * ?mode=consult → read-only view (no status buttons, no save bar)
     * default       → full editor (inline status change + bulk actions + save)
     */
    public function show(Request $request, Session $session)
    {
        $mode = $request->query('mode', 'edit');
        if (!in_array($mode, ['edit', 'consult'], true)) {
            $mode = 'edit';
        }

        $session->load(['module', 'group', 'teacherProfile.user']);

        $students = $session->group
            ? $session->group->studentProfiles()->with('user')->orderBy('matricule')->get()
            : collect();

        $existingRecords = AttendanceRecord::where('session_id', $session->id)
            ->get()
            ->keyBy('student_profile_id');

        $stats = [
            'present'  => $existingRecords->where('status', 'present')->count(),
            'absent'   => $existingRecords->where('status', 'absent')->count(),
            'late'     => $existingRecords->where('status', 'late')->count(),
            'unmarked' => max($students->count() - $existingRecords->count(), 0),
        ];

        return view('admin.attendance.show', [
            'session'          => $session,
            'students'         => $students,
            'existingRecords'  => $existingRecords,
            'stats'            => $stats,
            'mode'             => $mode,
            'isConsult'        => $mode === 'consult',
        ]);
    }

    /**
     * Save inline attendance edits (form submit from the show view).
     */
    public function store(Request $request, Session $session): RedirectResponse
    {
        $request->validate([
            'attendance'          => 'nullable|array',
            'attendance.*'        => 'in:present,absent,late',
            'attendance.*.status' => 'in:present,absent,late',
            'attendance.*.note'   => 'nullable|string|max:500',
        ]);

        $raw = $request->input('attendance', []);

        $attendanceData = [];
        $notesData = [];
        foreach ($raw as $studentId => $payload) {
            if (is_array($payload)) {
                $status = $payload['status'] ?? null;
                $note   = $payload['note']   ?? null;
            } else {
                $status = $payload;
                $note   = null;
            }
            if ($status) {
                $attendanceData[(int) $studentId] = $status;
                if ($note !== null && $note !== '') {
                    $notesData[(int) $studentId] = $note;
                }
            }
        }

        $submittedStudentIds = array_keys($attendanceData);
        $sessionDate = Carbon::parse($session->start_time)->toDateString();

        // Wipe records for students no longer in the submitted set
        AttendanceRecord::where('session_id', $session->id)
            ->whereNotIn('student_profile_id', $submittedStudentIds)
            ->delete();

        foreach ($attendanceData as $studentId => $status) {
            $this->attendance->markAttendance(
                (int) $studentId,
                $session->id,
                $status,
                $sessionDate,
                $notesData[$studentId] ?? null,
            );
        }

        return redirect()
            ->route('admin.attendance.show', ['session' => $session->id, 'mode' => 'consult'])
            ->with('success', "Présences mises à jour ({$session->module->name} — {$session->group->name}).");
    }

    /**
     * AJAX endpoint for inline (per-row) save from the table.
     * Returns the updated stats for the session.
     */
    public function updateRow(Request $request, Session $session): JsonResponse
    {
        $payload = $request->validate([
            'student_id' => 'required|integer|exists:student_profiles,id',
            'status'     => 'required|in:present,absent,late',
            'note'       => 'nullable|string|max:500',
        ]);

        $sessionDate = Carbon::parse($session->start_time)->toDateString();

        $this->attendance->markAttendance(
            (int) $payload['student_id'],
            $session->id,
            $payload['status'],
            $sessionDate,
            $payload['note'] ?? null,
        );

        $records = AttendanceRecord::where('session_id', $session->id)->get();
        $total = $session->group
            ? $session->group->studentProfiles()->count()
            : 0;

        return response()->json([
            'success' => true,
            'stats'   => [
                'present'  => $records->where('status', 'present')->count(),
                'absent'   => $records->where('status', 'absent')->count(),
                'late'     => $records->where('status', 'late')->count(),
                'unmarked' => max($total - $records->count(), 0),
            ],
        ]);
    }
}
