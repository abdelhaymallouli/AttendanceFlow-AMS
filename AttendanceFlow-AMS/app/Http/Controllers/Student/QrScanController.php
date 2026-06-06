<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\StudentProfile;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Student web fallback for scanning a teacher's QR.
 * Mobile app is the primary path; this is provided for laptops/desktops
 * that can scan from a second device.
 */
class QrScanController extends Controller
{
    /**
     * GET /student/scan
     */
    public function show(Request $request): View
    {
        $user = $request->user();
        $studentProfile = StudentProfile::where('user_id', $user->id)->with('group')->firstOrFail();

        $recentRecords = AttendanceRecord::with('session.module')
            ->where('student_profile_id', $studentProfile->id)
            ->orderByDesc('date')
            ->limit(10)
            ->get();

        return view('student.qr.scan', [
            'student' => $studentProfile,
            'recentRecords' => $recentRecords,
        ]);
    }
}
