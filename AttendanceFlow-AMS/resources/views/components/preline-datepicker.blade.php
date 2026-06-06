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
    $resolvedValue = $value ?? old('date', \Carbon\Carbon::today()->toDateString());

    $datepickerOptions = ['format' => 'yyyy-mm-dd'];
    if (!empty($minDate)) {
        $datepickerOptions['minDate'] = $minDate;
    }
    if (!empty($maxDate)) {
        $datepickerOptions['maxDate'] = $maxDate;
    }
    $datepickerJson = json_encode($datepickerOptions);

    $paddingClass = $size === 'sm' ? 'py-2 pe-8 ps-10' : 'py-2.5 pe-9 ps-10';
@endphp

<div class="relative w-full">
    <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-3">
        <i data-lucide="{{ $icon }}" class="w-4 h-4 text-gray-400"></i>
    </div>

    <input
        type="text"
        id="{{ $id }}"
        name="{{ $name }}"
        value="{{ $resolvedValue }}"
        data-hs-datepicker="{{ $datepickerJson }}"
        onchange="{{ $onChange }}"
        placeholder="{{ $placeholder }}"
        @if($formId) form="{{ $formId }}" @endif
        class="w-full {{ $paddingClass }} text-sm border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none bg-white shadow-sm dark:bg-neutral-900 dark:border-neutral-700"
    >
</div>
