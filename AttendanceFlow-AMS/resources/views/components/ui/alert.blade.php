@props([
    'type' => 'success', // success, error, warning, info
    'title' => null,
    'message' => null,
    'showIcon' => true,
    'dismissable' => true
])

@php
    $classes = [
        'success' => 'bg-green-50 border border-green-200 text-green-800',
        'error' => 'bg-red-50 border border-red-200 text-red-800',
        'warning' => 'bg-amber-50 border border-amber-200 text-amber-800',
        'info' => 'bg-blue-50 border border-blue-200 text-blue-800',
    ];
    
    $icons = [
        'success' => 'check-circle',
        'error' => 'alert-circle',
        'warning' => 'alert-triangle',
        'info' => 'info',
    ];
    
    $bgClass = $classes[$type] ?? $classes['success'];
    $icon = $icons[$type] ?? $icons['success'];
@endphp

<div {{ $attributes->merge(['class' => 'mb-6 p-4 flex items-center justify-between rounded-xl shadow-sm ' . $bgClass]) }}>
    @if($showIcon)
        <div class="flex items-center">
            <div class="bg-[${{str_replace('bg-', '', explode(' ', $bgClass)[0])}}]/100 p-2 rounded-lg mr-3">
                <i data-lucide="{{ $icon }}" class="w-5 h-5 text-[${{str_replace('text-', '', explode(' ', $bgClass)[1])}}]"></i>
            </div>
            @if($title)
                <p class="text-sm font-medium">{{ $title }}</p>
            @endif
            @if($message)
                <p class="text-sm">{{ $message }}</p>
            @endif
        </div>
    @else
        @if($title)
            <p class="text-sm font-medium">{{ $title }}</p>
        @endif
        @if($message)
            <p class="text-sm">{{ $message }}</p>
        @endif
    @endif
    
    @if($dismissable)
        <button @click="$el.remove()" 
                class="text-[${{str_replace('text-', '', explode(' ', $bgClass)[1])}}] hover:text-[${{str_replace('text-', '', explode(' ', $bgClass)[1])}}]/80 transition-colors p-1 hover:bg-[${{str_replace('bg-', '', explode(' ', $bgClass)[0])}}]/200 rounded-md">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
    @endif
</div>