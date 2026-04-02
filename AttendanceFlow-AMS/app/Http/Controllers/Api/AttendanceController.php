<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    public function getStudentAttendance($id)
    {
        return response()->json(AttendanceRecord::where('student_profile_id', $id)->get());
    }

    public function getSessionAttendance($id)
    {
        return response()->json(AttendanceRecord::where('session_id', $id)
            ->with('studentProfile.user')
            ->get());
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

        foreach ($request->records as $record) {
            AttendanceRecord::updateOrCreate(
                [
                    'session_id' => $request->session_id,
                    'student_profile_id' => $record['student_profile_id'],
                    'date' => $request->date
                ],
                ['status' => $record['status']]
            );
        }

        return response()->json(['message' => 'Attendance recorded successfully']);
    }
}
