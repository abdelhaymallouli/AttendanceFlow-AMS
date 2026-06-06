<?php

namespace App\Http\Controllers\Web;

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
     * GET /web/notifications
     * Session-auth endpoint used by the bell + popup in the dashboard layout.
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

    public function unread(): JsonResponse
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

    public function unreadCount(): JsonResponse
    {
        $userId = Auth::id();
        return response()->json([
            'unread_count' => $this->notifications->countUnread($userId),
        ]);
    }

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

    public function clearAll(): JsonResponse
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
