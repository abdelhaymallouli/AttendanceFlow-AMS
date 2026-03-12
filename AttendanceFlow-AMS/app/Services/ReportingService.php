<?php

namespace App\Services;

use App\Models\AttendanceRecord;
use App\Models\Group;

/**
 * ReportingService
 * 
 * Generates analytical insights.
 */
class ReportingService extends BaseService
{
    public function getServiceName(): string
    {
        return 'ReportingService';
    }

    /**
     * Generate basic attendance statistics for a specific group.
     */
    public function generateGroupReport(int $groupId, string $startDate, string $endDate): array
    {
        $this->logInfo("Generating attendance report for group {$groupId}");
        
        $records = AttendanceRecord::whereHas('studentProfile', function ($query) use ($groupId) {
                $query->where('group_id', $groupId);
            })
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $total = $records->count();
        $present = $records->where('status', 'present')->count();
        $absent = $records->whereIn('status', ['absent', 'justified'])->count(); // justified is still an absence
        
        $rate = $total > 0 ? round(($present / $total) * 100, 2) : 0;

        return [
            'total_records' => $total,
            'present' => $present,
            'absent_total' => $absent,
            'attendance_rate' => $rate . '%',
        ];
    }
}
