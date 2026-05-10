@props(['session', 'selected' => false, 'url' => '#'])

@php
    $isSelected = $selected;
    $start = \Carbon\Carbon::parse($session->start_time);
    $end = \Carbon\Carbon::parse($session->end_time);
    $time = $start->format('H:i') . ' - ' . $end->format('H:i');
    $duration = $end->diffInHours($start);
    $isToday = $start->isToday();
@endphp

<a href="{{ $url }}" 
   class="session-tab flex items-start p-4 border rounded-xl transition-all bg-white text-left hover:border-blue-400 border-gray-200">
    
    <!-- Type icon -->
    <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-3 flex-shrink-0 mt-0.5 {{ $isToday ? 'bg-blue-100' : 'bg-gray-100' }}">
        <i data-lucide="clock" class="w-5 h-5 {{ $isToday ? 'text-blue-600' : 'text-gray-500' }}"></i>
    </div>

    <!-- Session info -->
    <div class="flex-1 min-w-0">
        <!-- Time + duration -->
        <p class="font-semibold text-sm text-gray-800">
            {{ $time }}
            <span class="font-normal text-xs ml-1 text-gray-400">({{ $duration }}h)</span>
        </p>
        
        <!-- Type label -->
        <p class="text-xs mt-0.5 text-gray-500">{{ $isToday ? 'Active Session' : 'Scheduled' }}</p>
        
        <!-- Module & Group -->
        <p class="text-xs text-gray-500 truncate mt-1">
            <span>{{ $session->module->name }}</span>
            <span class="mx-1 text-gray-300">·</span>
            <span>Group {{ $session->group->name }}</span>
        </p>
        
        <!-- Teacher -->
        <p class="text-xs mt-0.5 text-gray-400">
            Teacher: {{ $session->teacherProfile->user->name }}
        </p>
    </div>
</a>
