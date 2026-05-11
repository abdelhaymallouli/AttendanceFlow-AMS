@extends('layouts.dashboard')

@section('title', 'Attendance Entry')
@section('page_title', 'Session Selection')

@section('content')
<div class="space-y-6" x-data="attendanceApp(initialSessions, '{{ $date }}')">
    
    <!-- Step 1: Date + Session Selector -->
    <x-ui.section-card padding="p-4" class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <!-- Step label -->
            <div>
                <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs flex items-center justify-center font-bold shadow-sm shadow-blue-500/30">1</span>
                    Select Date &amp; Session
                </h3>
                <p class="text-xs text-gray-500 mt-1 ml-8">Sessions are filtered by the selected date</p>
            </div>

            <!-- Date Input -->
            <x-date-filter 
                label="Date:"
                name="selectedDate"
                value="{{ $date }}"
                onChange="window.location.href = '?date=' + this.selectedDate"
            />
        </div>

        <!-- Session Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <template x-for="session in availableSessions" :key="session.id">
                <a :href="session.url" class="session-tab flex items-start p-4 border rounded-xl transition-all bg-white text-left hover:border-blue-400 hover:shadow-md border-gray-200 group">
                    
                    <!-- Type icon -->
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-3 flex-shrink-0 mt-0.5 bg-blue-50 group-hover:bg-blue-600 transition-colors">
                        <i data-lucide="clock" class="w-5 h-5 text-blue-600 group-hover:text-white transition-colors"></i>
                    </div>

                    <!-- Session info -->
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-sm text-gray-800">
                            <span x-text="session.time"></span>
                            <span class="font-normal text-xs ml-1 text-gray-400" x-text="'(' + session.duration + 'h)'"></span>
                        </p>
                        
                        <p class="text-xs text-gray-500 truncate mt-1">
                            <span class="font-medium text-gray-700" x-text="session.module"></span>
                            <span class="mx-1 text-gray-300">·</span>
                            <span x-text="'Group ' + session.group"></span>
                        </p>
                        
                        <!-- Teacher -->
                        <p class="text-xs mt-1.5 text-gray-500 flex items-center">
                            <i data-lucide="user" class="w-3 h-3 mr-1 opacity-70"></i>
                            <span x-text="session.teacher"></span>
                        </p>
                    </div>
                </a>
            </template>
        </div>

        <!-- No sessions state -->
        <div x-show="availableSessions.length === 0" style="display: none;">
            <x-ui.empty-state 
                icon="calendar-x"
                title="No sessions scheduled for this date"
                class="border-dashed"
            />
        </div>
    </x-ui.section-card>
</div>

@push('scripts')
@php
    $allSessionsData = $sessions->map(function($session) {
        $start = \Carbon\Carbon::parse($session->start_time);
        $end = \Carbon\Carbon::parse($session->end_time);
        return [
            'id' => $session->id,
            'start_time' => $start->format('Y-m-d H:i:s'),
            'time' => $start->format('H:i') . ' - ' . $end->format('H:i'),
            'duration' => $end->diffInHours($start),
            'module' => $session->module->name,
            'group' => $session->group->name,
            'teacher' => $session->teacherProfile->user->name,
            'url' => route('admin.attendance.show', $session->id)
        ];
    })->values();
@endphp

<script>
    const initialSessions = @json($allSessionsData);
</script>
@endpush
@endsection