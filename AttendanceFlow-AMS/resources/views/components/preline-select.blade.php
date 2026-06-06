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
    $selectOptions = [
        'hasSearch' => (bool) $hasSearch,
        'placeholder' => (string) $placeholder,
        'searchPlaceholder' => (string) $placeholder,
    ];
    $selectOptionsJson = json_encode($selectOptions);
@endphp

<div class="relative inline-block w-full" @if($disabled) x-data="{ disabled: true }" @endif>
    <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-3">
        <i data-lucide="{{ $icon }}" class="w-4 h-4 text-gray-400"></i>
    </div>

    <select
        id="{{ $id }}"
        name="{{ $name }}"
        data-hs-select='{{ $selectOptionsJson }}'
        @change="{{ $onChange }}"
        @if($disabled) disabled @endif
        @if($formId) form="{{ $formId }}" @endif
        class="w-full {{ $paddingClass }} text-sm border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none bg-white shadow-sm disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400"
    >
        @if($allowBlank)
            <option value="" @selected(old($name, $value ?? '') === '' || $value === null)>
                {{ $blankText }}
            </option>
        @endif
        @foreach($options as $optValue => $optLabel)
            @if(is_array($optLabel))
                <optgroup label="{{ $optValue }}">
                    @foreach($optLabel as $subValue => $subLabel)
                        <option value="{{ $subValue }}" @selected((string) old($name, $value ?? '') === (string) $subValue)>
                            {{ $subLabel }}
                        </option>
                    @endforeach
                </optgroup>
            @else
                <option value="{{ $optValue }}" @selected((string) old($name, $value ?? '') === (string) $optValue)>
                    {{ $optLabel }}
                </option>
            @endif
        @endforeach
    </select>

    @if($label)
        <label for="{{ $id }}" class="sr-only">{{ $label }}</label>
    @endif
</div>
