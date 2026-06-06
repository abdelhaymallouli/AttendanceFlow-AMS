<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeviceFingerprint extends Model
{
    use HasFactory;

    protected $table = 'device_fingerprints';

    protected $fillable = [
        'user_id',
        'fingerprint_hash',
        'user_agent',
        'first_seen_at',
        'last_seen_at',
        'trust_score',
        'revoked_at',
    ];

    protected $casts = [
        'first_seen_at' => 'datetime',
        'last_seen_at' => 'datetime',
        'revoked_at' => 'datetime',
        'trust_score' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isRevoked(): bool
    {
        return $this->revoked_at !== null;
    }

    public function revoke(): void
    {
        $this->forceFill(['revoked_at' => now()])->save();
    }

    public function touchSeen(): void
    {
        $this->forceFill(['last_seen_at' => now()])->save();
    }
}
