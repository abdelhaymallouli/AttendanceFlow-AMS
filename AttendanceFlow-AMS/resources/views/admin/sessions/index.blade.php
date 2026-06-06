@extends('layouts.dashboard')

@section('title', 'Academic Schedule')
@section('page_title', 'Session Schedule')

@section('header_actions')
<div class="flex items-center gap-2">
    <a href="{{ route('admin.export.sessions') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-4 rounded-lg transition-colors flex items-center text-sm shadow-sm">
        <i data-lucide="calendar" class="w-4 h-4 mr-2"></i> Export Sessions (Excel)
    </a>

    <a href="{{ route('admin.sessions.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors flex items-center text-sm shadow-sm">
        <i data-lucide="plus" class="w-4 h-4 mr-2"></i> New Session
    </a>
</div>
@endsection

@section('content')

<div class="space-y-6">

    <!-- FILTERS -->
    <!-- FILTER -->
    <x-ui.section-card padding="p-4">
        <form method="GET" action="{{ route('admin.sessions.index') }}"
              class="flex items-center justify-between gap-4">

            <div>
                <h3 class="text-sm font-semibold text-gray-800">Filter Schedule</h3>
                <p class="text-xs text-gray-500 mt-1">
                    Select a date to view sessions.
                </p>
            </div>

            <div class="flex items-center gap-2 w-full max-w-[320px]">
                <x-preline-datepicker
                    name="date"
                    :value="$date"
                    onChange="this.form.submit()"
                    icon="calendar"
                />
            </div>

        </form>
    </x-ui.section-card>
    <!-- SESSIONS LIST -->
    <x-ui.section-card :overflow="true" padding="none">

        <!-- HEADER -->
        <div class="hidden md:grid grid-cols-12 gap-4 p-4 bg-gray-50 border-b border-gray-200 font-bold text-xs text-gray-500 uppercase tracking-wider">
            <div class="col-span-2">Time</div>
            <div class="col-span-3">Module</div>
            <div class="col-span-2">Group</div>
            <div class="col-span-3">Teacher</div>
            <div class="col-span-2 text-right">Actions</div>
        </div>

        <div class="divide-y divide-gray-100">

            @forelse($sessions as $session)

                @php
                    $start = \Carbon\Carbon::parse($session->start_time);
                    $end = \Carbon\Carbon::parse($session->end_time);
                    $duration = $end->diffInHours($start);
                @endphp

                <!-- DESKTOP -->
                <div class="hidden md:grid grid-cols-12 gap-4 p-4 items-center hover:bg-gray-50 transition-colors">

                    <div class="col-span-2">
                        <p class="font-bold text-gray-800">
                            {{ $start->format('H:i') }}
                            <span class="text-gray-400 text-xs">({{ $duration }}h)</span>
                        </p>
                        <p class="text-xs text-gray-500">{{ $end->format('H:i') }}</p>
                    </div>

                    <div class="col-span-3">
                        <p class="font-medium text-gray-800">{{ $session->module->name }}</p>
                        <p class="text-xs text-blue-600 uppercase">{{ $session->type }}</p>
                    </div>

                    <div class="col-span-2">
                        <x-ui.badge color="gray">Group {{ $session->group->name }}</x-ui.badge>
                    </div>

                    <div class="col-span-3">
                        <span class="text-sm font-medium text-gray-700">
                            {{ $session->teacherProfile->user->name }}
                        </span>
                    </div>

                    <div class="col-span-2 flex justify-end gap-2">
                        <a href="{{ route('admin.attendance.show', $session->id) }}" class="text-blue-600">
                            <i data-lucide="clipboard-check"></i>
                        </a>

                        <a href="{{ route('admin.sessions.edit', $session->id) }}" class="text-gray-500">
                            <i data-lucide="edit-2"></i>
                        </a>

                        <form method="POST" action="{{ route('admin.sessions.destroy', $session->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500">
                                <i data-lucide="trash-2"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- MOBILE -->
                <div class="md:hidden p-4 hover:bg-gray-50">

                    <div class="flex justify-between mb-3">
                        <div>
                            <p class="font-bold">
                                {{ $start->format('H:i') }} - {{ $end->format('H:i') }}
                                <span class="text-xs text-gray-400">({{ $duration }}h)</span>
                            </p>
                            <p class="text-xs text-blue-600">{{ $session->type }}</p>
                        </div>

                        <a href="{{ route('admin.attendance.show', $session->id) }}" class="text-blue-600">
                            <i data-lucide="clipboard-check"></i>
                        </a>
                    </div>

                    <p class="text-sm">{{ $session->module->name }}</p>

                    <div class="flex justify-between text-sm text-gray-600 mt-2">
                        <span>{{ $session->teacherProfile->user->name }}</span>
                        <x-ui.badge color="gray">Group {{ $session->group->name }}</x-ui.badge>
                    </div>
                </div>

            @empty

                <div class="p-12 text-center bg-gray-50/30">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="calendar-x" class="w-8 h-8 text-gray-400"></i>
                    </div>


                    <h3 class="text-base font-bold text-gray-800 mb-2">
                        No sessions found
                    </h3>

                    <!-- ONLY DATE MESSAGE (FIXED) -->
                    <p class="text-sm text-gray-500 mb-6">
                        There are no academic sessions planned for
                        {{ \Carbon\Carbon::parse($date)->format('F j, Y') }}.
                    </p>

                    <a href="{{ route('admin.sessions.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-lg">
                        <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                        Create Session
                    </a>
                </div>

            @endforelse

        </div>
    </x-ui.section-card>

</div>
@endsection