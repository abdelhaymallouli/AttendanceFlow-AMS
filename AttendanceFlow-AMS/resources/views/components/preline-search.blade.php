@props([
    'name' => 'search',
    'value' => null,
    'onChange' => null,
    'placeholder' => 'Rechercher...',
    'icon' => 'search',
    'formId' => null,
    'size' => 'md',
])

@php
    $id = 'search_' . \Illuminate\Support\Str::random(6);
    $paddingClass = $size === 'sm' ? 'py-2 pe-8 ps-10' : 'py-2.5 pe-9 ps-10';
    $resolvedValue = $value ?? old('search', request($name, ''));
@endphp

<div class="relative w-full">
    <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-3">
        <i data-lucide="{{ $icon }}" class="w-4 h-4 text-gray-400"></i>
    </div>

    <input
        type="search"
        id="{{ $id }}"
        name="{{ $name }}"
        value="{{ $resolvedValue }}"
        placeholder="{{ $placeholder }}"
        @if($onChange) oninput="{{ $onChange }}" @endif
        @if($formId) form="{{ $formId }}" @endif
        class="w-full {{ $paddingClass }} text-sm border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none bg-white shadow-sm dark:bg-neutral-900 dark:border-neutral-700"
    >
</div>
