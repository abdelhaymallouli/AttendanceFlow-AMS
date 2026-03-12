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
    public function getServiceName(): string
    {
        return 'SchedulingService';
    }

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
}
