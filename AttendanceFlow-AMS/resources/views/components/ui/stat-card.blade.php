@props([
    'title',
    'value',
    'icon',
    'color' => 'blue', // blue, green, red, amber, purple
    'trend' => null,
    'trendColor' => null, // defaults to color if null
    'route' => null // Optional link wrapper
])

@php
    $trendColor = $trendColor ?? $color;
@endphp

@if($route)
<a href="{{ $route }}" class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-md transition-shadow cursor-pointer block group">
@else
<div class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-md transition-shadow group">
@endif

    <div class="flex items-center justify-between mb-4">
        <div class="w-12 h-12 bg-{{ $color }}-100 rounded-lg flex items-center justify-center transition-transform group-hover:scale-105">
            <i data-lucide="{{ $icon }}" class="w-6 h-6 text-{{ $color }}-600"></i>
        </div>
        
        @if($trend)
            <span class="text-xs font-medium text-{{ $trendColor }}-600 bg-{{ $trendColor }}-50 px-2 py-1 rounded-full">
                {{ $trend }}
            </span>
        @endif
    </div>
    
    @if(isset($alpineValue))
        <p class="text-2xl font-bold text-gray-800 mb-1" x-text="{{ $alpineValue }}"></p>
    @else
        <p class="text-2xl font-bold text-gray-800 mb-1">{{ $value ?? 0 }}</p>
    @endif
    <p class="text-sm text-gray-500">{{ $title }}</p>

@if($route)
</a>
@else
</div>
@endif
