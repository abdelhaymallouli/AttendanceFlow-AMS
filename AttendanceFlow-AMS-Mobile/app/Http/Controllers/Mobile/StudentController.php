<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    protected ApiService $api;

    public function __construct(ApiService $apiService)
    {
        $this->api = $apiService;
    }

    private function getProfileId(): ?int
    {
        return session('mobile_student_profile_id');
    }

    private function getGroupId(): ?int
    {
        return session('mobile_student_group_id');
    }

    private function requireProfile()
    {
        $profileId = $this->getProfileId();
        if (! $profileId) {
            abort(403, 'Profil étudiant non trouvé.');
        }
        return $profileId;
    }

    public function home()
    {
        $profileId = $this->requireProfile();
        $stats = $this->api->getStudentStats($profileId);
        $attendance = $this->api->getStudentAttendance($profileId);

        return view('mobile.student.home', compact('stats', 'attendance'));
    }

    public function absences()
    {
        $profileId = $this->requireProfile();
        $attendance = $this->api->getStudentAttendance($profileId);
        $justifications = $this->api->getStudentJustifications($profileId);

        $justifiedSessionIds = collect($justifications)
            ->where('status', 'approved')
            ->pluck('session_id')
            ->toArray();

        $absences = collect($attendance)->filter(function ($record) use ($justifiedSessionIds) {
            return in_array($record['status'] ?? '', ['absent_unexcused', 'absent_excused', 'absent']);
        })->map(function ($record) use ($justifiedSessionIds) {
            $record['is_justified'] = in_array($record['session_id'] ?? 0, $justifiedSessionIds);
            return $record;
        })->values()->all();

        return view('mobile.student.absences', compact('absences'));
    }

    public function justifications()
    {
        $profileId = $this->requireProfile();
        $justifications = $this->api->getStudentJustifications($profileId);

        return view('mobile.student.justifications', compact('justifications'));
    }

    public function justificationsCreate()
    {
        $profileId = $this->requireProfile();
        $groupId = $this->getGroupId();

        $sessions = [];
        if ($groupId) {
            $sessions = $this->api->getGroupSessions($groupId);
        }

        $attendance = $this->api->getStudentAttendance($profileId);

        $absentSessionIds = collect($attendance)
            ->whereIn('status', ['absent_unexcused', 'absent'])
            ->pluck('session_id')
            ->unique()
            ->toArray();

        $absentSessions = collect($sessions)
            ->filter(fn($s) => in_array($s['id'], $absentSessionIds))
            ->values()
            ->all();

        return view('mobile.student.justifications-create', compact('absentSessions'));
    }

    public function justificationsStore(Request $request)
    {
        $profileId = $this->requireProfile();

        $validated = $request->validate([
            'session_id' => 'required|integer',
            'reason' => 'required|string|max:1000',
            'type' => 'required|in:medical,family,academic,other',
            'document' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048',
        ]);

        $data = [
            'student_profile_id' => $profileId,
            'session_id' => $validated['session_id'],
            'reason' => $validated['reason'],
            'start_date' => now()->toDateString(),
            'end_date' => now()->toDateString(),
            'type' => $validated['type'],
        ];

        $filePath = null;
        if ($request->hasFile('document')) {
            $filePath = $request->file('document')->getRealPath();
        }

        $result = $this->api->submitJustificationWithFile($data, $filePath);

        if (isset($result['message']) && !isset($result['errors'])) {
            return redirect()->route('mobile.justifications')
                ->with('success', 'Justification soumise avec succès.');
        }

        $errorMsg = $result['errors']['submission'][0] ?? $result['message'] ?? 'Erreur lors de la soumission.';
        return back()->withInput()->with('error', $errorMsg);
    }

    public function sessions()
    {
        $groupId = $this->getGroupId();

        $sessions = [];
        if ($groupId) {
            $sessions = $this->api->getGroupSessions($groupId);
        }

        return view('mobile.student.sessions', compact('sessions'));
    }

    public function sessionDetail($id)
    {
        $session = $this->api->getSession($id);
        return view('mobile.student.session-detail', compact('session'));
    }
}
