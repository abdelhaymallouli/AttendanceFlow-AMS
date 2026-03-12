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
    public function getServiceName(): string
    {
        return 'JustificationService';
    }

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
     * Review and update justification status. Updates attendance records if accepted.
     */
    public function reviewJustification(int $justificationId, string $status): Justification
    {
        $justification = Justification::findOrFail($justificationId);
        $justification->status = $status;
        $justification->save();

        if ($status === 'accepted') {
            $this->logInfo("Justification {$justificationId} accepted. Updating related attendance records.");
            
            // Auto-update absentees records to justified between the dates
            AttendanceRecord::where('student_profile_id', $justification->student_profile_id)
                ->whereBetween('date', [$justification->start_date, $justification->end_date])
                ->where('status', 'absent')
                ->update(['status' => 'justified']);
        }

        return $justification;
    }
}
