<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\TimetableChangeRequest;
use App\Services\AcademicService;
use App\Services\TimetableService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TimetableController extends Controller
{
    public function __construct(
        private readonly TimetableService $timetable,
        private readonly AcademicService $academic,
    ) {
    }

    /**
     * GET /teacher/timetable
     * Weekly view of the teacher's own sessions + their pending change requests.
     */
    public function index(Request $request): View
    {
        $teacherProfile = $request->user()->teacherProfile;
        abort_if(! $teacherProfile, 403);

        $weekStart = $request->query('week')
            ? Carbon::parse($request->query('week'))->startOfWeek()
            : now()->startOfWeek();

        $grid = $this->timetable->weekGridFor($weekStart->toDateString(), 'teacher', $teacherProfile->id);

        $requests = TimetableChangeRequest::with(['session.module', 'session.group', 'reviewer'])
            ->where('teacher_profile_id', $teacherProfile->id)
            ->latest()
            ->take(10)
            ->get();

        return view('teacher.timetable.index', [
            'grid'        => $grid,
            'weekStart'   => $weekStart,
            'weekEnd'     => (clone $weekStart)->endOfWeek(),
            'requests'    => $requests,
        ]);
    }

    /**
     * GET /teacher/timetable/request
     * Form to submit a new change request.
     */
    public function createRequest(Request $request): View
    {
        $teacherProfile = $request->user()->teacherProfile;
        abort_if(! $teacherProfile, 403);

        $modules = $teacherProfile->modules()->orderBy('name')->get();
        $groups  = $teacherProfile->groups()->orderBy('name')->get();

        // Pre-fill if editing
        $session = null;
        if ($request->filled('session')) {
            $session = Session::with('module', 'group')->find($request->query('session'));
        }

        return view('teacher.timetable.request', [
            'modules'   => $modules,
            'groups'    => $groups,
            'session'   => $session,
        ]);
    }

    /**
     * POST /teacher/timetable/request
     * Submit a change request (create / update / delete).
     */
    public function storeRequest(Request $request): RedirectResponse
    {
        $teacherProfile = $request->user()->teacherProfile;
        abort_if(! $teacherProfile, 403);

        $data = $request->validate([
            'action'           => 'required|in:create,update,delete',
            'session_id'       => 'nullable|exists:academic_sessions,id',
            'module_id'        => 'required|exists:modules,id',
            'group_id'         => 'required|exists:groups,id',
            'type'             => 'required|in:lecture,td,tp,CM,TD,TP',
            'date'             => 'required|date',
            'start_time'       => 'required|date_format:H:i',
            'end_time'         => 'required|date_format:H:i|after:start_time',
            'room'             => 'nullable|string|max:64',
            'reason'           => 'nullable|string|max:1000',
        ]);

        // Verify teacher has access to the module/group combination
        $assigned = DB::table('module_teacher_group')
            ->where('teacher_profile_id', $teacherProfile->id)
            ->where('module_id', $data['module_id'])
            ->where('group_id', $data['group_id'])
            ->exists();
        if (! $assigned) {
            return back()->withErrors(['error' => 'Vous n\'êtes pas assigné(e) à ce module pour ce groupe.'])->withInput();
        }

        // For update/delete, verify session belongs to the teacher
        $session = null;
        if (in_array($data['action'], ['update', 'delete'], true)) {
            $session = Session::where('id', $data['session_id'])
                ->where('teacher_profile_id', $teacherProfile->id)
                ->first();
            if (! $session) {
                return back()->withErrors(['error' => 'Séance introuvable ou non autorisée.'])->withInput();
            }
        }

        $start = Carbon::parse($data['date'].' '.$data['start_time']);
        $end   = Carbon::parse($data['date'].' '.$data['end_time']);
        $duration = round($start->diffInMinutes($end) / 60, 1);

        $proposed = [
            'module_id'         => $data['module_id'],
            'teacher_profile_id'=> $teacherProfile->id,
            'group_id'          => $data['group_id'],
            'type'              => $data['type'],
            'start_time'        => $start,
            'end_time'          => $end,
            'duration_hours'    => $duration,
            'room'              => $data['room'] ?? null,
        ];

        // Soft conflict check (errors only, return 422-style with errors)
        $issues = $this->timetable->detectConflicts($proposed, $data['action'] === 'update' ? $session->id : null);
        $errors = array_values(array_filter($issues, fn ($i) => $i['level'] === 'error'));
        if (! empty($errors)) {
            return back()->withErrors(['conflicts' => collect($errors)->pluck('message')->all()])->withInput();
        }

        $previous = $session ? $session->only([
            'module_id', 'teacher_profile_id', 'group_id', 'type',
            'start_time', 'end_time', 'duration_hours', 'room',
        ]) : null;

        $changeRequest = TimetableChangeRequest::create([
            'session_id'        => $session?->id,
            'teacher_profile_id'=> $teacherProfile->id,
            'requested_by'      => $request->user()->id,
            'action'            => $data['action'],
            'proposed_data'     => $proposed,
            'previous_data'     => $previous,
            'reason'            => $data['reason'] ?? null,
            'status'            => TimetableChangeRequest::STATUS_PENDING,
        ]);

        $this->timetable->submitChangeRequest($changeRequest);

        return redirect()
            ->route('teacher.timetable.index')
            ->with('success', 'Demande envoyée à l\'administration. Vous serez notifié(e) de la décision.');
    }
}
