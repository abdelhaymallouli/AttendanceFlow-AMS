<?php

namespace App\View\Components;

use App\Services\NotificationService;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class NotificationDropdown extends Component
{
    public $notifications;
    public $unreadCount;

    public function __construct(NotificationService $notificationService)
    {
        $userId = Auth::id();
        $this->notifications = $userId ? $notificationService->getUserNotifications($userId) : collect();
        $this->unreadCount = $this->notifications->where('is_read', false)->count();
    }

    public function render()
    {
        return view('components.notification-dropdown');
    }
}