@extends('layouts.dashboard')

@section('title', 'Academic Schedule')
@section('page_title', 'Session Schedule')

@section('header_actions')
<a href="{{ route('admin.sessions.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors flex items-center text-sm shadow-sm">
    <i data-lucide="plus" class="w-4 h-4 mr-2"></i> New Session
</a>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Date Filter Card -->
    <x-ui.section-card padding="p-4">
        <form method="GET" action="{{ route('admin.sessions.index') }}" class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-800">Filter Schedule</h3>
                <p class="text-xs text-gray-500 mt-1">Select a date to view the scheduled sessions.</p>
            </div>
            
            <div class="flex items-center gap-3 bg-gray-50 p-2 rounded-lg border border-gray-100">
                <label class="text-sm font-medium text-gray-700 whitespace-nowrap ml-1"><i data-lucide="calendar" class="w-4 h-4 inline-block mr-1 text-blue-600"></i> Date:</label>
                <input 
                    type="date" 
                    name="date" 
                    value="{{ $date }}"
                    onchange="this.form.submit()"
                    class="text-sm border border-gray-300 rounded-md px-3 py-1.5 focus:ring-2 focus:ring-blue-500 outline-none bg-white shadow-sm"
                >
                @if($date !== \Carbon\Carbon::today()->toDateString())
                    <a href="{{ route('admin.sessions.index') }}" class="text-xs font-medium text-blue-600 hover:text-blue-800 px-2 py-1 bg-blue-50 hover:bg-blue-100 rounded transition-colors">
                        Today
                    </a>
                @endif
            </div>
        </form>
    </x-ui.section-card>

    <!-- Sessions Timeline/Table -->
    <x-ui.section-card :overflow="true" padding="none">
        
        <!-- Table Header (Desktop) -->
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
                <div class="hidden md:grid grid-cols-12 gap-4 p-4 items-center hover:bg-gray-50 transition-colors">
                    <!-- Time -->
                    <div class="col-span-2">
                        <p class="font-bold text-gray-800">{{ $start->format('H:i') }} <span class="text-gray-400 font-normal ml-1 text-xs">({{ $duration }}h)</span></p>
                        <p class="text-xs text-gray-500">{{ $end->format('H:i') }}</p>
                    </div>
                    
                    <!-- Module -->
                    <div class="col-span-3">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center mr-3 flex-shrink-0">
                                <i data-lucide="book-open" class="w-4 h-4 text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800 truncate">{{ $session->module->name }}</p>
                                <p class="text-xs text-blue-600 font-medium uppercase tracking-wider mt-0.5">{{ $session->type }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Group -->
                    <div class="col-span-2">
                        <x-ui.badge color="gray" class="font-medium">Group {{ $session->group->name }}</x-ui.badge>
                    </div>
                    
                    <!-- Teacher -->
                    <div class="col-span-3">
                        <div class="flex items-center">
                            <i data-lucide="user" class="w-4 h-4 text-gray-400 mr-2"></i>
                            <span class="text-sm font-medium text-gray-700">{{ $session->teacherProfile->user->name }}</span>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="col-span-2 flex justify-end space-x-1">
                        <a href="{{ route('admin.attendance.show', $session->id) }}" class="text-blue-600 hover:text-blue-800 p-2 hover:bg-blue-50 rounded-lg transition-colors" title="Mark Attendance">
                            <i data-lucide="clipboard-check" class="w-5 h-5"></i>
                        </a>
                        <a href="{{ route('admin.sessions.edit', $session->id) }}" class="text-gray-500 hover:text-blue-600 p-2 hover:bg-blue-50 rounded-lg transition-colors" title="Edit Session">
                            <i data-lucide="edit-2" class="w-4 h-4"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.sessions.destroy', $session->id) }}" onsubmit="return confirm('Are you sure you want to delete this session?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-500 hover:text-red-600 p-2 hover:bg-red-50 rounded-lg transition-colors" title="Delete Session">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Mobile View -->
                <div class="md:hidden p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="font-bold text-gray-800">{{ $start->format('H:i') }} - {{ $end->format('H:i') }} <span class="text-gray-400 font-normal ml-1 text-xs">({{ $duration }}h)</span></p>
                            <p class="text-xs text-blue-600 font-medium uppercase tracking-wider mt-0.5">{{ $session->type }}</p>
                        </div>
                        <a href="{{ route('admin.attendance.show', $session->id) }}" class="text-blue-600 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition-colors">
                            <i data-lucide="clipboard-check" class="w-4 h-4"></i>
                        </a>
                    </div>
                    
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center text-gray-700">
                            <i data-lucide="book-open" class="w-4 h-4 text-gray-400 mr-2"></i>
                            <span class="font-medium">{{ $session->module->name }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-gray-600">
                                <i data-lucide="user" class="w-4 h-4 text-gray-400 mr-2"></i>
                                <span>{{ $session->teacherProfile->user->name }}</span>
                            </div>
                            <x-ui.badge color="gray" class="text-xs">Group {{ $session->group->name }}</x-ui.badge>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center bg-gray-50/30">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="calendar-x" class="w-8 h-8 text-gray-400"></i>
                    </div>
                    <h3 class="text-base font-bold text-gray-800 mb-1">No sessions scheduled</h3>
                    <p class="text-sm text-gray-500 mb-6">There are no academic sessions planned for {{ \Carbon\Carbon::parse($date)->format('F j, Y') }}.</p>
                    <a href="{{ route('admin.sessions.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-blue-700 bg-blue-100 hover:bg-blue-200 transition-colors">
                        <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                        Create First Session
                    </a>
                </div>
            @endforelse
        </div>
    </x-ui.section-card>
</div>
@endsection
