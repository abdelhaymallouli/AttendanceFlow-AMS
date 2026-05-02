@props([
    'type' => 'default', // success, warning, danger, info, default
    'text' => null,
    'alpineType' => null, // e.g. "status === 'pending' ? 'warning' : 'success'"
    'alpineText' => null // e.g. "status"
])

@php
    $colors = [
        'success' => 'bg-green-100 text-green-700 border-green-200',
        'warning' => 'bg-amber-100 text-amber-700 border-amber-200',
        'danger' => 'bg-red-100 text-red-700 border-red-200',
        'info' => 'bg-blue-100 text-blue-700 border-blue-200',
        'default' => 'bg-gray-100 text-gray-700 border-gray-200',
    ];

    $colorClass = $colors[$type] ?? $colors['default'];
@endphp

@if(isset($alpineType) || isset($alpineText))
<span {{ $attributes->merge(['class' => "px-2.5 py-1 text-xs font-medium rounded-full border inline-flex items-center gap-1.5"]) }}
      @if(isset($alpineType))
      :class="{
          '{{ $colors['success'] }}': {{ $alpineType }} === 'success' || {{ $alpineType }} === 'approved' || {{ $alpineType }} === 'present',
          '{{ $colors['warning'] }}': {{ $alpineType }} === 'warning' || {{ $alpineType }} === 'pending' || {{ $alpineType }} === 'late',
          '{{ $colors['danger'] }}': {{ $alpineType }} === 'danger' || {{ $alpineType }} === 'rejected' || {{ $alpineType }} === 'absent',
          '{{ $colors['info'] }}': {{ $alpineType }} === 'info',
          '{{ $colors['default'] }}': !['success', 'approved', 'present', 'warning', 'pending', 'late', 'danger', 'rejected', 'absent', 'info'].includes({{ $alpineType }})
      }"
      @else
      class="{{ $colorClass }}"
      @endif
      @if(isset($alpineText))
      x-text="{{ $alpineText }}"
      @endif>
    @if(!isset($alpineText))
        {{ $text ?? $slot }}
    @endif
</span>
@else
<span {{ $attributes->merge(['class' => "px-2.5 py-1 text-xs font-medium rounded-full border $colorClass inline-flex items-center gap-1.5"]) }}>
    {{ $text ?? $slot }}
</span>
@endif
