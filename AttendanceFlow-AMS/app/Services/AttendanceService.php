<?php

namespace App\Services;

use App\Models\AttendanceRecord;
use App\Models\QrAttendanceToken;
use App\Models\StudentProfile;
use Illuminate\Support\Collection;

/**
 * AttendanceService
 * 
 * Records and analyzes student presence.
 * 
 * Two entry points:
 *  - markAttendance()        : manual/override (radio buttons, CSV import)
 *  - markAttendanceViaQr()   : QR scan flow (multi-factor validation)
 */
class AttendanceService extends BaseService
{
    public function __construct(
        ?\App\Services\Qr\QrTokenService $qrTokens = null,
        ?\App\Services\Qr\ValidationScoreService $validator = null,
        ?\App\Services\Qr\GeolocationService $geolocation = null,
        ?\App\Services\Qr\DeviceFingerprintService $deviceService = null,
    ) {
        $this->qrTokens = $qrTokens ?? app(\App\Services\Qr\QrTokenService::class);
        $this->validator = $validator ?? app(\App\Services\Qr\ValidationScoreService::class);
        $this->geolocation = $geolocation ?? app(\App\Services\Qr\GeolocationService::class);
        $this->deviceService = $deviceService ?? app(\App\Services\Qr\DeviceFingerprintService::class);
    }

    /**
     * Manual / override path (existing behavior).
     */
    public function markAttendance(int $studentProfileId, int $sessionId, string $status, string $date, string $method = 'manual', ?string $note = null): AttendanceRecord
    {
        $this->logInfo("Marking attendance for student {$studentProfileId} in session {$sessionId}: {$status}");

        $justification = null;
        if (in_array($status, ['absent', 'absent_unexcused', 'absent_excused'], true)) {
            $justification = \App\Models\Justification::where([
                'student_profile_id' => $studentProfileId,
                'session_id' => $sessionId,
            ])->where('status', 'approved')->first();

            if ($justification) {
                $status = 'absent_excused';
            } else {
                $status = 'absent_unexcused';
            }
        }

        $record = AttendanceRecord::updateOrCreate(
            ['student_profile_id' => $studentProfileId, 'session_id' => $sessionId],
            [
                'status' => $status,
                'date' => $date,
                'justification_id' => $justification?->id,
                'check_in_method' => $method,
                'note' => $note,
            ]
        );

        $this->fireNotifications($record, $status);

        return $record;
    }

    /**
     * QR code path. Verifies the token, runs the multi-factor scoring,
     * and persists the resulting attendance record.
     *
     * @return array{record: ?AttendanceRecord, decision: array<string, mixed>, token: ?QrAttendanceToken}
     */
    public function markAttendanceViaQr(
        string $tokenString,
        int $studentProfileId,
        ?float $latitude = null,
        ?float $longitude = null,
        ?float $gpsAccuracy = null,
        ?string $clientIp = null,
        ?string $deviceFingerprintHash = null,
        ?\Illuminate\Http\Request $request = null,
    ): array {
        $student = StudentProfile::with('user')->find($studentProfileId);
        if (! $student) {
            return [
                'record' => null,
                'decision' => [
                    'status' => 'rejected',
                    'rejection_reason' => 'student_not_found',
                    'score' => 0,
                    'max_score' => 0,
                    'signals' => [],
                ],
                'token' => null,
            ];
        }

        $token = $this->qrTokens->verify($tokenString);
        if (! $token) {
            return [
                'record' => null,
                'decision' => [
                    'status' => 'rejected',
                    'rejection_reason' => 'invalid_or_expired_qr_token',
                    'score' => 0,
                    'max_score' => 0,
                    'signals' => [],
                ],
                'token' => null,
            ];
        }

        $context = new \App\Services\Qr\QrScanContext(
            token: $token,
            user: $student->user,
            latitude: $latitude,
            longitude: $longitude,
            gpsAccuracy: $gpsAccuracy,
            clientIp: $clientIp,
            deviceFingerprintHash: $deviceFingerprintHash,
            request: $request,
        );

        $decision = $this->validator->evaluate($context);

        if ($decision['status'] === 'rejected') {
            return [
                'record' => null,
                'decision' => $decision,
                'token' => $token,
            ];
        }

        $consumed = $this->qrTokens->consume($token, $student->id, $clientIp);
        if (! $consumed) {
            return [
                'record' => null,
                'decision' => [
                    'status' => 'rejected',
                    'rejection_reason' => 'token_already_consumed',
                    'score' => $decision['score'],
                    'max_score' => $decision['max_score'],
                    'signals' => $decision['signals'],
                ],
                'token' => $token,
            ];
        }

        $session = \App\Models\Session::find($token->session_id);
        $date = $session ? \Carbon\Carbon::parse($session->start_time)->toDateString() : now()->toDateString();

        $record = AttendanceRecord::updateOrCreate(
            ['student_profile_id' => $student->id, 'session_id' => $token->session_id],
            [
                'status' => $decision['status'],
                'date' => $date,
                'justification_id' => null,
                'check_in_method' => 'qr',
                'latitude' => $latitude,
                'longitude' => $longitude,
                'distance_meters' => isset($latitude, $longitude)
                    ? (int) round($this->geolocation->getDistanceToCampus($latitude, $longitude))
                    : null,
                'wifi_ip' => $clientIp,
                'qr_token_id' => $token->id,
                'synced_at' => now(),
                'validation_score' => $decision['score'],
            ]
        );

        if ($deviceFingerprintHash !== null) {
            $fp = $this->deviceService->register($student->user, $deviceFingerprintHash, $request?->userAgent());
            $record->device_fingerprint_id = $fp->id;
            $record->save();
        }

        $this->fireNotifications($record, $decision['status']);

        return [
            'record' => $record,
            'decision' => $decision,
            'token' => $token,
        ];
    }

