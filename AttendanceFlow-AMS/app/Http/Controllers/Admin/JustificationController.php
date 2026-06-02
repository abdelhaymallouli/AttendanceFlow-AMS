<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\JustificationService;
use Illuminate\Http\Request;

class JustificationController extends Controller
{
    protected JustificationService $justificationService;

    public function __construct(JustificationService $justificationService)
    {
        $this->justificationService = $justificationService;
    }

    public function index()
    {
        $justifications = $this->justificationService->getAllJustificationsWithRelations()
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

        $pendingCount = $this->justificationService->getPending()->count();

        return view('admin.justifications', compact('justifications', 'pendingCount'));
    }

    public function update(Request $request, \App\Models\Justification $justification)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,accepted',
        ]);

        $this->justificationService->reviewJustification($justification->id, $request->status);

        return back()->with('success', 'Justification status updated successfully.');
    }
}
