<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfflineSyncReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'processed' => $this->resource['processed'] ?? 0,
            'accepted'  => $this->resource['accepted'] ?? 0,
            'rejected'  => $this->resource['rejected'] ?? 0,
            'results'   => $this->resource['results'] ?? [],
            'server_time' => now()->toIso8601String(),
        ];
    }
}
