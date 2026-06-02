<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    protected AttendanceService $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function getStudentAttendance($id)
    {
        return response()->json($this->attendanceService->getStudentAttendance($id));
    }

    public function getSessionAttendance($id)
    {
        return response()->json($this->attendanceService->getSessionAttendance($id));
    }

    public function recordAttendance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_id' => 'required|exists:academic_sessions,id',
            'date' => 'required|date',
            'records' => 'required|array',
            'records.*.student_profile_id' => 'required|exists:student_profiles,id',
            'records.*.status' => 'required|in:present,absent,late'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $this->attendanceService->bulkMarkAttendance($request->session_id, $request->records);

        return response()->json(['message' => 'Attendance recorded successfully']);
    }
}
