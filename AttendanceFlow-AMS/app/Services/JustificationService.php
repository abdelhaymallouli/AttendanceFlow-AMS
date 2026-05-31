<?php

namespace App\Services;

use App\Models\Justification;
use App\Models\AttendanceRecord;

/**
 * JustificationService
 * 
 * Manages the lifecycle of absence justifications.
 */
class JustificationService extends BaseService
{
    // Service name is now automatically handled by BaseService

    /**
     * Submit a new justification.
     */
    public function submitJustification(int $studentProfileId, array $data): Justification
    {
        $this->logInfo("New justification submitted by student {$studentProfileId}");
        
        $data['student_profile_id'] = $studentProfileId;
        $data['status'] = 'pending'; // Default status
        
        return Justification::create($data);
    }

    /**
     * Review and update justification status. Updates attendance records if approved.
     */
    public function reviewJustification(int $justificationId, string $status): Justification
    {
        $justification = Justification::findOrFail($justificationId);
        
        // Map 'accepted' to 'approved' for backwards compatibility
        if ($status === 'accepted') {
            $status = 'approved';
        }

        $justification->status = $status;
        $justification->reviewed_at = now();
        $justification->reviewed_by = auth()->id();
        $justification->save();

        if ($status === 'approved') {
            $this->logInfo("Justification {$justificationId} approved. Updating related attendance records.");
            
            if ($justification->session_id) {
                // Find or update the attendance record for this student and session
                $attendance = AttendanceRecord::where([
                    'student_profile_id' => $justification->student_profile_id,
                    'session_id' => $justification->session_id,
                ])->first();

                if ($attendance) {
                    $attendance->status = 'absent_excused';
                    $attendance->justification_id = $justification->id;
                    $attendance->save();
                }
            } else {
                // Fallback for missing session_id: Auto-update absentees records to excused between the dates
                AttendanceRecord::where('student_profile_id', $justification->student_profile_id)
                    ->whereBetween('date', [$justification->start_date, $justification->end_date])
                    ->whereIn('status', ['absent', 'absent_unexcused'])
                    ->update([
                        'status' => 'absent_excused',
                        'justification_id' => $justification->id
                    ]);
            }
        }

        return $justification;
    }
}
