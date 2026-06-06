<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\Session;
use App\Services\Qr\QrTokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Teacher-facing workspace for a single session:
 *  - QR projection mode (rotating HMAC-signed token)
 *  - Manual override sheet (per-student status + note)
 *  - Live scan feed (polled JSON endpoint)
 *  - Reinitialize (wipe all attendance for the session)
 */
class QrSessionController extends Controller
{
    public function __construct(
        private readonly QrTokenService $qrTokens,
    ) {
    }

    /**
     * GET /teacher/sessions/{session}/qr
     *
     * Three landing modes driven by `?start=1` query param:
     *  - default : landing card with "Start Session" button (no token issued)
     *  - start=1 : QR projection + telemetry (token issued)
     */
    public function show(Request $request, int $sessionId): View
    {
        $user = $request->user();
        $session = Session::with(['module', 'group', 'teacherProfile.user'])->findOrFail($sessionId);

        $isAdmin = $user->hasRole('admin');
        $isOwner = $user->hasRole('teacher')
            && $session->teacherProfile
            && (int) $session->teacherProfile->user_id === (int) $user->id;

        if (! $isAdmin && ! $isOwner) {
            abort(403);
        }

        $students = $session->group
            ? $session->group->studentProfiles()->with('user')->orderBy('matricule')->get()
            : collect();

        $recordsByStudent = AttendanceRecord::where('session_id', $session->id)
            ->get()
            ->keyBy('student_profile_id');

        $presentCount = $recordsByStudent->whereIn('status', ['present', 'late'])->count();
        $isPast = now()->greaterThan($session->end_time);
        // Closed ONLY when the time window has elapsed.
        // During the session window, even with records present, the teacher
        // is allowed to come back and fix mistakes.
        $isClosed = $isPast;

        // Only issue a token if the user clicked "Start" (or already started).
        $started = $request->boolean('start') || $recordsByStudent->isNotEmpty();
        $token = $started ? $this->qrTokens->generate($session) : null;

        $studentsData = $students->map(function ($s) use ($recordsByStudent) {
            $record = $recordsByStudent->get($s->id);
            return [
                'id' => $s->id,
                'name' => $s->user->name,
                'email' => $s->user->email,
                'matricule' => $s->matricule,
                'current_status' => $record?->status,
                'note' => $record?->note,
                'check_in_method' => $record?->check_in_method,
                'recorded_at' => $record?->updated_at?->toIso8601String(),
            ];
        })->values();

        $liveFeed = $recordsByStudent
            ->sortByDesc('updated_at')
            ->take(20)
            ->map(function ($r) use ($students) {
                $student = $students->firstWhere('id', $r->student_profile_id);
                return [
                    'id' => $r->id,
                    'student_id' => $r->student_profile_id,
                    'nom' => $student?->user->name ?? 'Étudiant',
                    'status' => $r->status,
                    'check_in_method' => $r->check_in_method,
                    'pointe_a' => $r->updated_at?->format('H:i'),
                ];
            })
            ->values();

        return view('teacher.qr.show', [
            'session' => $session,
            'token' => $token,
            'students' => $students,
            'studentsData' => $studentsData,
            'liveFeed' => $liveFeed,
            'presentCount' => $presentCount,
            'totalCount' => $students->count(),
            'started' => $started,
            'closed' => $isClosed,
            'isPast' => $isPast,
            'isAdmin' => $isAdmin,
        ]);
    }

    /**
     * GET /api/teacher/sessions/{session}/live-scans
     * Polled by the registry sheet to refresh the live feed + count.
     */
    public function liveScans(Request $request, int $sessionId): JsonResponse
    {
        $user = $request->user();
        $session = Session::with(['teacherProfile', 'group'])->findOrFail($sessionId);

        $isAdmin = $user->hasRole('admin');
        $isOwner = $user->hasRole('teacher')
            && $session->teacherProfile
            && (int) $session->teacherProfile->user_id === (int) $user->id;

        if (! $isAdmin && ! $isOwner) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $students = $session->group
            ? $session->group->studentProfiles()->with('user')->orderBy('matricule')->get()
            : collect();

        $records = AttendanceRecord::where('session_id', $session->id)
            ->get()
            ->keyBy('student_profile_id');

        $presentCount = $records->whereIn('status', ['present', 'late'])->count();
        $closed = $records->isNotEmpty() && $records->count() >= $students->count();

        $feed = $records->sortByDesc('updated_at')->take(20)->map(function ($r) use ($students) {
            $student = $students->firstWhere('id', $r->student_profile_id);
            return [
                'id' => $r->id,
                'student_id' => $r->student_profile_id,
                'nom' => $student?->user->name ?? 'Étudiant',
                'status' => $r->status,
                'check_in_method' => $r->check_in_method,
                'pointe_a' => $r->updated_at?->format('H:i'),
            ];
        })->values();

        return response()->json([
            'present_count' => $presentCount,
            'total_count' => $students->count(),
            'closed' => $closed,
            'feed' => $feed,
            'server_time' => now()->toIso8601String(),
        ]);
    }

    /**
     * POST /teacher/sessions/{session}/qr/reinitialize
     * Wipes all attendance records for the session.
     */
    public function reinitialize(Request $request, int $sessionId): RedirectResponse
    {
        $user = $request->user();
        $session = Session::with('teacherProfile')->findOrFail($sessionId);

        $isAdmin = $user->hasRole('admin');
        $isOwner = $user->hasRole('teacher')
            && $session->teacherProfile
            && (int) $session->teacherProfile->user_id === (int) $user->id;

        if (! $isAdmin && ! $isOwner) {
            abort(403);
        }

        $deleted = AttendanceRecord::where('session_id', $session->id)->delete();

        $redirectRoute = $isAdmin
            ? 'admin.sessions.qr.show'
            : 'teacher.sessions.qr.show';

        return redirect()
            ->route($redirectRoute, $session->id)
            ->with('success', "Séance réinitialisée : {$deleted} présence(s) supprimée(s).");
    }
}
