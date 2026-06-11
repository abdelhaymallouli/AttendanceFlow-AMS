@props([
    'name' => 'group_id',
    'value' => null,
    'onChange' => 'this.form.submit()',
    'label' => 'Groupe :',
    'icon' => 'users',
    'includeBlank' => true,
    'blankText' => 'Tous les groupes',
    'formId' => null,
    'size' => 'md',
])

@php
    $options = \App\Models\Group::orderBy('name')->get()
        ->mapWithKeys(fn ($g) => [(string) $g->id => $g->name])
        ->toArray();
@endphp

<div class="flex items-center gap-3 bg-white p-2 rounded-lg border border-gray-200">
    @if($label)
        <label for="filter-{{ $name }}" class="text-sm font-medium text-gray-700 whitespace-nowrap ml-1 flex items-center gap-1.5">
            {{ $label }}
        </label>
    @endif
    <div class="flex-1 min-w-0 max-w-[240px]">
        <select name="{{ $name }}" onchange="{{ $onChange }}" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            @if($includeBlank)
                <option value="">{{ $blankText }}</option>
            @endif
            @foreach($options as $optValue => $optLabel)
                <option value="{{ $optValue }}" @selected($value == $optValue)>{{ $optLabel }}</option>
            @endforeach
        </select>
    </div>
</div>
