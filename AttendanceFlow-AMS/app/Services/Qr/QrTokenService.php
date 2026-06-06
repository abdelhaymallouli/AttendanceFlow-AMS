<?php

namespace App\Services\Qr;

use App\Models\QrAttendanceToken;
use App\Models\Session;
use App\Services\BaseService;
use Illuminate\Support\Str;

/**
 * QrTokenService
 * ==============
 * Generates and verifies HMAC-SHA256 signed QR tokens for attendance sessions.
 *
 * Token wire format (compact, URL-safe):
 *   <payload_b64>.<signature_b64>
 *
 * Payload (JSON):
 *   { "p": "AMS-QR", "s": <session_id>, "n": <nonce>, "i": <issued_at>, "e": <expires_at> }
 */
class QrTokenService extends BaseService
{
    /**
     * Generate a fresh signed token for a session.
     *
     * @return array{token: string, expires_at: \Carbon\Carbon, token_id: int}
     */
    public function generate(Session $session): array
    {
        $ttl = (int) config('qr_attendance.token.ttl_seconds', 30);
        $issuedAt = now();
        $expiresAt = (clone $issuedAt)->addSeconds($ttl);
        $nonce = Str::random(32);

        $payload = [
            'p' => config('qr_attendance.token.prefix', 'AMS-QR'),
            's' => $session->id,
            'n' => $nonce,
            'i' => $issuedAt->timestamp,
            'e' => $expiresAt->timestamp,
        ];

        $payloadB64 = $this->base64UrlEncode(json_encode($payload, JSON_THROW_ON_ERROR));
        $signature = $this->sign($payloadB64);
        $token = $payloadB64 . '.' . $signature;

        $record = QrAttendanceToken::create([
            'session_id' => $session->id,
            'token_hash' => $signature,
            'nonce' => $nonce,
            'issued_at' => $issuedAt,
            'expires_at' => $expiresAt,
            'is_consumed' => false,
        ]);

        $this->logInfo("QR token issued for session {$session->id}, expires at {$expiresAt}");

        return [
            'token' => $token,
            'expires_at' => $expiresAt,
            'token_id' => $record->id,
        ];
    }

    /**
     * Verify a token string. Returns the matching token record (without
     * marking it consumed). Returns null if invalid, expired, or
     * HMAC-mismatched.
     */
    public function verify(string $token): ?QrAttendanceToken
    {
        $parts = explode('.', $token);
        if (count($parts) !== 2) {
            return null;
        }

        [$payloadB64, $signature] = $parts;

        if (! $this->verifyHmac($payloadB64, $signature)) {
            $this->logError('QR token HMAC verification failed');
            return null;
        }

        $payloadJson = $this->base64UrlDecode($payloadB64);
        if ($payloadJson === false) {
            return null;
        }

        try {
            $payload = json_decode($payloadJson, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            return null;
        }

        $expectedPrefix = config('qr_attendance.token.prefix', 'AMS-QR');
        if (($payload['p'] ?? null) !== $expectedPrefix) {
            return null;
        }

        $now = now()->timestamp;
        $skew = (int) config('qr_attendance.hmac.clock_skew', 60);

        if (! isset($payload['e'], $payload['i'], $payload['s'])) {
            return null;
        }

        if ($payload['e'] + $skew < $now) {
            return null;
        }
        if ($payload['i'] - $skew > $now) {
            return null; // token from the future
        }

        $sessionId = (int) $payload['s'];
        $nonce = (string) $payload['n'];

        $record = QrAttendanceToken::query()
            ->where('session_id', $sessionId)
            ->where('nonce', $nonce)
            ->where('token_hash', $signature)
            ->first();

        if (! $record || ! $record->isValid()) {
            return null;
        }

        return $record;
    }

    /**
     * Atomically mark a token as consumed for the given student. Uses
     * a row-level lock to prevent double-spend.
     */
    public function consume(QrAttendanceToken $token, int $studentProfileId, ?string $ip = null): bool
    {
        $updated = QrAttendanceToken::query()
            ->where('id', $token->id)
            ->where('is_consumed', false)
            ->update([
                'is_consumed' => true,
                'consumed_by_student_id' => $studentProfileId,
                'consumed_at' => now(),
                'consumed_ip' => $ip,
                'updated_at' => now(),
            ]);

        if ($updated === 0) {
            return false;
        }

        $token->refresh();
        return true;
    }

    /**
     * Compute the HMAC signature for a payload string.
     */
    public function sign(string $payloadB64): string
    {
        $secret = (string) config('qr_attendance.hmac.secret');
        $algo = (string) config('qr_attendance.hmac.algorithm', 'sha256');

        if ($secret === '') {
            if (app()->environment('local', 'testing')) {
                // Dev fallback: ephemeral in-memory secret. Refuses to verify across
                // requests, but unblocks local development.
                $secret = 'dev-only-ephemeral-secret-' . config('app.key');
            } else {
                throw new \RuntimeException('QR_HMAC_SECRET is not configured.');
            }
        }

        $raw = hash_hmac($algo, $payloadB64, $secret, true);
        return $this->base64UrlEncode($raw);
    }

    /**
     * Constant-time HMAC verification.
     */
    public function verifyHmac(string $payloadB64, string $signatureB64): bool
    {
        $expected = $this->sign($payloadB64);
        return hash_equals($expected, $signatureB64);
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64UrlDecode(string $data): string|false
    {
        $padded = str_pad(strtr($data, '-_', '+/'), strlen($data) % 4 === 0 ? strlen($data) : strlen($data) + 4 - strlen($data) % 4, '=');
        return base64_decode($padded, false);
    }
}
