@props(['notification'])

<div class="flex items-start gap-4 p-4 hover:bg-gray-50 transition-colors">
    <div class="flex-shrink-0 mt-1">
        @if($notification->type === 'success')
            <i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i>
        @elseif($notification->type === 'danger')
            <i data-lucide="alert-circle" class="w-5 h-5 text-red-500"></i>
        @else
            <i data-lucide="info" class="w-5 h-5 text-blue-500"></i>
        @endif
    </div>
    <div class="flex-grow">
        <p class="text-sm font-medium text-gray-900">{{ $notification->title }}</p>
        <p class="text-xs text-gray-500">{{ $notification->message }}</p>
        <p class="text-[10px] text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
    </div>
</div>