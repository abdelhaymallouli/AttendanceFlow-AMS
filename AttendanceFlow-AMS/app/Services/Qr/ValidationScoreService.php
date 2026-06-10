<?php

namespace App\Services\Qr;

use App\Models\QrAttendanceToken;
use App\Models\User;
use App\Services\BaseService;
use Illuminate\Http\Request;

/**
 * ValidationScoreService
 * ======================
 * Aggregates the 4 multi-factor signals into a final score that
 * maps to a present/late/rejected decision.
 */
class ValidationScoreService extends BaseService
{
    public function __construct(
        ?GeolocationService $geolocation = null,
        ?WifiSubnetService $wifi = null,
        ?DeviceFingerprintService $device = null,
    ) {
        $this->geolocation = $geolocation ?? app(GeolocationService::class);
        $this->wifi = $wifi ?? app(WifiSubnetService::class);
        $this->device = $device ?? app(DeviceFingerprintService::class);
    }

    /**
     * @return array{
     *     status: string,
     *     score: int,
     *     max_score: int,
     *     signals: array<string, array{ok: bool, points: int, max: int, reason: ?string}>,
     *     rejection_reason: ?string
     * }
     */
    public function evaluate(QrScanContext $context): array
    {
        $weights = (array) config('qr_attendance.validation.weights', [
            'hmac' => 100,
            'geolocation' => 40,
            'wifi' => 30,
            'device' => 30,
        ]);
        $thresholds = (array) config('qr_attendance.validation.thresholds', [
            'present_min' => 70,
            'late_min' => 40,
        ]);

        $signals = [];

        // 1. HMAC (binary: must pass)
        $hmacOk = $context->token instanceof QrAttendanceToken && $context->token->isValid();
        $signals['hmac'] = [
            'ok' => $hmacOk,
            'points' => $hmacOk ? (int) $weights['hmac'] : 0,
            'max' => (int) $weights['hmac'],
            'reason' => $hmacOk ? null : ($context->token === null ? 'token_invalid' : 'token_expired_or_consumed'),
        ];

        if (! $hmacOk) {
            return [
                'status' => 'rejected',
                'score' => 0,
                'max_score' => array_sum($weights),
                'signals' => $signals,
                'rejection_reason' => 'invalid_or_expired_qr_token',
            ];
        }

        // 2. Geolocation
        $geo = $this->geolocation->isWithinCampus($context->latitude, $context->longitude, $context->gpsAccuracy);
        $signals['geolocation'] = [
            'ok' => $geo['ok'],
            'points' => $geo['ok'] ? (int) $weights['geolocation'] : 0,
            'max' => (int) $weights['geolocation'],
            'reason' => $geo['reason'],
        ];

        // 3. Wi-Fi
        $wifiOk = $this->wifi->isOnCampusNetwork($context->clientIp, $context->request);
        $signals['wifi'] = [
            'ok' => $wifiOk,
            'points' => $wifiOk ? (int) $weights['wifi'] : 0,
            'max' => (int) $weights['wifi'],
            'reason' => $wifiOk ? null : 'ip_not_in_campus_subnets',
        ];

        // 4. Device fingerprint
        $deviceOk = false;
        if (config('qr_attendance.device.enabled', true)) {
            if ($context->user instanceof User && $context->deviceFingerprintHash !== null) {
                $deviceOk = $this->device->isKnown($context->user, $context->deviceFingerprintHash);
            }
        } else {
            $deviceOk = true; // disabled → always pass
        }
        $signals['device'] = [
            'ok' => $deviceOk,
            'points' => $deviceOk ? (int) $weights['device'] : 0,
            'max' => (int) $weights['device'],
            'reason' => $deviceOk ? null : 'unknown_device',
        ];

        $total = array_sum(array_map(fn ($s) => $s['points'], $signals));
        $max = array_sum($weights);

        $allPassed = $hmacOk && $geo['ok'] && $wifiOk;
        $status = $allPassed ? 'present' : 'rejected';

        $rejectionReason = null;
        if (! $allPassed) {
            if (! $geo['ok']) {
                $rejectionReason = $geo['reason'] ?? 'outside_campus_radius';
            } elseif (! $wifiOk) {
                $rejectionReason = 'ip_not_in_campus_subnets';
            } else {
                $rejectionReason = 'validation_failed';
            }
        }

        return [
            'status' => $status,
            'score' => $total,
            'max_score' => $max,
            'signals' => $signals,
            'rejection_reason' => $rejectionReason,
        ];
    }
}

/**
 * Context object passed to ValidationScoreService::evaluate().
 * Plain DTO; constructed in the controller from the request payload.
 */
class QrScanContext
{
    public function __construct(
        public ?QrAttendanceToken $token,
        public ?User $user,
        public ?float $latitude,
        public ?float $longitude,
        public ?float $gpsAccuracy,
        public ?string $clientIp,
        public ?string $deviceFingerprintHash,
        public ?Request $request = null,
    ) {
    }
}