    /**
     * Bulk mark attendance for an entire session.
     */
    public function bulkMarkAttendance(int $sessionId, array $attendanceData): void
    {
        $this->logInfo("Bulk marking attendance for session {$sessionId}");

        foreach ($attendanceData as $data) {
            $this->markAttendance(
                (int) $data['student_profile_id'],
                $sessionId,
                (string) $data['status'],
                (string) $data['date'],
            );
        }
    }

    public function getSessionAttendance(int $sessionId): Collection
    {
        return AttendanceRecord::with('studentProfile.user')
            ->where('session_id', $sessionId)
            ->get();
    }

    public function getStudentAttendance(int $studentProfileId): Collection
    {
        return AttendanceRecord::where('student_profile_id', $studentProfileId)->get();
    }

    /**
     * Fire the standard absence/late notifications.
     */
    private function fireNotifications(AttendanceRecord $record, string $status): void
    {
        if (! in_array($status, ['absent_unexcused', 'absent_excused', 'late'], true)) {
            return;
        }

        $student = \App\Models\StudentProfile::find($record->student_profile_id);
        $session = \App\Models\Session::with(['module', 'teacherProfile.user'])->find($record->session_id);

        if ($student && $student->user_id) {
            $moduleName = $session->module->name ?? 'Séance';
            $statusLabel = $status === 'absent_excused'
                ? 'absent (justifié)'
                : ($status === 'absent_unexcused' ? 'absent (non justifié)' : 'en retard');
            \App\Models\Notification::create([
                'user_id' => $student->user_id,
                'title' => $status === 'late' ? 'Retard signalé' : 'Nouvelle absence signalée',
                'message' => 'Vous avez été marqué ' . $statusLabel . ' le ' . \Carbon\Carbon::parse($record->date)->format('d/m/Y') . ' pour la séance de : ' . $moduleName . '.',
                'type' => $status === 'absent_unexcused' ? 'danger' : ($status === 'absent_excused' ? 'success' : 'info'),
            ]);
        }

        if ($session && $session->teacherProfile && $session->teacherProfile->user_id) {
            \App\Models\Notification::create([
                'user_id' => $session->teacherProfile->user_id,
                'title' => 'Absence Étudiant',
                'message' => 'L\'étudiant ' . ($student->user->name ?? 'inconnu') . ' a été marqué ' . ($status === 'absent_excused' ? 'absent (justifié)' : 'absent (non justifié)') . ' pour la séance de : ' . ($session->module->name ?? 'Séance') . '.',
                'type' => 'danger',
            ]);
        }
    }
}
