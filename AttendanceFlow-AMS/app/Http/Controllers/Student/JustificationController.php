<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Justification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class JustificationController extends Controller
{
    public function index()
    {
        $studentProfile = Auth::user()->studentProfile;
        
        $justifications = Justification::where('student_profile_id', $studentProfile->id)
            ->orderBy('submitted_at', 'desc')
            ->get();

        // Fetch sessions scheduled for student's group: future sessions or past sessions within 48 hours
        $sessions = \App\Models\Session::with(['module', 'teacherProfile.user'])
            ->where('group_id', $studentProfile->group_id)
            ->where(function($q) {
                $q->where('start_time', '>', now())
                  ->orWhere('end_time', '>=', now()->subHours(48));
            })
            ->orderBy('start_time', 'desc')
            ->get();

        return view('student.justifications', compact('justifications', 'sessions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:academic_sessions,id',
            'reason' => 'required|string|max:1000',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB
        ]);

        $studentProfile = Auth::user()->studentProfile;
        $session = \App\Models\Session::findOrFail($request->session_id);

        // Enforce 48-hour rule
        $endTime = \Carbon\Carbon::parse($session->end_time);
        if (now()->greaterThan($endTime->copy()->addHours(48))) {
            return back()->withErrors(['session_id' => 'Submission rejected: The 48-hour deadline to justify this absence has passed.']);
        }

        $path = $request->file('file')->store('justifications', 'public');

        Justification::create([
            'student_profile_id' => $studentProfile->id,
            'session_id' => $session->id,
            'reason' => $request->reason,
            'document_name' => $path,
            'start_date' => \Carbon\Carbon::parse($session->start_time)->toDateString(),
            'end_date' => \Carbon\Carbon::parse($session->end_time)->toDateString(),
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        return redirect()->route('student.justifications.index')->with('success', 'Justification submitted for review.');
    }
}
