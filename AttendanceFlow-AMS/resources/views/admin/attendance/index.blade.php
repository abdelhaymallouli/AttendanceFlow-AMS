@extends('layouts.dashboard')

@section('title', 'Attendance Entry')
@section('page_title', 'Session Selection')

@section('content')

<div class="space-y-6"
     x-data="attendanceApp(@js($allSessionsData), '{{ $date }}')">

    <!-- DATE FILTER ONLY -->
    <x-ui.section-card padding="p-4" class="mb-6">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">

            <div>
                <h3 class="text-sm font-semibold text-gray-800">
                    Select Date
                </h3>
            </div>

            <input type="date"
                   value="{{ $date }}"
                   @change="onDateChange($event.target.value)"
                   class="text-sm border border-gray-300 rounded-md px-3 py-1.5"
            >
        </div>

    </x-ui.section-card>

    <!-- SESSIONS -->
    <x-ui.section-card padding="p-4">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

            <template x-for="session in availableSessions" :key="session.id">

                <a :href="session.url"
                   class="p-4 border rounded-lg bg-white hover:shadow-md">

                    <div class="text-sm font-semibold">
                        <span x-text="session.time"></span>
                        <span class="text-xs text-gray-400"
                              x-text="'(' + session.duration + 'h)'"></span>
                    </div>

                    <div class="text-xs text-gray-500 mt-1">
                        <span x-text="session.module"></span>
                        ·
                        <span x-text="session.group"></span>
                    </div>

                    <div class="text-xs mt-2 text-gray-400">
                        <span x-text="session.teacher"></span>
                    </div>

                </a>

            </template>

        </div>

        <!-- EMPTY -->
        <div x-show="availableSessions.length === 0"
             class="text-center text-gray-500 py-10">
            No sessions for this date
        </div>

    </x-ui.section-card>

</div>

@endsection