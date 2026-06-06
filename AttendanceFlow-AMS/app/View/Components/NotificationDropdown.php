<?php

namespace App\View\Components;

use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class NotificationDropdown extends Component
{
    public int $unreadCount;
    public $notifications;

    public function __construct(NotificationService $notifications)
    {
        $userId = Auth::id();
        $this->notifications = $userId
            ? $notifications->getUserNotifications($userId, 30)
            : collect();
        $this->unreadCount = $this->notifications->where('is_read', false)->count();
    }

    public function render()
    {
        return view('components.notification-dropdown');
    }
}
