<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OfflineSyncRequest;
use App\Http\Requests\Api\QrScanRequest;
use App\Http\Resources\OfflineSyncReportResource;
use App\Http\Resources\QrScanResultResource;
use App\Services\AttendanceService;
use App\Services\NotificationService;
use App\Services\Qr\OfflineQueueService;
use App\Services\Qr\QrTokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Mobile API for QR attendance.
 * All routes are prefixed with /api/attendance/qr and rate-limited
 * via the `qr_scan` throttle in RouteServiceProvider.
 */
class QrAttendanceController extends Controller
{
    public function __construct(
        private readonly AttendanceService $attendance,
        private readonly QrTokenService $tokens,
        private readonly OfflineQueueService $offline,
        private readonly NotificationService $notifications,
    ) {
    }

    /**
     * GET /api/attendance/qr/pre-check
     * Pre-checks network subnet matching and optional GPS positioning.
     */
    public function preCheck(Request $request): JsonResponse
    {
        $wifi = app(\App\Services\Qr\WifiSubnetService::class);
        $geo = app(\App\Services\Qr\GeolocationService::class);

        $ip = $request->ip();
        $networkOk = $wifi->isOnCampusNetwork($ip, $request);

        $lat = $request->query('latitude');
        $lng = $request->query('longitude');
        $accuracy = $request->query('accuracy');

        $gpsOk = false;
        $gpsReason = 'gps_missing';

        if ($lat !== null && $lng !== null) {
            $geoResult = $geo->isWithinCampus((float) $lat, (float) $lng, $accuracy !== null ? (float) $accuracy : null);
            $gpsOk = $geoResult['ok'];
            $gpsReason = $geoResult['reason'];
        }

        return response()->json([
            'network_ok' => $networkOk,
            'gps_ok' => $gpsOk,
            'gps_reason' => $gpsReason,
            'client_ip' => $ip,
        ]);
    }

    /**
     * GET /api/attendance/qr/token/{sessionId}
     * Returns the active (or freshly-issued) token for a session.
     * Teachers/admins only.
     */
    public function issueToken(int $sessionId): JsonResponse
    {
        $session = \App\Models\Session::findOrFail($sessionId);

        $user = request()->user();
        $isAdmin = $user->hasRole('admin');
        $isTeacher = $user->hasRole('teacher')
            && $session->teacherProfile
            && (int) $session->teacherProfile->user_id === (int) $user->id;

        if (! $isAdmin && ! $isTeacher) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $isMultiUse = request()->boolean('is_multi_use', true);
        $ttl = request()->has('ttl') ? (int) request()->input('ttl') : null;

        $token = $this->tokens->generate($session, $isMultiUse, $ttl);
        return response()->json([
            'token'      => $token['token'],
            'text_code'  => $token['text_code'],
            'issued_at'  => now()->toIso8601String(),
            'expires_at' => $token['expires_at']->timestamp,
            'session_id' => $session->id,
        ]);
    }

    /**
     * POST /api/attendance/qr/scan
     * Student submits a scanned QR token + GPS + device fingerprint.
     */
    public function scan(QrScanRequest $request): QrScanResultResource
    {
        $user = $request->user();
        $studentProfile = \App\Models\StudentProfile::where('user_id', $user->id)->first();

        if (! $studentProfile) {
            return new QrScanResultResource([
                'record' => null,
                'decision' => [
                    'status' => 'rejected',
                    'rejection_reason' => 'no_student_profile',
                    'score' => 0,
                    'max_score' => 0,
                    'signals' => [],
                ],
            ]);
        }

        $ip = $request->ip();

        try {
            $result = $this->attendance->markAttendanceViaQr(
                tokenString: (string) $request->input('token'),
                studentProfileId: (int) $request->input('student_profile_id', $studentProfile->id),
                latitude: (float) $request->input('latitude'),
                longitude: (float) $request->input('longitude'),
                gpsAccuracy: $request->input('accuracy') !== null ? (float) $request->input('accuracy') : null,
                clientIp: $ip,
                deviceFingerprintHash: $request->input('device_fingerprint'),
                request: $request,
            );
        } catch (\Throwable $e) {
            Log::error('QR scan failed', ['error' => $e->getMessage(), 'user_id' => $user->id]);
            return new QrScanResultResource([
                'record' => null,
                'decision' => [
                    'status' => 'rejected',
                    'rejection_reason' => 'server_error',
                    'score' => 0,
                    'max_score' => 0,
                    'signals' => [],
                ],
            ]);
        }

        // Notify the student on a successful scan
        $decisionStatus = $result['decision']['status'] ?? null;
        if (in_array($decisionStatus, ['present', 'late'], true)) {
            $session = \App\Models\Session::find($result['record']?->session_id ?? null);
            if ($session) {
                $this->notifications->notifyStudentPresent(
                    userId: $user->id,
                    sessionId: $session->id,
                    moduleName: $session->module->name ?? 'Séance',
                );
            }
        }

        return new QrScanResultResource($result);
    }

    /**
     * POST /api/attendance/qr/sync-offline
     * Batched sync of offline-captured scans.
     */
    public function syncOffline(OfflineSyncRequest $request): OfflineSyncReportResource
    {
        $user = $request->user();
        $batch = $request->input('entries', []);

        $report = $this->offline->sync($batch, $user);

        return new OfflineSyncReportResource($report);
    }
}
