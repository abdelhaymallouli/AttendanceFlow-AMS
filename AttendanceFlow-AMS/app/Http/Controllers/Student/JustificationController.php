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

        return view('student.justifications', compact('justifications'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'reason' => 'required|string|max:1000',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB
        ]);

        $studentProfile = Auth::user()->studentProfile;

        $path = $request->file('file')->store('justifications', 'public');

        Justification::create([
            'student_profile_id' => $studentProfile->id,
            'reason' => $request->reason,
            'file_path' => $path,
            'start_date' => $request->date,
            'end_date' => $request->date, // Default to same day for now
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        return redirect()->route('student.justifications.index')->with('success', 'Justification submitted for review.');
    }
}
