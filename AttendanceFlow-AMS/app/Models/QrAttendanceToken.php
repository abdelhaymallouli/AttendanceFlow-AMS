<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QrAttendanceToken extends Model
{
    use HasFactory;

    protected $table = 'qr_attendance_tokens';

    protected $fillable = [
        'session_id',
        'token_hash',
        'text_code',
        'nonce',
        'issued_at',
        'expires_at',
        'is_multi_use',
        'is_consumed',
        'consumed_by_student_id',
        'consumed_at',
        'consumed_ip',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'expires_at' => 'datetime',
        'consumed_at' => 'datetime',
        'is_consumed' => 'boolean',
        'is_multi_use' => 'boolean',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class, 'session_id');
    }

    public function studentProfile(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class, 'consumed_by_student_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_consumed', false)
            ->where('expires_at', '>', now());
    }

    public function scopeForSession(Builder $query, int $sessionId): Builder
    {
        return $query->where('session_id', $sessionId);
    }

    public function isValid(): bool
    {
        return ! $this->is_consumed && $this->expires_at->isFuture();
    }

    public function consume(int $studentProfileId, ?string $ip = null): void
    {
        $this->forceFill([
            'is_consumed' => true,
            'consumed_by_student_id' => $studentProfileId,
            'consumed_at' => now(),
            'consumed_ip' => $ip,
        ])->save();
    }
}
