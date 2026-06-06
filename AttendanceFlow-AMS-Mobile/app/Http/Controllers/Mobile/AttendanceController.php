<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    protected $api;

    public function __construct(ApiService $apiService)
    {
        $this->api = $apiService;
    }

    /**
     * Record attendance for a session.
     */
    public function record(Request $request)
    {
        $validated = $request->validate([
            'session_id' => 'required|integer',
            'date' => 'required|date',
            'records' => 'required|array',
            'records.*.student_profile_id' => 'required|integer',
            'records.*.status' => 'required|string|in:present,absent,late',
            'records.*.late_reason' => 'nullable|string|max:255',
        ]);

        $attendanceData = [
            'session_id' => $validated['session_id'],
            'date' => $validated['date'],
            'records' => array_map(function($record) {
                return [
                    'student_profile_id' => $record['student_profile_id'],
                    'status' => $record['status'],
                    'late_reason' => $record['status'] === 'late' ? $record['late_reason'] : null,
                ];
            }, $validated['records']),
        ];

        $response = $this->api->recordAttendance($attendanceData);

        if ($response['success'] ?? false) {
            return redirect()
                ->route('mobile.session.show', $validated['session_id'])
                ->with('success', 'Présence enregistrée avec succès');
        }

        return back()
            ->withInput()
            ->with('error', 'Erreur lors de l\'enregistrement de la présence');
    }

    /**
     * GET /mobile/scan
     * Student QR scan page (camera + manual fallback).
     */
    public function scan(Request $request)
    {
        $pendingCount = count($this->loadPendingOfflineScans());
        return view('mobile.attendance.scan', [
            'pendingCount' => $pendingCount,
        ]);
    }

    /**
     * POST /mobile/scan/submit
     * Submit a scanned QR token from the mobile device.
     */
    public function submitScan(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string|min:32',
            'student_profile_id' => 'required|integer',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'accuracy' => 'nullable|numeric',
            'device_fingerprint' => 'nullable|string|max:128',
        ]);

        $payload = [
            'token' => $validated['token'],
            'student_profile_id' => $validated['student_profile_id'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'accuracy' => $validated['accuracy'] ?? null,
            'device_fingerprint' => $validated['device_fingerprint'] ?? null,
            'client_ip' => $request->ip(),
        ];

        if (! $request->boolean('force_online', true)) {
            $payload['client_timestamp'] = now()->toIso8601String();
            $payload['nonce'] = bin2hex(random_bytes(8));
            $this->enqueueOfflineScan($payload);
            return response()->json([
                'queued' => true,
                'status' => 'queued',
                'message' => 'Aucune connexion — scan mis en file d\'attente pour synchronisation.',
            ]);
        }

        $result = $this->api->qrScan($payload);
        return response()->json($result);
    }

    /**
     * POST /mobile/scan/sync
     * Sync all queued offline scans.
     */
    public function syncOffline(Request $request)
    {
        $entries = $this->loadPendingOfflineScans();
        if (empty($entries)) {
            return response()->json(['processed' => 0, 'accepted' => 0, 'rejected' => 0, 'results' => []]);
        }
        $report = $this->api->qrSyncOffline($entries);
        if (($report['accepted'] ?? 0) + ($report['rejected'] ?? 0) >= count($entries)) {
            $this->clearPendingOfflineScans();
        }
        return response()->json($report);
    }

    protected function enqueueOfflineScan(array $entry): void
    {
        $queue = $this->loadPendingOfflineScans();
        $queue[] = $entry;
        $this->persistOfflineScans($queue);
    }

    protected function loadPendingOfflineScans(): array
    {
        $raw = \Illuminate\Support\Facades\Cache::get('mobile_qr_queue_' . session()->getId(), '[]');
        if (is_string($raw)) {
            $decoded = json_decode($raw, true);
            return is_array($decoded) ? $decoded : [];
        }
        return is_array($raw) ? $raw : [];
    }

    protected function persistOfflineScans(array $queue): void
    {
        \Illuminate\Support\Facades\Cache::put(
            'mobile_qr_queue_' . session()->getId(),
            json_encode($queue),
            now()->addDays(7)
        );
    }

    protected function clearPendingOfflineScans(): void
    {
        \Illuminate\Support\Facades\Cache::forget('mobile_qr_queue_' . session()->getId());
    }
}