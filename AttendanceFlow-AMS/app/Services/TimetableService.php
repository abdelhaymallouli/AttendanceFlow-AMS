<?php

namespace App\Services;

use App\Models\Module;
use App\Models\Session;
use App\Models\TimetableChangeRequest;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * TimetableService
 *
 * Single source of truth for timetable operations:
 *  - Detect scheduling conflicts (teacher, group, room)
 *  - Enforce the module's annual hour allocation
 *  - Publish a session (and fan out notifications)
 *  - Approve / reject teacher change requests
 */
class TimetableService extends BaseService
{
    public function __construct(
        private readonly NotificationService $notifications,
    ) {
    }

    /**
     * Validate a session payload and return the list of blocking issues.
     *
     * Each issue has:
     *  - code:   teacher_conflict | group_conflict | room_conflict | module_hours_exceeded
     *  - level:  error | warning
     *  - message: human-readable description
     *
     * @return array<int, array{code: string, level: string, message: string}>
     */
    public function detectConflicts(array $sessionData, ?int $excludeSessionId = null): array
    {
        $start = Carbon::parse($sessionData['start_time']);
        $end   = Carbon::parse($sessionData['end_time']);

        if ($end->lessThanOrEqualTo($start)) {
            return [['code' => 'invalid_range', 'level' => 'error', 'message' => 'La fin doit être après le début.']];
        }

        $issues = [];
        $base = Session::query()
            ->where('start_time', '<', $end)
            ->where('end_time',   '>', $start)
            ->where('is_published', true);

        if ($excludeSessionId) {
            $base->where('id', '!=', $excludeSessionId);
        }

        // Teacher overlap
        if (!empty($sessionData['teacher_profile_id'])) {
            $teacherConflict = (clone $base)
                ->where('teacher_profile_id', $sessionData['teacher_profile_id'])
                ->with('module')
                ->first();
            if ($teacherConflict) {
                $issues[] = [
                    'code'    => 'teacher_conflict',
                    'level'   => 'error',
                    'message' => "L'enseignant est déjà occupé par « {$teacherConflict->module->name} » ({$teacherConflict->start_time->format('H:i')}–{$teacherConflict->end_time->format('H:i')}).",
                ];
            }
        }

        // Group overlap
        if (!empty($sessionData['group_id'])) {
            $groupConflict = (clone $base)
                ->where('group_id', $sessionData['group_id'])
                ->with('module')
                ->first();
            if ($groupConflict) {
                $issues[] = [
                    'code'    => 'group_conflict',
                    'level'   => 'error',
                    'message' => "Le groupe a déjà une séance « {$groupConflict->module->name} » à ce créneau.",
                ];
            }
        }

        // Room overlap (only if a room is specified)
        if (!empty($sessionData['room'])) {
            $roomConflict = (clone $base)
                ->where('room', $sessionData['room'])
                ->with(['module', 'group'])
                ->first();
            if ($roomConflict) {
                $issues[] = [
                    'code'    => 'room_conflict',
                    'level'   => 'warning',
                    'message' => "La salle « {$sessionData['room']} » est déjà occupée par le groupe « {$roomConflict->group->name} ».",
                ];
            }
        }

        // Module hours budget
        $duration = (float) ($sessionData['duration_hours'] ?? round($start->diffInMinutes($end) / 60, 1));
        if (!empty($sessionData['module_id'])) {
            $module = Module::find($sessionData['module_id']);
            if ($module) {
                $used = $this->moduleUsedHours($module->id, $excludeSessionId);
                $total = (float) $module->total_hours;
                if (($used + $duration) > $total + 0.0001) {
                    $issues[] = [
                        'code'    => 'module_hours_exceeded',
                        'level'   => 'error',
                        'message' => "Le module « {$module->name} » dispose de {$total}h, dont {$used}h déjà planifiées. Cette séance ajouterait {$duration}h (dépassement de " . round(($used + $duration) - $total, 1) . 'h).',
                    ];
                } elseif ($used + $duration >= $total - 0.0001) {
                    $issues[] = [
                        'code'    => 'module_hours_fully_used',
                        'level'   => 'warning',
                        'message' => "Cette séance consomme les dernières heures disponibles du module « {$module->name} » ({$used}h + {$duration}h = {$total}h).",
                    ];
                }
            }
        }

        return $issues;
    }

