<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Support\Collection;

class NotificationService extends BaseService
{
    /**
     * Get user notifications.
     */
    public function getUserNotifications(int $userId, int $limit = 20): Collection
    {
        return Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(int $id): bool
    {
        $notification = Notification::find($id);
        if ($notification) {
            return $notification->update(['is_read' => true]);
        }
        return false;
    }

    /**
     * Mark all unread notifications for a user as read.
     */
    public function markAllAsRead(int $userId): bool
    {
        return Notification::where('user_id', $userId)->where('is_read', false)->update(['is_read' => true]);
    }

    /**
     * Clear all notifications for a user.
     */
    public function clearAllNotifications(int $userId): bool
    {
        return Notification::where('user_id', $userId)->delete();
    }
}