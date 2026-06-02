<div class="hs-dropdown relative inline-flex" x-data="{ unreadCount: {{ $unreadCount }} }">
    <button id="hs-dropdown-notifications" type="button" class="hs-dropdown-toggle p-2 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50">
        <i data-lucide="bell" class="w-5 h-5"></i>
        <span x-show="unreadCount > 0" x-text="unreadCount" class="absolute -top-1 -right-1 flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full"></span>
    </button>

    <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-[300px] bg-white shadow-md rounded-lg p-2 mt-2 border border-gray-200" aria-labelledby="hs-dropdown-notifications">
        <div class="flex justify-between items-center px-4 py-2 border-b border-gray-100">
            <span class="text-sm font-semibold text-gray-800">Notifications</span>
            <div class="flex gap-2">
                <button @click="/* Call MarkAllAsRead API */" class="text-xs text-blue-600 hover:text-blue-800">Mark all read</button>
                <button @click="/* Call ClearAll API */" class="text-xs text-red-600 hover:text-red-800">Clear all</button>
            </div>
        </div>
        <div class="max-h-60 overflow-y-auto">
            @foreach($notifications as $notification)
                <x-notification-item :notification="$notification" />
            @endforeach
        </div>
    </div>
</div>