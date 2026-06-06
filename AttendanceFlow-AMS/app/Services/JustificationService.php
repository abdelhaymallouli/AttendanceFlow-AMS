<?php

namespace App\Services;

use App\Models\Justification;
use App\Models\AttendanceRecord;
use App\Models\Role;
use App\Models\User;

/**
 * JustificationService
 *
 * Manages the lifecycle of absence justifications.
 */
class JustificationService extends BaseService
{
    public function __construct(private readonly NotificationService $notifications)
    {
    }

    /**
     * Submit a new justification.
     */
    public function submitJustification(int $studentProfileId, array $data): Justification
    {
        $this->logInfo("New justification submitted by student {$studentProfileId}");

        $session = \App\Models\Session::findOrFail($data['session_id']);

        // Enforce 48-hour rule
        $endTime = \Carbon\Carbon::parse($session->end_time);
        if (now()->greaterThan($endTime->copy()->addHours(48))) {
            throw new \Exception("Submission rejected: The 48-hour deadline to justify this absence has passed.");
        }

        $justification = Justification::create([
            'student_profile_id' => $studentProfileId,
            'session_id'          => $session->id,
            'reason'              => $data['reason'],
            'document_name'       => $data['document_name'] ?? null,
            'start_date'          => \Carbon\Carbon::parse($session->start_time)->toDateString(),
            'end_date'            => \Carbon\Carbon::parse($session->end_time)->toDateString(),
            'status'              => 'pending',
            'submitted_at'        => now(),
        ]);

        // Notify every admin that a new justification is awaiting review
        $student = $justification->studentProfile;
        $studentName = $student?->user?->name ?? 'Un étudiant';
        $adminIds = User::role('admin')->pluck('id');
        foreach ($adminIds as $adminId) {
            $this->notifications->notifyAdminJustificationSubmitted(
                userId: (int) $adminId,
                justificationId: $justification->id,
                studentName: $studentName,
            );
        }

        return $justification;
    }

    /**
     * Get pending justifications.
     */
    public function getPending(): \Illuminate\Support\Collection
    {
        return Justification::where('status', 'pending')
            ->with(['studentProfile.user'])
            ->get();
    }

    /**
     * Get justifications for a student.
     */
    public function getStudentJustifications(int $studentProfileId): \Illuminate\Support\Collection
    {
        return Justification::where('student_profile_id', $studentProfileId)
            ->orderBy('submitted_at', 'desc')
            ->get();
    }

    /**
     * Get all justifications for administrative view.
     */
    public function getAllJustificationsWithRelations(): \Illuminate\Support\Collection
    {
        return Justification::with('studentProfile.user', 'studentProfile.group')
            ->orderBy('status', 'asc')
            ->orderBy('submitted_at', 'desc')
            ->get();
    }

    /**
     * Review and update justification status. Updates attendance records if approved.
     */
    public function reviewJustification(int $justificationId, string $status): Justification
    {
        $justification = Justification::findOrFail($justificationId);

        if ($status === 'accepted') {
            $status = 'approved';
        }

        $justification->status = $status;
        $justification->reviewed_at = now();
        $justification->reviewed_by = auth()->id();
        $justification->save();

        if ($status === 'approved') {
            $this->logInfo("Justification {$justificationId} approved. Updating related attendance records.");

            if ($justification->session_id) {
                $attendance = AttendanceRecord::where([
                    'student_profile_id' => $justification->student_profile_id,
                    'session_id' => $justification->session_id,
                ])->first();

                if ($attendance) {
                    $attendance->status = 'absent_excused';
                    $attendance->justification_id = $justification->id;
                    $attendance->save();
                }
            } else {
                AttendanceRecord::where('student_profile_id', $justification->student_profile_id)
                    ->whereBetween('date', [$justification->start_date, $justification->end_date])
                    ->whereIn('status', ['absent', 'absent_unexcused'])
                    ->update([
                        'status' => 'absent_excused',
                        'justification_id' => $justification->id
                    ]);
            }
        }

        $statusLabel = $status === 'rejected' ? 'refusé' : 'accepté';
        $type = $status === 'rejected' ? 'danger' : 'success';

        $this->notifications->create(
            userId: $justification->studentProfile->user_id,
            title: 'Justificatif ' . $statusLabel,
            message: 'Votre justificatif pour la date du ' . \Carbon\Carbon::parse($justification->start_date)->format('d/m/Y') . ' a été ' . $statusLabel . '.',
            category: $status === 'approved' ? 'justification.approved' : 'justification.rejected',
            type: $type,
            audience: 'student',
            data: [
                'justification_id' => $justification->id,
                'url' => route('student.justifications.index', [], false),
            ],
        );

        return $justification;
    }
}
