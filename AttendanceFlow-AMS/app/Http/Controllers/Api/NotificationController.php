<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function getUserNotifications($userId)
    {
        return response()->json($this->notificationService->getUserNotifications($userId));
    }

    public function markAsRead($id)
    {
        if ($this->notificationService->markAsRead($id)) {
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
    }

    public function markAllAsRead()
    {
        $userId = Auth::id();
        if ($this->notificationService->markAllAsRead($userId)) {
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Failed to mark all as read'], 500);
    }

    public function clearAllNotifications()
    {
        $userId = Auth::id();
        if ($this->notificationService->clearAllNotifications($userId)) {
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Failed to clear all notifications'], 500);
    }
}
