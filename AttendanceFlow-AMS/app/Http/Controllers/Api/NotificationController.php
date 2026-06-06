<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct(private readonly NotificationService $notifications)
    {
    }

    /**
     * GET /api/notifications
     * Returns the latest notifications for the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $userId = Auth::id();
        $limit = (int) $request->query('limit', 30);

        $items = $this->notifications->getUserNotifications($userId, $limit)
            ->map(fn (Notification $n) => $this->serialize($n))
            ->values();

        return response()->json([
            'items' => $items,
            'unread_count' => $this->notifications->countUnread($userId),
        ]);
    }

    /**
     * GET /api/notifications/unread
     * Returns only unread notifications (used by the popup).
     */
    public function unread(Request $request): JsonResponse
    {
        $userId = Auth::id();
        $items = $this->notifications->getUnread($userId, 30)
            ->map(fn (Notification $n) => $this->serialize($n))
            ->values();

        return response()->json([
            'items' => $items,
            'unread_count' => $items->count(),
        ]);
    }

    /**
     * GET /api/notifications/unread-count
     * Cheap endpoint polled for the bell badge.
     */
    public function unreadCount(): JsonResponse
    {
        $userId = Auth::id();
        return response()->json([
            'unread_count' => $this->notifications->countUnread($userId),
        ]);
    }

    /**
     * POST /api/notifications/{id}/read
     * Mark a single notification as read. Returns its redirect URL for client navigation.
     */
    public function markAsRead(int $id): JsonResponse
    {
        $userId = Auth::id();
        $notification = Notification::forUser($userId)->find($id);

        if (! $notification) {
            return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
        }

        if (! $notification->is_read) {
            $notification->update(['is_read' => true]);
        }

        return response()->json([
            'success' => true,
            'redirect_url' => $notification->redirect_url,
        ]);
    }

    /**
     * POST /api/notifications/mark-all-as-read
     */
    public function markAllAsRead(): JsonResponse
    {
        $userId = Auth::id();
        $count = $this->notifications->markAllAsRead($userId);
        return response()->json([
            'success' => true,
            'marked' => $count,
            'unread_count' => 0,
        ]);
    }

    /**
     * POST /api/notifications/clear-all
     */
    public function clearAllNotifications(): JsonResponse
    {
        $userId = Auth::id();
        $count = $this->notifications->clearAllNotifications($userId);
        return response()->json([
            'success' => true,
            'cleared' => $count,
        ]);
    }

    private function serialize(Notification $n): array
    {
        return [
            'id' => $n->id,
            'title' => $n->title,
            'message' => $n->message,
            'type' => $n->type,
            'category' => $n->category,
            'is_read' => (bool) $n->is_read,
            'data' => $n->data ?? [],
            'redirect_url' => $n->redirect_url,
            'created_at' => $n->created_at?->toIso8601String(),
            'created_human' => $n->created_at?->diffForHumans(),
        ];
    }
}
