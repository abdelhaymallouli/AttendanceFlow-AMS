@props([
    'name',
    'label' => '',
    'checked' => false,
    'value' => '1',
    'onChange' => null,
    'size' => 'md',
    'disabled' => false,
    'description' => null,
])

@php
    $id = $name . '_' . \Illuminate\Support\Str::random(6);
    $boxSize = $size === 'sm' ? 'w-4 h-4' : 'w-4.5 h-4.5';
@endphp

<div class="flex items-start gap-3">
    <div class="flex items-center h-5">
        <input
            type="checkbox"
            id="{{ $id }}"
            name="{{ $name }}"
            value="{{ $value }}"
            @checked($checked)
            @if($onChange) onchange="{{ $onChange }}" @endif
            @disabled($disabled)
            class="{{ $boxSize }} shrink-0 mt-0.5 border-gray-300 rounded text-blue-600 bg-white focus:ring-2 focus:ring-blue-500/30 focus:ring-offset-0 disabled:opacity-50 disabled:pointer-events-none"
        >
    </div>
    @if($label || $description)
        <label for="{{ $id }}" class="cursor-pointer">
            @if($label)
                <span class="text-sm font-medium text-gray-800">{{ $label }}</span>
            @endif
            @if($description)
                <span class="block text-xs text-gray-500 mt-0.5">{{ $description }}</span>
            @endif
        </label>
    @endif
</div>