    /**
     * @return array{ok: bool, issues: array, payload: array}
     */
    public function validate(array $sessionData, ?int $excludeSessionId = null): array
    {
        $issues = $this->detectConflicts($sessionData, $excludeSessionId);
        $errors = array_values(array_filter($issues, fn ($i) => $i['level'] === 'error'));

        return [
            'ok'      => empty($errors),
            'issues'  => $issues,
            'payload' => $sessionData,
        ];
    }

    /**
     * Sum of durations of all published sessions for a module.
     * Optionally excludes one session (for edits).
     */
    public function moduleUsedHours(int $moduleId, ?int $excludeSessionId = null): float
    {
        $q = Session::where('module_id', $moduleId)
            ->where('is_published', true);
        if ($excludeSessionId) {
            $q->where('id', '!=', $excludeSessionId);
        }
        return (float) $q->sum('duration_hours');
    }

    /**
     * Create a published session and fan out notifications.
     */
    public function publishSession(array $data): Session
    {
        $data['is_published'] = $data['is_published'] ?? true;
        $session = Session::create($data);
        $session->load(['module', 'group', 'teacherProfile.user']);

        $this->notifyPublish($session, 'created');

        return $session;
    }

    /**
     * Update a session and notify when relevant fields changed.
     */
    public function updateSession(Session $session, array $data): Session
    {
        $previous = $session->only(['start_time', 'end_time', 'room', 'module_id', 'group_id', 'teacher_profile_id', 'is_published', 'type']);

        $session->fill($data);
        $session->save();
        $session->load(['module', 'group', 'teacherProfile.user']);

        $this->notifyPublish($session, 'updated', $previous);

        return $session;
    }

    /**
     * Soft-delete from the students' perspective, then physically delete.
     * We notify the group before deletion.
     */
    public function deleteSession(Session $session): bool
    {
        $session->load(['module', 'group', 'teacherProfile.user']);
        $ok = $session->delete();
        if ($ok) {
            $this->notifyPublish($session, 'deleted');
        }
        return $ok;
    }

    /**
     * Notify the teacher + every student of the group's users.
     */
    public function notifyPublish(Session $session, string $action, ?array $previous = null): void
    {
        $module = $session->module?->name ?? '—';
        $group  = $session->group?->name  ?? '—';
        $when   = $session->start_time?->format('d/m/Y H:i') ?? '';
        $room   = $session->room ? " · Salle {$session->room}" : '';

        $verbed = match ($action) {
            'created' => 'a été planifiée',
            'updated' => 'a été modifiée',
            'deleted' => 'a été annulée',
            default   => 'a été mise à jour',
        };

        $title = match ($action) {
            'created' => 'Nouvelle séance',
            'updated' => 'Séance modifiée',
            'deleted' => 'Séance annulée',
            default   => 'Séance mise à jour',
        };

        $redirectDate = $session->start_time?->toDateString() ?? now()->toDateString();

        // Teacher
        if ($session->teacherProfile?->user_id) {
            $this->notifications->create(
                userId: $session->teacherProfile->user_id,
                title: "{$title} — {$module}",
                message: "La séance « {$module} » ({$session->type}) du groupe {$group} {$verbed} pour le {$when}{$room}.",
                category: 'timetable.publish',
                type: $action === 'deleted' ? 'warning' : 'info',
                audience: 'teacher',
                data: [
                    'session_id' => $session->id,
                    'module' => $module,
                    'url' => route('teacher.timetable.index', ['date' => $redirectDate], false),
                ],
            );
        }

        // Students in the group
        if ($session->group) {
            $studentUserIds = $session->group->studentProfiles()->pluck('user_id')->filter()->all();
            foreach ($studentUserIds as $userId) {
                $this->notifications->create(
                    userId: (int) $userId,
                    title: "{$title} — {$module}",
                    message: "{$module} ({$session->type}) · {$when}{$room} · {$verbed}.",
                    category: 'timetable.publish',
                    type: $action === 'deleted' ? 'warning' : 'info',
                    audience: 'student',
                    data: [
                        'session_id' => $session->id,
                        'module' => $module,
                        'url' => route('student.timetable.index', ['date' => $redirectDate], false),
                    ],
                );
            }
        }
    }

