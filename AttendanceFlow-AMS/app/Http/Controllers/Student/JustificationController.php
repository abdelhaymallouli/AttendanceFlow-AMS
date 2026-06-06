<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\JustificationService;
use App\Services\AcademicService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JustificationController extends Controller
{
    protected JustificationService $justificationService;
    protected AcademicService $academicService;

    public function __construct(JustificationService $justificationService, AcademicService $academicService)
    {
        $this->justificationService = $justificationService;
        $this->academicService = $academicService;
    }

    public function index(Request $request)
    {
        $studentProfile = Auth::user()->studentProfile;
        
        $justifications = $this->justificationService->getStudentJustifications($studentProfile->id);

        $sessions = \App\Models\Session::with(['module', 'teacherProfile.user'])
            ->where('group_id', $studentProfile->group_id)
            ->where(function($q) {
                $q->where('start_time', '>', now())
                  ->orWhere('end_time', '>=', now()->subHours(48));
            })
            ->orderBy('start_time', 'desc')
            ->get();

        $preselectSessionId = (int) $request->query('session', 0);

        return view('student.justifications', compact('justifications', 'sessions', 'preselectSessionId'));
    }

    /**
     * Deep link from a notification: open the justification form pre-filtered to a session.
     */
    public function create(Request $request)
    {
        return $this->index($request);
    }

    public function store(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:academic_sessions,id',
            'reason' => 'required|string|max:1000',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB
        ]);

        $path = $request->file('file')->store('justifications', 'public');
        $data = [
            'session_id' => $request->session_id,
            'reason' => $request->reason,
            'document_name' => basename($path),
        ];

        try {
            $this->justificationService->submitJustification(Auth::user()->studentProfile->id, $data);
            return redirect()->route('student.justifications.index')->with('success', 'Justification submitted for review.');
        } catch (\Exception $e) {
            return back()->withErrors(['session_id' => $e->getMessage()]);
        }
    }
}
