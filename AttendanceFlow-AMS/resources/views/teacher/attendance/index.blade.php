@extends('layouts.dashboard')

@section('title', 'Attendance Entry')
@section('page_title', 'Session Selection')

@section('content')
<div class="space-y-6">
    <!-- Step 1: Date + Session Selector -->
    <x-ui.section-card padding="p-4" class="mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs flex items-center justify-center font-bold shadow-sm shadow-blue-500/30">1</span>
                    Select Date &amp; Session
                </h3>
                <p class="text-xs text-gray-500 mt-1 ml-8">Sessions are filtered by the selected date</p>
            </div>

            <x-date-filter 
                label="Date:"
                name="selectedDate"
                value="{{ $date }}"
                onChange="window.location.href = '?date=' + this.selectedDate"
                showTodayLink="true"
                todayUrl="{{ route('teacher.attendance.index') }}"
            />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($sessions as $session)
                <x-session-card 
                    :session="$session"
                    :url="route('teacher.sessions.attendance.show', $session->id)"
                    showTeacher="false"
                />
            @empty
                <div class="col-span-full">
                    <x-ui.empty-state 
                        icon="calendar-x"
                        title="No sessions scheduled for this date"
                        class="border-dashed"
                    />
                </div>
            @endforelse
        </div>
    </x-ui.section-card>
</div>