    /**
     * Teacher submits a change request; we notify all admins.
     */
    public function submitChangeRequest(TimetableChangeRequest $request): void
    {
        $admins = \App\Models\User::role('admin')->get();
        $action = $request->action;

        $verb = match ($action) {
            TimetableChangeRequest::ACTION_CREATE => 'création',
            TimetableChangeRequest::ACTION_UPDATE => 'modification',
            TimetableChangeRequest::ACTION_DELETE => 'suppression',
            default => $action,
        };

        $module = $request->session_id
            ? Session::with('module')->find($request->session_id)?->module?->name
            : (Module::find($request->proposed_data['module_id'] ?? null)?->name ?? '—');

        foreach ($admins as $admin) {
            $this->notifications->create(
                userId: $admin->id,
                title: "Demande de {$verb} de séance",
                message: "L'enseignant {$request->teacherProfile->user->name} demande la {$verb} d'une séance de « {$module} ».",
                category: 'timetable.change_request',
                type: 'info',
                audience: 'admin',
                data: [
                    'request_id' => $request->id,
                    'module' => $module,
                    'url' => route('admin.timetable.requests', [], false),
                ],
            );
        }
    }

    /**
     * Approve a pending request and apply it to the timetable.
     */
    public function approveRequest(TimetableChangeRequest $request, int $reviewerId, ?string $note = null): Session|bool
    {
        if ($request->status !== TimetableChangeRequest::STATUS_PENDING) {
            throw new \Exception('Cette demande a déjà été traitée.');
        }

        $result = match ($request->action) {
            TimetableChangeRequest::ACTION_CREATE => $this->publishSession($request->proposed_data),
            TimetableChangeRequest::ACTION_UPDATE => $this->updateSession($request->session, $request->proposed_data),
            TimetableChangeRequest::ACTION_DELETE => $this->deleteSession($request->session),
            default => throw new \Exception("Action inconnue: {$request->action}"),
        };

        $request->update([
            'status'      => TimetableChangeRequest::STATUS_APPROVED,
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
            'admin_note'  => $note,
        ]);

        $this->notifications->create(
            userId: $request->requested_by,
            title: 'Demande approuvée',
            message: "Votre demande a été approuvée." . ($note ? " Motif : {$note}" : ''),
            category: 'timetable.change_approved',
            type: 'success',
            audience: 'teacher',
            data: [
                'request_id' => $request->id,
                'url' => route('teacher.timetable.index', [], false),
            ],
        );

        return $result;
    }

    /**
     * Reject a pending request.
     */
    public function rejectRequest(TimetableChangeRequest $request, int $reviewerId, ?string $note = null): void
    {
        if ($request->status !== TimetableChangeRequest::STATUS_PENDING) {
            throw new \Exception('Cette demande a déjà été traitée.');
        }

        $request->update([
            'status'      => TimetableChangeRequest::STATUS_REJECTED,
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
            'admin_note'  => $note,
        ]);

        $this->notifications->create(
            userId: $request->requested_by,
            title: 'Demande rejetée',
            message: "Votre demande a été rejetée." . ($note ? " Motif : {$note}" : ''),
            category: 'timetable.change_rejected',
            type: 'danger',
            audience: 'teacher',
            data: [
                'request_id' => $request->id,
                'url' => route('teacher.timetable.index', [], false),
            ],
        );
    }

    /**
     * Build a 7-day grid for a group / teacher, useful for the timetable UI.
     *
     * @return array<string, Collection>
     */
    public function weekGridFor(string $weekStartIso, string $scope, int $id): array
    {
        $start = Carbon::parse($weekStartIso)->startOfWeek();
        $end   = (clone $start)->endOfWeek();

        $q = Session::with(['module', 'group', 'teacherProfile.user'])
            ->whereBetween('start_time', [$start, $end])
            ->orderBy('start_time');

        if ($scope === 'group') {
            $q->where('group_id', $id);
        } elseif ($scope === 'teacher') {
            $q->where('teacher_profile_id', $id);
        } elseif ($scope === 'student') {
            $groupIds = \App\Models\StudentProfile::where('user_id', $id)->pluck('group_id');
            $q->whereIn('group_id', $groupIds);
        }
        // scope === 'school' → no filter

        $sessions = $q->get();

        $grid = [];
        for ($i = 0; $i < 7; $i++) {
            $day = (clone $start)->addDays($i);
            $grid[$day->toDateString()] = $sessions->filter(
                fn ($s) => $s->start_time->isSameDay($day)
            )->values();
        }
        return $grid;
    }
}
