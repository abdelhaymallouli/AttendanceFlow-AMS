<?php

namespace App\Services\Qr;

use App\Models\AttendanceRecord;
use App\Models\QrAttendanceToken;
use App\Models\StudentProfile;
use App\Models\User;
use App\Services\BaseService;
use Illuminate\Support\Carbon;

/**
 * OfflineQueueService
 * ===================
 * Handles batched sync of QR scans captured offline on the mobile
 * device. Idempotent on (session_id, student_id, nonce).
 */
class OfflineQueueService extends BaseService
{
    public function __construct(
        ?QrTokenService $tokens = null,
        ?ValidationScoreService $validator = null,
        ?GeolocationService $geolocation = null,
        ?WifiSubnetService $wifi = null,
        ?DeviceFingerprintService $device = null,
    ) {
        $this->tokens = $tokens ?? app(QrTokenService::class);
        $this->validator = $validator ?? app(ValidationScoreService::class);
        $this->geolocation = $geolocation ?? app(GeolocationService::class);
        $this->wifi = $wifi ?? app(WifiSubnetService::class);
        $this->device = $device ?? app(DeviceFingerprintService::class);
    }

    /**
     * Deduplicate a batch by (session_id, student_id, nonce).
     *
     * @param  array<int, array<string, mixed>>  $batch
     * @return array<int, array<string, mixed>>
     */
    public function deduplicate(array $batch): array
    {
        $seen = [];
        $unique = [];

        foreach ($batch as $entry) {
            $key = sprintf(
                '%d|%d|%s',
                (int) ($entry['session_id'] ?? 0),
                (int) ($entry['student_id'] ?? 0),
                (string) ($entry['nonce'] ?? '')
            );

            if (isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;
            $unique[] = $entry;
        }

        return $unique;
    }

    /**
     * Is the given client timestamp too old to be accepted?
     */
    public function isStale(?string $clientTimestamp): bool
    {
        if ($clientTimestamp === null || $clientTimestamp === '') {
            return true;
        }

        try {
            $ts = Carbon::parse($clientTimestamp);
        } catch (\Throwable) {
            return true;
        }

        $maxAgeHours = (int) config('qr_attendance.offline.max_age_hours', 24);
        return $ts->lt(now()->subHours($maxAgeHours));
    }

    /**
     * Process a batch of offline scans. Returns a per-entry report.
     *
     * @param  array<int, array<string, mixed>>  $batch
     * @param  User  $user  The authenticated student user
     * @return array{processed: int, accepted: int, rejected: int, results: array<int, array<string, mixed>>}
     */
    public function sync(array $batch, User $user): array
    {
        $maxBatch = (int) config('qr_attendance.offline.max_batch_size', 50);
        if (count($batch) > $maxBatch) {
            throw new \InvalidArgumentException("Batch size exceeds limit of {$maxBatch}.");
        }

        if (! config('qr_attendance.offline.enabled', true)) {
            return [
                'processed' => 0,
                'accepted' => 0,
                'rejected' => count($batch),
                'results' => array_map(fn ($e) => [
                    'status' => 'rejected',
                    'reason' => 'offline_sync_disabled',
                ], $batch),
            ];
        }

        $batch = $this->deduplicate($batch);
        $results = [];
        $accepted = 0;
        $rejected = 0;

        foreach ($batch as $entry) {
            $clientTs = $entry['client_timestamp'] ?? null;
            if ($this->isStale($clientTs)) {
                $results[] = ['status' => 'rejected', 'reason' => 'scan_too_old'];
                $rejected++;
                continue;
            }

            $tokenString = (string) ($entry['token'] ?? '');
            $token = $this->tokens->verify($tokenString);
            if (! $token) {
                $results[] = ['status' => 'rejected', 'reason' => 'invalid_token'];
                $rejected++;
                continue;
            }

            $context = new QrScanContext(
                token: $token,
                user: $user,
                latitude: isset($entry['latitude']) ? (float) $entry['latitude'] : null,
                longitude: isset($entry['longitude']) ? (float) $entry['longitude'] : null,
                gpsAccuracy: isset($entry['accuracy']) ? (float) $entry['accuracy'] : null,
                clientIp: $entry['client_ip'] ?? null,
                deviceFingerprintHash: $entry['device_fingerprint'] ?? null,
            );

            $result = $this->validator->evaluate($context);
            if ($result['status'] === 'rejected') {
                $results[] = [
                    'status' => 'rejected',
                    'reason' => $result['rejection_reason'] ?? 'low_score',
                    'score' => $result['score'],
                ];
                $rejected++;
                continue;
            }

            $studentProfile = StudentProfile::where('user_id', $user->id)->first();
            if (! $studentProfile) {
                $results[] = ['status' => 'rejected', 'reason' => 'no_student_profile'];
                $rejected++;
                continue;
            }

            $consumed = $this->tokens->consume($token, $studentProfile->id, $context->clientIp);
            if (! $consumed) {
                $results[] = ['status' => 'rejected', 'reason' => 'token_already_used'];
                $rejected++;
                continue;
            }

            $record = AttendanceRecord::updateOrCreate(
                [
                    'student_profile_id' => $studentProfile->id,
                    'session_id' => $token->session_id,
                ],
                [
                    'status' => $result['status'],
                    'date' => now()->toDateString(),
                    'check_in_method' => 'offline',
                    'latitude' => $context->latitude,
                    'longitude' => $context->longitude,
                    'distance_meters' => isset($context->latitude, $context->longitude)
                        ? (int) round($this->geolocation->getDistanceToCampus($context->latitude, $context->longitude))
                        : null,
                    'wifi_ip' => $context->clientIp,
                    'qr_token_id' => $token->id,
                    'synced_at' => now(),
                    'validation_score' => $result['score'],
                ]
            );

            if ($context->deviceFingerprintHash !== null) {
                $fp = $this->device->register($user, $context->deviceFingerprintHash);
                $record->device_fingerprint_id = $fp->id;
                $record->save();
            }

            $results[] = [
                'status' => $result['status'],
                'score' => $result['score'],
                'record_id' => $record->id,
            ];
            $accepted++;
        }

        return [
            'processed' => count($batch),
            'accepted' => $accepted,
            'rejected' => $rejected,
            'results' => $results,
        ];
    }
}
