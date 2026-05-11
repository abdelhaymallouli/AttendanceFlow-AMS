@props([
    'title' => 'Quick Actions',
    'actions' => [] // Array of ['href' => ..., 'icon' => ..., 'label' => ..., 'color' => 'blue']
])

<h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
    <i data-lucide="zap" class="w-5 h-5 mr-2 text-blue-600"></i>
    {{ $title }}
</h3>
<div class="space-y-3">
    @foreach($actions as $action)
        <a href="{{ $action['href'] ?? '#' }}"
           class="block w-full {{ $action['color'] ?? 'blue' }}-600 hover:{{ $action['color'] ?? 'blue' }}-700 text-white font-medium py-3 px-4 rounded-lg transition-colors flex items-center justify-center shadow-sm">
            <i data-lucide="{{ $action['icon'] ?? 'plus-circle' }}" class="w-5 h-5 mr-2"></i>
            {{ $action['label'] }}
        </a>
    @endforeach
</div>