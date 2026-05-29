@props([
    'session',
    'selected' => false,
    'url' => '#',
    'showTeacher' => true,
    'timeOnly' => false,
    'alpine' => false // Set to true when used inside Alpine template x-for
])

@php
    $isSelected = $selected;
    $start = \Carbon\Carbon::parse($session->start_time);
    $end = \Carbon\Carbon::parse($session->end_time);
    $time = $start->format('H:i') . ' - ' . $end->format('H:i');
    $duration = $end->diffInHours($start);
    $isToday = $start->isToday();
@endphp

<a href="{{ $alpine ? '#' : $url }}" 
   @if($alpine) :href="session.url" @endif
   class="session-tab flex items-start p-4 border rounded-xl transition-all bg-white text-left hover:border-blue-400 border-gray-200 group">
   
    <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-3 flex-shrink-0 mt-0.5 {{ $isToday ? 'bg-blue-100' : 'bg-gray-100' }}">
        <i data-lucide="clock" class="w-5 h-5 {{ $isToday ? 'text-blue-600' : 'text-gray-500' }}"></i>
    </div>

    <div class="flex-1 min-w-0">
        <p class="font-semibold text-sm text-gray-800">
            @if($alpine)
                <span x-text="session.time"></span>
                @if(!$timeOnly)
                    <span class="font-normal text-xs ml-1 text-gray-400" x-text="'(' + session.duration + 'h)'"></span>
                @endif
            @else
                {{ $time }}
                @if(!$timeOnly)
                    <span class="font-normal text-xs ml-1 text-gray-400">({{ $duration }}h)</span>
                @endif
            @endif
        </p>
        
        <p class="text-xs mt-0.5 text-gray-500">{{ $isToday ? 'Active Session' : 'Scheduled' }}</p>
        
        <p class="text-xs text-gray-500 truncate mt-1">
            @if($alpine)
                <span class="font-medium text-gray-700" x-text="session.module"></span>
                <span class="mx-1 text-gray-300">·</span>
                <span x-text="'Group ' + session.group"></span>
            @else
                <span class="font-medium text-gray-700">{{ $session->moduleName ?? $session->module->name }}</span>
                <span class="mx-1 text-gray-300">·</span>
                <span>Group {{ $session->groupName ?? $session->group->name }}</span>
            @endif
        </p>
        
        @if($showTeacher)
            <p class="text-xs mt-0.5 text-gray-400 flex items-center">
                <i data-lucide="user" class="w-3 h-3 mr-1 opacity-70"></i>
                @if($alpine)
                    <span x-text="session.teacher"></span>
                @else
                    <span>{{ $session->teacher ?? $session->teacherProfile->user->name ?? '' }}</span>
                @endif
            </p>
        @endif
    </div>
</a>