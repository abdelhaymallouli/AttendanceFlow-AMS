@props([
    'title',
    'value',
    'icon',
    'color' => 'blue', // blue, green, red, amber, purple, indigo, pink
    'trend' => null,
    'trendColor' => null, // defaults to color if null
    'route' => null, // Optional link wrapper
    'alpineValue' => null // Alpine expression for dynamic value
])

@php
    // Define color mappings for proper Tailwind classes
    $colorMap = [
        'blue' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'border' => 'border-blue-200'],
        'green' => ['bg' => 'bg-green-50', 'text' => 'text-green-600', 'border' => 'border-green-200'],
        'red' => ['bg' => 'bg-red-50', 'text' => 'text-red-600', 'border' => 'border-red-200'],
        'amber' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'border' => 'border-amber-200'],
        'purple' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'border' => 'border-purple-200'],
        'indigo' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-600', 'border' => 'border-indigo-200'],
        'pink' => ['bg' => 'bg-pink-50', 'text' => 'text-pink-600', 'border' => 'border-pink-200'],
    ];
    
    $colorData = $colorMap[$color] ?? $colorMap['blue'];
    $trendColorData = $colorMap[$trendColor ?? $color] ?? $colorData;
    
    // For the icon background, we need the lighter version
    $iconBgClass = $colorData['bg'];
    $iconTextClass = $colorData['text'];
    $trendBgClass = $trendColorData['bg'];
    $trendTextClass = $trendColorData['text'];
@endphp

@if($route)
<a href="{{ $route }}" class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-md transition-shadow cursor-pointer block group">
@else
<div class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-md transition-shadow group">
@endif

    <div class="flex items-center justify-between mb-4">
        <div class="w-12 h-12 {{ $iconBgClass }} rounded-lg flex items-center justify-center transition-transform group-hover:scale-105">
            <i data-lucide="{{ $icon }}" class="w-6 h-6 {{ $iconTextClass }}"></i>
        </div>
        
        @if($trend)
            <span class="text-xs font-medium {{ $trendTextClass }} {{ $trendBgClass }} px-2 py-1 rounded-full">
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