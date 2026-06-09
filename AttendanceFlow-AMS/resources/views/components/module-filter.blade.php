@props([
    'name' => 'module_id',
    'value' => null,
    'onChange' => 'this.form.submit()',
    'label' => 'Module :',
    'icon' => 'book-open',
    'includeBlank' => true,
    'blankText' => 'Tous les modules',
    'formId' => null,
    'size' => 'md',
])

@php
    $options = \App\Models\Module::orderBy('name')->get()
        ->mapWithKeys(fn ($m) => [(string) $m->id => $m->name])
        ->toArray();
@endphp

<div class="flex items-center gap-3 bg-white p-2 rounded-lg border border-gray-200">
    @if($label)
        <label for="filter-{{ $name }}" class="text-sm font-medium text-gray-700 whitespace-nowrap ml-1 flex items-center gap-1.5">
            {{ $label }}
        </label>
    @endif
    <div class="flex-1 min-w-0 max-w-[260px]">
        <x-preline-select
            :name="$name"
            :value="$value"
            :options="$options"
            :onChange="$onChange"
            :icon="$icon"
            :includeBlank="$includeBlank"
            :blankText="$blankText"
            :formId="$formId"
            :size="$size"
        />
    </div>
</div>
