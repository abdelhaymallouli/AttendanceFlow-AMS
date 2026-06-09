@props([
    'name' => 'date',
    'value' => null,
    'onChange' => 'this.form.submit()',
    'label' => 'Date :',
    'icon' => null,
    'showTodayLink' => true,
    'todayUrl' => null,
    'minDate' => null,
    'maxDate' => null,
    'placeholder' => 'Sélectionner une date',
    'formId' => null,
    'size' => 'md',
])

@php
    $resolvedValue = $value ?? old('date', request('date', \Carbon\Carbon::today()->toDateString()));
    $resolvedToday = \Carbon\Carbon::today()->toDateString();
@endphp

<div class="flex items-center gap-3 bg-white p-2 rounded-lg border border-gray-200">
    @if($label)
        <label class="text-sm font-medium text-gray-700 whitespace-nowrap ml-1 flex items-center gap-1.5">
            {{ $label }}
        </label>
    @endif

    <div class="flex-1 min-w-0 max-w-[200px]">
        <x-preline-datepicker
            :name="$name"
            :value="$resolvedValue"
            :onChange="$onChange"
            :icon="$icon"
            :minDate="$minDate"
            :maxDate="$maxDate"
            :placeholder="$placeholder"
            :formId="$formId"
            :size="$size"
        />
    </div>

    @if($showTodayLink && $todayUrl)
        @if($resolvedValue !== $resolvedToday)
            <a href="{{ $todayUrl }}"
               class="inline-flex items-center gap-1 text-xs font-bold uppercase tracking-wider px-3 py-2 text-blue-600 hover:bg-blue-50 border border-blue-200 rounded-lg transition-colors">
                <i data-lucide="rotate-ccw" class="w-3 h-3"></i>
                Aujourd'hui
            </a>
        @endif
    @endif
</div>
