<?php

namespace App\Services\Qr;

use App\Models\DeviceFingerprint;
use App\Models\User;
use App\Services\BaseService;

/**
 * DeviceFingerprintService
 * =========================
 * Generates and verifies device fingerprints (SHA-256 hash of
 * user-agent + screen + timezone + canvas hash sent by the client).
 */
class DeviceFingerprintService extends BaseService
{
    /**
     * Generate a stable fingerprint hash from the client-provided signal.
     *
     * @param  array<string, mixed>  $components  e.g. ['ua', 'screen', 'tz', 'canvas']
     */
    public function generate(array $components): string
    {
        $ua = (string) ($components['ua'] ?? '');
        $screen = (string) ($components['screen'] ?? '');
        $tz = (string) ($components['tz'] ?? '');
        $canvas = (string) ($components['canvas'] ?? '');

        $payload = implode('|', [$ua, $screen, $tz, $canvas]);
        return hash('sha256', $payload);
    }

    /**
     * Register a device fingerprint for the given user. Idempotent.
     * If user already exceeds the cap, oldest non-revoked is revoked.
     */
    public function register(User $user, string $fingerprintHash, ?string $userAgent = null): DeviceFingerprint
    {
        $existing = DeviceFingerprint::query()
            ->where('user_id', $user->id)
            ->where('fingerprint_hash', $fingerprintHash)
            ->first();

        if ($existing) {
            if ($existing->isRevoked()) {
                $existing->revoked_at = null;
            }
            $existing->last_seen_at = now();
            $existing->user_agent = $userAgent ?? $existing->user_agent;
            $existing->save();
            return $existing;
        }

        $maxDevices = (int) config('qr_attendance.device.max_fingerprints_per_user', 3);
        $count = DeviceFingerprint::query()
            ->where('user_id', $user->id)
            ->whereNull('revoked_at')
            ->count();

        if ($count >= $maxDevices) {
            $oldest = DeviceFingerprint::query()
                ->where('user_id', $user->id)
                ->whereNull('revoked_at')
                ->orderBy('last_seen_at')
                ->first();
            $oldest?->revoke();
        }

        return DeviceFingerprint::create([
            'user_id' => $user->id,
            'fingerprint_hash' => $fingerprintHash,
            'user_agent' => $userAgent,
            'first_seen_at' => now(),
            'last_seen_at' => now(),
            'trust_score' => config('qr_attendance.device.trust_new_device', false) ? 100 : 50,
        ]);
    }

    /**
     * Is this fingerprint already known (and not revoked) for this user?
     */
    public function isKnown(User $user, string $fingerprintHash): bool
    {
        return DeviceFingerprint::query()
            ->where('user_id', $user->id)
            ->where('fingerprint_hash', $fingerprintHash)
            ->whereNull('revoked_at')
            ->exists();
    }

    /**
     * Touch the last_seen_at on a fingerprint (no-op if not found).
     */
    public function touch(User $user, string $fingerprintHash): void
    {
        DeviceFingerprint::query()
            ->where('user_id', $user->id)
            ->where('fingerprint_hash', $fingerprintHash)
            ->update(['last_seen_at' => now()]);
    }
}
