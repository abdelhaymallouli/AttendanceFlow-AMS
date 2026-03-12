<?php

namespace App\Services;

use App\Models\AttendanceRecord;
use Illuminate\Support\Collection;

/**
 * AttendanceService
 * 
 * Records and analyzes student presence.
 */
class AttendanceService extends BaseService
{
    public function getServiceName(): string
    {
        return 'AttendanceService';
    }

    /**
     * Mark attendance for a single student.
     */
    public function markAttendance(int $studentProfileId, int $sessionId, string $status, string $date): AttendanceRecord
    {
        $this->logInfo("Marking attendance for student {$studentProfileId} in session {$sessionId}: {$status}");
        
        return AttendanceRecord::updateOrCreate(
            ['student_profile_id' => $studentProfileId, 'session_id' => $sessionId],
            ['status' => $status, 'date' => $date]
        );
    }

    /**
     * Bulk mark attendance for an entire session.
     * Expected array format: [['student_profile_id' => 1, 'status' => 'present', 'date' => '2026-03-12'], ...]
     */
    public function bulkMarkAttendance(int $sessionId, array $attendanceData): void
    {
        $this->logInfo("Bulk marking attendance for session {$sessionId}");
        
        foreach ($attendanceData as $data) {
            $this->markAttendance($data['student_profile_id'], $sessionId, $data['status'], $data['date']);
        }
    }

    /**
     * Get all attendance records for a given session.
     */
    public function getSessionAttendance(int $sessionId): Collection
    {
        return AttendanceRecord::with('studentProfile.user')
            ->where('session_id', $sessionId)
            ->get();
    }
}
