@props([
    'icon' => 'inbox',
    'title',
    'subtitle' => null
])

<div {{ $attributes->merge(['class' => 'p-12 text-center text-gray-500 bg-white border border-gray-200 rounded-xl']) }}>
    <i data-lucide="{{ $icon }}" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
    <p class="font-medium text-gray-600">{{ $title }}</p>
    @if($subtitle)
        <p class="text-sm mt-1 text-gray-400">{{ $subtitle }}</p>
    @endif
</div>
