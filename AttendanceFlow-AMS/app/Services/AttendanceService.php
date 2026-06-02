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
    // Service name is now automatically handled by BaseService

    /**
     * Mark attendance for a single student.
     */
    public function markAttendance(int $studentProfileId, int $sessionId, string $status, string $date): AttendanceRecord
    {
        $this->logInfo("Marking attendance for student {$studentProfileId} in session {$sessionId}: {$status}");
        
        $justification = null;
        if ($status === 'absent' || $status === 'absent_unexcused' || $status === 'absent_excused') {
            $justification = \App\Models\Justification::where([
                'student_profile_id' => $studentProfileId,
                'session_id' => $sessionId,
            ])->where('status', 'approved')->first();

            if ($justification) {
                $status = 'absent_excused';
            } else {
                $status = 'absent_unexcused';
            }
        }

        $record = AttendanceRecord::updateOrCreate(
            ['student_profile_id' => $studentProfileId, 'session_id' => $sessionId],
            [
                'status' => $status, 
                'date' => $date,
                'justification_id' => $justification ? $justification->id : null
            ]
        );

        if (in_array($status, ['absent_unexcused', 'absent_excused', 'late'])) {
            $student = \App\Models\StudentProfile::find($studentProfileId);
            $session = \App\Models\Session::with(['module', 'teacherProfile.user'])->find($sessionId);
            
            if ($student && $student->user_id) {
                $moduleName = $session->module->name ?? 'Séance';
                $statusLabel = $status === 'absent_excused' ? 'absent (justifié)' : ($status === 'absent_unexcused' ? 'absent (non justifié)' : 'en retard');
                \App\Models\Notification::create([
                    'user_id' => $student->user_id,
                    'title' => $status === 'late' ? 'Retard signalé' : 'Nouvelle absence signalée',
                    'message' => 'Vous avez été marqué ' . $statusLabel . ' le ' . \Carbon\Carbon::parse($date)->format('d/m/Y') . ' pour la séance de : ' . $moduleName . '.',
                    'type' => $status === 'absent_unexcused' ? 'danger' : ($status === 'absent_excused' ? 'success' : 'info'),
                ]);
            }

            // Notify teacher
            if ($session && $session->teacherProfile && $session->teacherProfile->user_id) {
                \App\Models\Notification::create([
                    'user_id' => $session->teacherProfile->user_id,
                    'title' => 'Absence Étudiant',
                    'message' => 'L\'étudiant ' . ($student->user->name ?? 'inconnu') . ' a été marqué ' . ($status === 'absent_excused' ? 'absent (justifié)' : 'absent (non justifié)') . ' pour la séance de : ' . ($session->module->name ?? 'Séance') . '.',
                    'type' => 'danger',
                ]);
            }
        }

        return $record;
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

    /**
     * Get all attendance records for a student.
     */
    public function getStudentAttendance(int $studentProfileId): Collection
    {
        return AttendanceRecord::where('student_profile_id', $studentProfileId)->get();
    }
}
