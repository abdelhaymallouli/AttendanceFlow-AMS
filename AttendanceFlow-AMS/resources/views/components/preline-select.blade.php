@props([
    'name',
    'value' => null,
    'options' => [],
    'onChange' => 'this.form.submit()',
    'icon' => 'list',
    'label' => null,
    'placeholder' => 'Sélectionner...',
    'hasSearch' => false,
    'allowBlank' => true,
    'blankText' => 'Tous',
    'disabled' => false,
    'formId' => null,
    'size' => 'md',
])

@php
    $id = $name . '_' . \Illuminate\Support\Str::random(6);
    $paddingClass = $size === 'sm' ? 'py-2 pe-8 ps-10' : 'py-2.5 pe-9 ps-10';
    $resolvedValue = old($name, $value);
    $searchFlag = $hasSearch ? 'true' : 'false';
@endphp

<div class="relative w-full">
    <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-3 z-10">
        <i data-lucide="{{ $icon }}" class="w-4 h-4 text-gray-400"></i>
    </div>

    <select
        id="{{ $id }}"
        name="{{ $name }}"
        data-hs-select='{"hasSearch": {{ $searchFlag }}, "placeholder": "{{ $placeholder }}", "searchPlaceholder": "{{ $placeholder }}"}'
        @change="{{ $onChange }}"
        @if($disabled) disabled @endif
        @if($formId) form="{{ $formId }}" @endif
        class="w-full {{ $paddingClass }} text-sm text-gray-900 placeholder-gray-400 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none bg-white shadow-sm disabled:opacity-50 disabled:pointer-events-none appearance-none"
        style="background-image: none;"
    >
        @if($allowBlank)
            <option value="" @selected($resolvedValue === '' || $resolvedValue === null)>
                {{ $blankText }}
            </option>
        @endif
        @foreach($options as $optValue => $optLabel)
            @if(is_array($optLabel))
                <optgroup label="{{ $optValue }}">
                    @foreach($optLabel as $subValue => $subLabel)
                        <option value="{{ $subValue }}" @selected((string) $resolvedValue === (string) $subValue)>
                            {{ $subLabel }}
                        </option>
                    @endforeach
                </optgroup>
            @else
                <option value="{{ $optValue }}" @selected((string) $resolvedValue === (string) $optValue)>
                    {{ $optLabel }}
                </option>
            @endif
        @endforeach
    </select>

    <div class="absolute inset-y-0 end-0 flex items-center pointer-events-none pe-3">
        <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
    </div>

    @if($label)
        <label for="{{ $id }}" class="sr-only">{{ $label }}</label>
    @endif
</div>
