<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Support\Collection;

class NotificationService extends BaseService
{
    /**
     * Get notifications for a user (most recent first).
     */
    public function getUserNotifications(int $userId, int $limit = 30): Collection
    {
        return Notification::forUser($userId)
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Get unread notifications for a user.
     */
    public function getUnread(int $userId, int $limit = 30): Collection
    {
        return Notification::forUser($userId)
            ->unread()
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Count unread notifications for a user.
     */
    public function countUnread(int $userId): int
    {
        return Notification::forUser($userId)->unread()->count();
    }

    /**
     * Mark a single notification as read (with ownership check).
     */
    public function markAsRead(int $id, int $userId): bool
    {
        $n = Notification::forUser($userId)->find($id);
        return $n ? (bool) $n->update(['is_read' => true]) : false;
    }

    /**
     * Mark all unread notifications for a user as read.
     */
    public function markAllAsRead(int $userId): int
    {
        return Notification::forUser($userId)->unread()->update(['is_read' => true]);
    }

    /**
     * Clear (delete) all notifications for a user.
     */
    public function clearAllNotifications(int $userId): int
    {
        return Notification::forUser($userId)->delete();
    }

    /**
     * Create a notification (fluent).
     *
     * @param  array<string, mixed>  $data
     */
    public function create(int $userId, string $title, string $message, string $category = 'general', string $type = 'info', ?string $audience = null, array $data = []): Notification
    {
        return Notification::create([
            'user_id'  => $userId,
            'title'    => $title,
            'message'  => $message,
            'type'     => $type,
            'category' => $category,
            'audience' => $audience,
            'data'     => $data,
        ]);
    }

    /**
     * Notify a student that they have been marked absent.
     * Carries a redirect URL to the justification create page.
     */
    public function notifyStudentAbsent(int $userId, int $sessionId, string $moduleName, ?int $attendanceRecordId = null): Notification
    {
        return $this->create(
            userId: $userId,
            title: 'Absence enregistrée',
            message: "Vous avez été marqué(e) absent(e) à la séance de « {$moduleName} ».",
            category: 'attendance.absent',
            type: 'danger',
            audience: 'student',
            data: [
                'session_id' => $sessionId,
                'attendance_record_id' => $attendanceRecordId,
                'module' => $moduleName,
                'url' => route('student.justifications.create', ['session' => $sessionId], false),
            ],
        );
    }

    /**
     * Notify a student that they have been marked present.
     */
    public function notifyStudentPresent(int $userId, int $sessionId, string $moduleName): Notification
    {
        return $this->create(
            userId: $userId,
            title: 'Présence confirmée',
            message: "Votre présence à « {$moduleName} » a bien été enregistrée.",
            category: 'attendance.present',
            type: 'success',
            audience: 'student',
            data: [
                'session_id' => $sessionId,
                'module' => $moduleName,
                'url' => route('student.dashboard', [], false),
            ],
        );
    }

    /**
     * Notify a teacher that a new absence has been recorded in their session.
     */
    public function notifyTeacherAbsenceMarked(int $userId, int $sessionId, string $studentName, string $moduleName): Notification
    {
        return $this->create(
            userId: $userId,
            title: 'Absence marquée',
            message: "{$studentName} a été marqué(e) absent(e) à « {$moduleName} ».",
            category: 'attendance.teacher_absent',
            type: 'warning',
            audience: 'teacher',
            data: [
                'session_id' => $sessionId,
                'student_name' => $studentName,
                'module' => $moduleName,
                'url' => route('teacher.sessions.qr.show', $sessionId, false),
            ],
        );
    }

    /**
     * Notify an admin that a student has submitted a justification.
     */
    public function notifyAdminJustificationSubmitted(int $userId, int $justificationId, string $studentName): Notification
    {
        return $this->create(
            userId: $userId,
            title: 'Nouveau justificatif à examiner',
            message: "{$studentName} a soumis un justificatif d'absence.",
            category: 'justification.submitted',
            type: 'info',
            audience: 'admin',
            data: [
                'justification_id' => $justificationId,
                'student_name' => $studentName,
                'url' => route('admin.justifications.index', [], false),
            ],
        );
    }
}
