<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Justification;
use Illuminate\Http\Request;

class JustificationController extends Controller
{
    public function index()
    {
        $justifications = Justification::with('studentProfile.user', 'studentProfile.group')
            ->orderBy('status', 'asc')
            ->orderBy('submitted_at', 'desc')
            ->get()
            ->map(function($j) {
                return [
                    'id'            => $j->id,
                    'studentName'   => $j->studentProfile->user->name ?? 'Unknown',
                    'studentId'     => $j->studentProfile->student_id ?? 'N/A',
                    'grade'         => $j->studentProfile->group->name ?? 'G1',
                    'absenceDate'   => \Carbon\Carbon::parse($j->start_date)->format('M d, Y'),
                    'documentUrl'   => \Illuminate\Support\Facades\Storage::url($j->file_path),
                    'reason'        => $j->reason,
                    'submittedDate' => \Carbon\Carbon::parse($j->created_at)->format('M d, Y'),
                    'status'        => $j->status,
                    'updateUrl'     => route('admin.justifications.update', $j->id),
                ];
            })
            ->values();

        $pendingCount = \App\Models\Justification::where('status', 'pending')->count();

        return view('admin.justifications', compact('justifications', 'pendingCount'));
    }

    public function update(Request $request, Justification $justification)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $justification->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Justification ' . $request->status . '.');
    }
}
