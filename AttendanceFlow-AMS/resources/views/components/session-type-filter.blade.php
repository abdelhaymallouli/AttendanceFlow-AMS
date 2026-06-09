@props([
    'name' => 'session_type',
    'value' => null,
    'onChange' => 'this.form.submit()',
    'label' => 'Type :',
    'icon' => 'book-open',
    'options' => [
        '' => 'Tous les types',
        'lecture' => 'Cours',
        'td' => 'TD',
        'tp' => 'TP',
    ],
    'formId' => null,
    'size' => 'md',
])

<div class="flex items-center gap-3 bg-white p-2 rounded-lg border border-gray-200">
    @if($label)
        <label for="filter-{{ $name }}" class="text-sm font-medium text-gray-700 whitespace-nowrap ml-1 flex items-center gap-1.5">
            {{ $label }}
        </label>
    @endif
    <div class="flex-1 min-w-0 max-w-[220px]">
        <x-preline-select
            :name="$name"
            :value="$value"
            :options="$options"
            :onChange="$onChange"
            :icon="$icon"
            :formId="$formId"
            :size="$size"
            :allowBlank="false"
        />
    </div>
</div>
