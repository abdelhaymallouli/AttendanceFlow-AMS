@extends('layouts.dashboard')

@section('title', 'New Session')
@section('page_title', 'Create Academic Session')

@section('header_actions')
<a href="{{ route('teacher.dashboard') }}" class="text-gray-500 hover:text-blue-600 transition-colors flex items-center">
    <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i> Back to Dashboard
</a>
@endsection

@section('content')
<div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden" x-data="sessionForm('{{ old('start_time', '09:00') }}', '{{ old('end_time', '11:00') }}')">
        <div class="p-6 border-b border-gray-200 bg-gray-50/50">
            <h3 class="text-lg font-semibold text-gray-800">Session Configuration</h3>
            <p class="text-sm text-gray-500">Choose your module, group, and schedule for this session.</p>
        </div>
        
        <form method="POST" action="{{ route('teacher.sessions.store') }}" class="p-6 md:p-8 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Module Selection (filtered by teacher's assignments) -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Module</label>
                    <x-preline-select
                        name="module_id"
                        :value="old('module_id')"
                        :options="$modules->mapWithKeys(fn($m) => [(string) $m->id => $m->name])->toArray()"
                        icon="book-open"
                        placeholder="Choisir un module..."
                        :allowBlank="false"
                    />
                    @error('module_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- Group Selection (filtered by teacher's assignments) -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Group</label>
                    <x-preline-select
                        name="group_id"
                        :value="old('group_id')"
                        :options="$groups->mapWithKeys(fn($g) => [(string) $g->id => $g->name])->toArray()"
                        icon="users"
                        placeholder="Choisir un groupe..."
                        :allowBlank="false"
                    />
                    @error('group_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- Session Type -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Session Type</label>
                    <x-preline-select
                        name="type"
                        :value="old('type')"
                        :options="['lecture' => 'Lecture', 'td' => 'TD (Travaux Dirigés)', 'tp' => 'TP (Travaux Pratiques)']"
                        icon="book-open"
                        placeholder="Choisir un type..."
                        :allowBlank="false"
                    />
                    @error('type') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <hr class="border-gray-100">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Date -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Date</label>
                    <x-preline-datepicker
                        name="date"
                        :value="old('date', \Carbon\Carbon::today()->toDateString())"
                        icon="calendar"
                        placeholder="Choisir une date..."
                    />
                    @error('date') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- Start Time -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Start Time</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-3 z-10">
                            <i data-lucide="clock" class="w-4 h-4 text-gray-400"></i>
                        </div>
                        <input type="time" x-model="startTime" name="start_time" required class="w-full ps-10 pe-4 py-3 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none bg-white shadow-sm">
                    </div>
                    @error('start_time') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- End Time -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">End Time</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-3 z-10">
                            <i data-lucide="clock" class="w-4 h-4 text-gray-400"></i>
                        </div>
                        <input type="time" x-model="endTime" name="end_time" required class="w-full ps-10 pe-4 py-3 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none bg-white shadow-sm">
                    </div>
                    @error('end_time') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>
            
            <!-- Computed Duration -->
            <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100 flex items-center justify-between">
                <span class="text-sm font-medium text-gray-700">Calculated Duration:</span>
                <span class="font-bold text-blue-700" x-text="durationText"></span>
            </div>
            
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('teacher.dashboard') }}" class="px-6 py-3 rounded-xl text-gray-700 font-medium hover:bg-gray-100 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-8 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 hover:shadow-lg active:scale-95 transition-all flex items-center">
                    <i data-lucide="calendar-plus" class="w-5 h-5 mr-2"></i>
                    Create Session
                </button>
            </div>
        </form>
    </div>

@endsection