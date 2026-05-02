@props([
    'title' => null,
    'icon' => null,
    'padding' => 'p-6',     // p-4, p-6, none
    'overflow' => false,    // adds overflow-hidden for tables
])

<div {{ $attributes->merge(['class' => 'bg-white border border-gray-200 rounded-xl shadow-sm' . ($overflow ? ' overflow-hidden' : '')]) }}>
    @if($title)
        <div class="flex items-center justify-between {{ $padding }} border-b border-gray-200">
            <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                @if($icon)
                    <i data-lucide="{{ $icon }}" class="w-5 h-5 text-blue-600"></i>
                @endif
                {{ $title }}
            </h3>
            @if(isset($actions))
                <div class="flex items-center gap-2">{{ $actions }}</div>
            @endif
        </div>
        <div class="{{ $padding === 'none' ? '' : $padding }}">
            {{ $slot }}
        </div>
    @else
        <div class="{{ $padding === 'none' ? '' : $padding }}">
            {{ $slot }}
        </div>
    @endif
</div>
