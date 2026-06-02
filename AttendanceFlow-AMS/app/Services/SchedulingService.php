<?php

namespace App\Services;

use App\Models\Session;
use Illuminate\Support\Collection;
use Carbon\Carbon;

/**
 * SchedulingService
 * 
 * Manages the temporal planning of training activities.
 */
class SchedulingService extends BaseService
{
    // Service name is now automatically handled by BaseService

    /**
     * Schedule a new session, checking for basic teacher conflicts.
     */
    public function scheduleSession(array $sessionData): ?Session
    {
        $this->logInfo("Scheduling new session for module ID: " . ($sessionData['module_id'] ?? 'unknown'));
        
        $startTime = Carbon::parse($sessionData['start_time']);
        $endTime = Carbon::parse($sessionData['end_time']);

        // Check for literal overlap: (start_time < new_end_time) AND (end_time > new_start_time)
        $conflict = Session::where('teacher_profile_id', $sessionData['teacher_profile_id'])
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime)
            ->exists();

        if ($conflict) {
            $this->logError("Scheduling conflict detected for teacher ID: {$sessionData['teacher_profile_id']}");
            throw new \Exception("The teacher is already busy during this time.");
        }

        return Session::create($sessionData);
    }

    /**
     * Get the schedule for a specific group within a date range.
     */
    public function getGroupSchedule(int $groupId, Carbon $start, Carbon $end): Collection
    {
        return Session::with(['module', 'teacherProfile.user'])
            ->where('group_id', $groupId)
            ->whereBetween('start_time', [$start, $end])
            ->orderBy('start_time')
            ->get();
    }

    /**
     * Get sessions for export.
     */
    public function getSessionsForExport(): Collection
    {
        return Session::with([
            'module',
            'group',
            'teacherProfile.user'
        ])->orderBy('start_time', 'asc')->get();
    }

    /**
     * Get all sessions for a specific date.
     */
    public function getSessionsForDate(string $date): Collection
    {
        return Session::with(['module', 'group', 'teacherProfile.user'])
            ->whereDate('start_time', $date)
            ->orderBy('start_time', 'asc')
            ->get();
    }

    /**
     * Update an existing session.
     */
    public function updateSession(Session $session, array $sessionData): bool
    {
        $this->logInfo("Updating session ID {$session->id}");
        
        $startTime = Carbon::parse($sessionData['start_time']);
        $endTime = Carbon::parse($sessionData['end_time']);

        // Check for overlap excluding this session itself
        $conflict = Session::where('teacher_profile_id', $sessionData['teacher_profile_id'])
            ->where('id', '!=', $session->id)
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime)
            ->exists();

        if ($conflict) {
            $this->logError("Scheduling conflict detected during update of session {$session->id} for teacher ID: {$sessionData['teacher_profile_id']}");
            throw new \Exception("The teacher is already busy during this time.");
        }

        $session = Session::create($sessionData);
        $session->load(['module', 'group', 'teacherProfile']);

        // Notify assigned teacher
        if ($session->teacherProfile && $session->teacherProfile->user_id) {
            \App\Models\Notification::create([
                'user_id' => $session->teacherProfile->user_id,
                'title' => 'Nouvelle Session Assignée',
                'message' => "Une nouvelle session {$session->module->name} ({$session->type}) pour le groupe {$session->group->name} vous a été assignée le " . \Carbon\Carbon::parse($session->start_time)->format('d/m/Y à H:i') . '.',
                'type' => 'info',
            ]);
        }

        // Notify students in the assigned group
        if ($session->group) {
            $studentUsers = $session->group->studentProfiles->pluck('user.id')->filter();
            foreach ($studentUsers as $userId) {
                \App\Models\Notification::create([
                    'user_id' => $userId,
                    'title' => 'Nouvelle Session Planifiée',
                    'message' => "Une nouvelle session {$session->module->name} ({$session->type}) est planifiée pour votre groupe {$session->group->name} le " . \Carbon\Carbon::parse($session->start_time)->format('d/m/Y à H:i') . '.',
                    'type' => 'info',
                ]);
            }
        }

        return $session;
    }

    /**
     * Delete a session.
     */
    public function deleteSession(Session $session): bool
    {
        $this->logInfo("Deleting session ID {$session->id}");
        return $session->delete();
    }
}
