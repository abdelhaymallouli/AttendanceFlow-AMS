@props([
    'icon' => 'activity',
    'iconBg' => 'bg-blue-100',
    'iconColor' => 'text-blue-600',
    'title' => '',
    'subtitle' => '',
    'detail' => '',
    'time' => '',
    'bgClass' => 'bg-gray-50',
    'iconBgClass' => ''
])

<div class="flex items-start space-x-4 p-4 {{ $bgClass }} rounded-lg hover:bg-gray-100 transition-colors">
    <div class="w-10 h-10 {{ $iconBgClass }} rounded-full flex items-center justify-center flex-shrink-0">
        <i data-lucide="{{ $icon }}" class="w-5 h-5 {{ $iconColor }}"></i>
    </div>
    <div class="flex-1 min-w-0">
        @if($title)
            <p class="text-sm font-medium text-gray-800">{{ $title }}</p>
        @endif
        @if($subtitle)
            <p class="text-xs text-gray-500 mt-1">{{ $subtitle }}</p>
        @endif
        @if($detail)
            <p class="text-sm font-medium text-gray-800">{{ $detail }}</p>
            @if($time)
                <p class="text-xs text-gray-500 mt-1">{{ $time }}</p>
            @endif
        @elseif($time)
            <p class="text-xs text-gray-500">{{ $time }}</p>
        @endif
    </div>
</div>