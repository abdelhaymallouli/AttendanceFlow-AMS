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
}