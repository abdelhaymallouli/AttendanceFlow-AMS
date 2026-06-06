<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Module;
use App\Models\Session;
use App\Models\TeacherProfile;
use App\Models\TimetableChangeRequest;
use App\Services\AcademicService;
use App\Services\TimetableService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TimetableController extends Controller
{
    public function __construct(
        private readonly TimetableService $timetable,
        private readonly AcademicService $academic,
    ) {
    }

    /**
     * GET /admin/timetable
     * Weekly grid view of all published sessions of the school.
     */
    public function index(Request $request): View
    {
        $weekStart = $request->query('week')
            ? Carbon::parse($request->query('week'))->startOfWeek()
            : now()->startOfWeek();

        $grid = $this->timetable->weekGridFor($weekStart->toDateString(), 'school', 0);

        $pendingRequests = TimetableChangeRequest::with([
            'teacherProfile.user', 'session.module', 'session.group', 'requester',
        ])->pending()->latest()->take(10)->get();

        $totalRequests = TimetableChangeRequest::pending()->count();

        return view('admin.timetable.index', [
            'grid'           => $grid,
            'weekStart'      => $weekStart,
            'weekEnd'        => (clone $weekStart)->endOfWeek(),
            'pendingRequests'=> $pendingRequests,
            'totalRequests'  => $totalRequests,
        ]);
    }

    /**
     * GET /admin/timetable/requests
     * List of all change requests (pending, approved, rejected).
     */
    public function requests(Request $request): View
    {
        $status = $request->query('status', 'pending');
        $requests = TimetableChangeRequest::with([
            'teacherProfile.user', 'session.module', 'session.group', 'requester', 'reviewer',
        ])
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.timetable.requests', [
            'requests' => $requests,
            'status'   => $status,
        ]);
    }

    /**
     * GET /admin/timetable/create
     * Form to create a new session (admin has full access).
     */
    public function create(Request $request): View
    {
        $modules   = $this->academic->getModules();
        $groups    = $this->academic->getGroups();
        $teachers  = $this->academic->getAllTeacherProfiles()->load('user');
        $defaultDate = $request->query('date', now()->toDateString());

        return view('admin.timetable.create', [
            'modules'     => $modules,
            'groups'      => $groups,
            'teachers'    => $teachers,
            'defaultDate' => $defaultDate,
        ]);
    }

    /**
     * POST /admin/timetable
     * Admin creates a session directly (no approval needed).
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatePayload($request);

        $start = Carbon::parse($data['date'].' '.$data['start_time']);
        $end   = Carbon::parse($data['date'].' '.$data['end_time']);
        $duration = round($start->diffInMinutes($end) / 60, 1);

        $payload = [
            'module_id'         => $data['module_id'],
            'teacher_profile_id'=> $data['teacher_profile_id'],
            'group_id'          => $data['group_id'],
            'type'              => $data['type'],
            'start_time'        => $start,
            'end_time'          => $end,
            'duration_hours'    => $duration,
            'room'              => $data['room'] ?? null,
            'is_published'      => $request->boolean('publish', true),
        ];

        $check = $this->timetable->validate($payload);

        if (! $check['ok']) {
            $errors = collect($check['issues'])
                ->where('level', 'error')
                ->pluck('message')
                ->all();
            return back()->withErrors(['conflicts' => $errors])->withInput();
        }

        $this->timetable->publishSession($payload);

        return redirect()
            ->route('admin.timetable.index', ['week' => $start->startOfWeek()->toDateString()])
            ->with('success', 'Séance planifiée et publiée.');
    }

    /**
     * GET /admin/timetable/{session}/edit
     */
    public function edit(Session $session): View
    {
        $modules   = $this->academic->getModules();
        $groups    = $this->academic->getGroups();
        $teachers  = $this->academic->getAllTeacherProfiles()->load('user');

        return view('admin.timetable.edit', [
            'session'  => $session,
            'modules'  => $modules,
            'groups'   => $groups,
            'teachers' => $teachers,
        ]);
    }

    /**
     * PUT /admin/timetable/{session}
     */
    public function update(Request $request, Session $session): RedirectResponse
    {
        $data = $this->validatePayload($request);

        $start = Carbon::parse($data['date'].' '.$data['start_time']);
        $end   = Carbon::parse($data['date'].' '.$data['end_time']);
        $duration = round($start->diffInMinutes($end) / 60, 1);

        $payload = [
            'module_id'         => $data['module_id'],
            'teacher_profile_id'=> $data['teacher_profile_id'],
            'group_id'          => $data['group_id'],
            'type'              => $data['type'],
            'start_time'        => $start,
            'end_time'          => $end,
            'duration_hours'    => $duration,
            'room'              => $data['room'] ?? null,
            'is_published'      => $request->boolean('publish', $session->is_published),
        ];

        $check = $this->timetable->validate($payload, $session->id);
        if (! $check['ok']) {
            $errors = collect($check['issues'])->where('level', 'error')->pluck('message')->all();
            return back()->withErrors(['conflicts' => $errors])->withInput();
        }

        $this->timetable->updateSession($session, $payload);

        return redirect()
            ->route('admin.timetable.index', ['week' => $start->startOfWeek()->toDateString()])
            ->with('success', 'Séance mise à jour.');
    }

    /**
     * DELETE /admin/timetable/{session}
     */
    public function destroy(Session $session): RedirectResponse
    {
        $week = $session->start_time->startOfWeek()->toDateString();
        $this->timetable->deleteSession($session);
        return redirect()
            ->route('admin.timetable.index', ['week' => $week])
            ->with('success', 'Séance supprimée.');
    }

    /**
     * POST /admin/timetable/requests/{request}/approve
     */
    public function approveRequest(Request $request, TimetableChangeRequest $changeRequest): RedirectResponse
    {
        $request->validate(['admin_note' => 'nullable|string|max:1000']);
        try {
            $this->timetable->approveRequest($changeRequest, $request->user()->id, $request->input('admin_note'));
            return back()->with('success', 'Demande approuvée et appliquée.');
        } catch (\Throwable $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * POST /admin/timetable/requests/{request}/reject
     */
    public function rejectRequest(Request $request, TimetableChangeRequest $changeRequest): RedirectResponse
    {
        $request->validate([
            'admin_note' => 'required|string|max:1000',
        ]);
        try {
            $this->timetable->rejectRequest($changeRequest, $request->user()->id, $request->input('admin_note'));
            return back()->with('success', 'Demande rejetée.');
        } catch (\Throwable $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * GET /admin/timetable/validate
     * AJAX endpoint: returns conflict info for a tentative session.
     */
    public function validateSession(Request $request): JsonResponse
    {
        $data = $this->validatePayload($request);
        $start = Carbon::parse($data['date'].' '.$data['start_time']);
        $end   = Carbon::parse($data['date'].' '.$data['end_time']);
        $duration = round($start->diffInMinutes($end) / 60, 1);

        $payload = [
            'module_id'         => $data['module_id'],
            'teacher_profile_id'=> $data['teacher_profile_id'],
            'group_id'          => $data['group_id'],
            'type'              => $data['type'],
            'start_time'        => $start,
            'end_time'          => $end,
            'duration_hours'    => $duration,
            'room'              => $data['room'] ?? null,
        ];

        $issues = $this->timetable->detectConflicts($payload, $request->query('exclude'));

        return response()->json([
            'ok'     => empty(array_filter($issues, fn ($i) => $i['level'] === 'error')),
            'issues' => $issues,
        ]);
    }

    private function validatePayload(Request $request): array
    {
        return $request->validate([
            'module_id'          => 'required|exists:modules,id',
            'teacher_profile_id' => 'required|exists:teacher_profiles,id',
            'group_id'           => 'required|exists:groups,id',
            'type'               => 'required|in:lecture,td,tp,CM,TD,TP',
            'date'               => 'required|date',
            'start_time'         => 'required|date_format:H:i',
            'end_time'           => 'required|date_format:H:i|after:start_time',
            'room'               => 'nullable|string|max:64',
        ]);
    }
}
