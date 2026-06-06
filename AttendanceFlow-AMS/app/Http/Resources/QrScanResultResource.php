<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QrScanResultResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $decision = $this->resource['decision'] ?? [];
        $record   = $this->resource['record'] ?? null;

        return [
            'status' => $decision['status'] ?? 'rejected',
            'rejection_reason' => $decision['rejection_reason'] ?? null,
            'score' => $decision['score'] ?? 0,
            'max_score' => $decision['max_score'] ?? 0,
            'signals' => $decision['signals'] ?? [],
            'attendance' => $record ? [
                'id' => $record->id,
                'session_id' => $record->session_id,
                'status' => $record->status,
                'date' => $record->date?->toDateString(),
                'check_in_method' => $record->check_in_method,
            ] : null,
            'server_time' => now()->toIso8601String(),
        ];
    }
}
