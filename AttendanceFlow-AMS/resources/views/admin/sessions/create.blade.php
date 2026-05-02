@extends('layouts.dashboard')

@section('title', 'Add Session')
@section('page_title', 'Add Academic Session')

@section('header_actions')
<a href="{{ route('admin.sessions.index') }}" class="text-gray-500 hover:text-blue-600 transition-colors flex items-center">
    <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i> Back to Schedule
</a>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden" x-data="sessionForm()">
        <div class="p-6 border-b border-gray-200 bg-gray-50/50">
            <h3 class="text-lg font-semibold text-gray-800">Session Configuration</h3>
            <p class="text-sm text-gray-500">Define the module, teacher, and schedule for this session.</p>
        </div>
        
        <form method="POST" action="{{ route('admin.sessions.store') }}" class="p-6 md:p-8 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Module Selection -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Module</label>
                    <select name="module_id" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-blue-100 focus:border-blue-600 transition-all outline-none bg-white">
                        <option value="">Select a Module</option>
                        @foreach($modules as $module)
                            <option value="{{ $module->id }}">{{ $module->name }}</option>
                        @endforeach
                    </select>
                    @error('module_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- Teacher Selection -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Teacher</label>
                    <select name="teacher_id" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-blue-100 focus:border-blue-600 transition-all outline-none bg-white">
                        <option value="">Select a Teacher</option>
                        @foreach($teacherProfiles as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->user->name }} ({{ $teacher->specialty }})</option>
                        @endforeach
                    </select>
                    @error('teacher_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- Group Selection -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Group</label>
                    <select name="group_id" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-blue-100 focus:border-blue-600 transition-all outline-none bg-white">
                        <option value="">Select a Group</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                    @error('group_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- Session Type -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Session Type</label>
                    <select name="type" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-blue-100 focus:border-blue-600 transition-all outline-none bg-white">
                        <option value="lecture">Lecture</option>
                        <option value="td">TD (Travaux Dirigés)</option>
                        <option value="tp">TP (Travaux Pratiques)</option>
                    </select>
                    @error('type') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <hr class="border-gray-100">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Date -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Date</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="calendar" class="w-4 h-4 text-gray-400"></i>
                        </div>
                        <input type="date" name="date" required value="{{ old('date', \Carbon\Carbon::today()->toDateString()) }}" class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-blue-100 focus:border-blue-600 transition-all outline-none">
                    </div>
                    @error('date') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                
                <!-- Start Time -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Start Time</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="clock" class="w-4 h-4 text-gray-400"></i>
                        </div>
                        <input type="time" x-model="startTime" name="start_time" required class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-blue-100 focus:border-blue-600 transition-all outline-none">
                    </div>
                    @error('start_time') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- End Time -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">End Time</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="clock" class="w-4 h-4 text-gray-400"></i>
                        </div>
                        <input type="time" x-model="endTime" name="end_time" required class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-blue-100 focus:border-blue-600 transition-all outline-none">
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
                <a href="{{ route('admin.sessions.index') }}" class="px-6 py-3 rounded-xl text-gray-700 font-medium hover:bg-gray-100 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-8 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 hover:shadow-lg active:scale-95 transition-all flex items-center">
                    <i data-lucide="save" class="w-5 h-5 mr-2"></i>
                    Create Session
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('sessionForm', () => ({
            startTime: '{{ old('start_time', '09:00') }}',
            endTime: '{{ old('end_time', '11:00') }}',
            
            get durationText() {
                if (!this.startTime || !this.endTime) return '--';
                
                const start = this.startTime.split(':');
                const end = this.endTime.split(':');
                
                const startMins = parseInt(start[0]) * 60 + parseInt(start[1]);
                const endMins = parseInt(end[0]) * 60 + parseInt(end[1]);
                
                const diff = endMins - startMins;
                
                if (diff <= 0) return 'Invalid Time';
                
                const hours = Math.floor(diff / 60);
                const mins = diff % 60;
                
                if (mins === 0) return `${hours} Hour${hours > 1 ? 's' : ''}`;
                return `${hours}h ${mins}m`;
            }
        }));
    });
</script>
@endpush
@endsection
