@props([
    'label' => 'Date:',
    'name' => 'date',
    'value' => null,
    'onChange' => 'this.form.submit()', // Default to form submit
    'showTodayLink' => true,
    'todayUrl' => null
])

<div class="flex items-center gap-3 bg-gray-50 p-2 rounded-lg border border-gray-100">
    <label class="text-sm font-medium text-gray-700 whitespace-nowrap ml-1">
        <i data-lucide="calendar" class="w-4 h-4 inline-block mr-1 text-blue-600"></i> {{ $label }}
    </label>
    <input 
        type="date" 
        name="{{ $name }}" 
        value="{{ $value ?? old('date', \Carbon\Carbon::today()->toDateString()) }}"
        @change="{{ $onChange }}"
        class="text-sm border border-gray-300 rounded-md px-3 py-1.5 focus:ring-2 focus:ring-blue-500 outline-none bg-white shadow-sm"
    >
    @if($showTodayLink && $todayUrl)
        @if($value ?? old('date', \Carbon\Carbon::today()->toDateString()) !== \Carbon\Carbon::today()->toDateString())
            <a href="{{ $todayUrl }}" 
               class="text-xs font-medium text-blue-600 hover:text-blue-800 px-2 py-1 bg-blue-50 hover:bg-blue-100 rounded transition-colors">
                Today
            </a>
        @endif
    @endif
</div>