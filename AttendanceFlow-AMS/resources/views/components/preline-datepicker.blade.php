@props([
    'name' => 'date',
    'value' => null,
    'onChange' => 'this.form.submit()',
    'icon' => 'calendar',
    'minDate' => null,
    'maxDate' => null,
    'placeholder' => 'Sélectionner une date',
    'formId' => null,
    'size' => 'md',
])

@php
    $id = 'date_' . \Illuminate\Support\Str::random(6);
    $resolvedValue = $value ?? old('date', request($name, \Carbon\Carbon::today()->toDateString()));

    $paddingClass = $size === 'sm' ? 'py-2 pe-3 ps-10' : 'py-2.5 pe-3 ps-10';
    $minAttr = !empty($minDate) ? 'min=' . e($minDate) : '';
    $maxAttr = !empty($maxDate) ? 'max=' . e($maxDate) : '';
@endphp

<div class="relative w-full">
    <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-3 z-10">
        <i data-lucide="{{ $icon }}" class="w-4 h-4 text-gray-400"></i>
    </div>

    <input
        type="date"
        id="{{ $id }}"
        name="{{ $name }}"
        value="{{ $resolvedValue }}"
        onchange="{{ $onChange }}"
        placeholder="{{ $placeholder }}"
        @if($formId) form="{{ $formId }}" @endif
        {!! $minAttr !!}
        {!! $maxAttr !!}
        class="w-full {{ $paddingClass }} text-sm text-gray-900 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none bg-white shadow-sm [&::-webkit-calendar-picker-indicator]:cursor-pointer [&::-webkit-calendar-picker-indicator]:opacity-60 [&::-webkit-calendar-picker-indicator]:hover:opacity-100"
    >
</div>
