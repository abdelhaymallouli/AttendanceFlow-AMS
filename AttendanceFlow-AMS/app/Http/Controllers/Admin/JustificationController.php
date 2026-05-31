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
                    'documentUrl'   => \Illuminate\Support\Facades\Storage::url($j->document_name),
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
            'status' => 'required|in:approved,rejected,accepted',
        ]);

        $service = app(\App\Services\JustificationService::class);
        $service->reviewJustification($justification->id, $request->status);

        $statusLabel = $request->status === 'rejected' ? 'refusé' : 'accepté';
        $type = $request->status === 'rejected' ? 'danger' : 'success';

        \App\Models\Notification::create([
            'user_id' => $justification->studentProfile->user_id,
            'title' => 'Justificatif ' . $statusLabel,
            'message' => 'Votre justificatif pour la date du ' . \Carbon\Carbon::parse($justification->start_date)->format('d/m/Y') . ' a été ' . $statusLabel . '.',
            'type' => $type,
        ]);

        return back()->with('success', 'Justification status updated successfully.');
    }
}
