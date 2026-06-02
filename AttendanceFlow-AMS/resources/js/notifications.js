document.addEventListener('alpine:init', () => {
    Alpine.data('notificationDropdown', () => ({
        unreadCount: 0,
        notifications: [],
        
        async markAllAsRead() {
            try {
                await fetch('/api/notifications/mark-all-as-read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                this.unreadCount = 0;
            } catch (error) {
                console.error('Error marking all as read:', error);
            }
        },
        
        async clearAll() {
            try {
                await fetch('/api/notifications/clear-all', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                this.notifications = [];
                this.unreadCount = 0;
            } catch (error) {
                console.error('Error clearing notifications:', error);
            }
        }
    }));
});