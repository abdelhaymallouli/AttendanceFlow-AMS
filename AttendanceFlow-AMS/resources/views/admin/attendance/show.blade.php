@extends('layouts.dashboard')

@section('title', 'Attendance Entry')
@section('page_title', 'Mark Attendance')

@section('content')
<div class="space-y-6" x-data="{ 
    searchQuery: '',
    stats: { present: 0, absent: 0, late: 0, unmarked: {{ $students->count() }} },
    updateStats() {
        const statuses = Array.from(document.querySelectorAll('input[type=radio]:checked')).map(r => r.value);
        this.stats.present = statuses.filter(s => s === 'present').length;
        this.stats.absent = statuses.filter(s => s === 'absent').length;
        this.stats.late = statuses.filter(s => s === 'late').length;
        this.stats.unmarked = {{ $students->count() }} - statuses.length;
    },
    markAllPresent() {
        document.querySelectorAll('input[type=radio][value=present]').forEach(el => el.checked = true);
        this.updateStats();
    },
    clearAll() {
        document.querySelectorAll('input[type=radio]').forEach(el => el.checked = false);
        this.updateStats();
    },
    filterRows() {
        const query = this.searchQuery.toLowerCase();
        document.querySelectorAll('.student-row').forEach(row => {
            const text = row.innerText.toLowerCase();
            if (text.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
}" x-init="updateStats()">

    <!-- Session Context Banner -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-2 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm">
                <i data-lucide="clipboard-check" class="w-5 h-5 text-white"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-blue-900">
                    {{ $session->module->name }}
                    <span class="opacity-50">·</span> Group {{ $session->group->name }}
                </p>
                <p class="text-xs text-blue-700 mt-0.5">
                    {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}
                    <span class="opacity-50">·</span> {{ $students->count() }} students
                </p>
            </div>
        </div>
        <a href="{{ url()->previous() }}" class="text-xs text-blue-600 hover:text-blue-800 underline font-medium self-start sm:self-center bg-white px-3 py-1.5 rounded-lg border border-blue-200 transition-colors">
            Change session
        </a>
    </div>

    <!-- Controls Bar -->
    <x-ui.section-card padding="p-4" class="mb-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search Student</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                    </div>
                    <input x-model="searchQuery" @input="filterRows()" type="text"
                        class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-gray-50 hover:bg-white transition-colors"
                        placeholder="Name or ID...">
                </div>
            </div>

            <!-- Bulk Actions -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Quick Actions</label>
                <div class="flex space-x-2">
                    <button @click="markAllPresent()" type="button"
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-3 rounded-lg transition-colors text-sm flex items-center justify-center shadow-sm">
                        <i data-lucide="check-circle" class="w-4 h-4 mr-1.5"></i>
                        All Present
                    </button>
                    <button @click="clearAll()" type="button"
                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-3 rounded-lg transition-colors text-sm flex items-center justify-center border border-gray-200">
                        <i data-lucide="rotate-ccw" class="w-4 h-4 mr-1.5"></i>
                        Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Summary + Save Bar -->
        <div class="mt-4 pt-4 border-t border-gray-100 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex flex-wrap justify-center md:justify-start gap-4 text-sm bg-gray-50 px-4 py-2 rounded-lg border border-gray-100">
                <div class="flex items-center">
                    <span class="w-2.5 h-2.5 bg-green-500 rounded-full mr-2"></span>
                    <span class="text-gray-500 text-xs font-medium uppercase tracking-wider">Present: <strong class="text-gray-800 ml-1 text-sm" x-text="stats.present"></strong></span>
                </div>
                <div class="flex items-center">
                    <span class="w-2.5 h-2.5 bg-red-500 rounded-full mr-2"></span>
                    <span class="text-gray-500 text-xs font-medium uppercase tracking-wider">Absent: <strong class="text-gray-800 ml-1 text-sm" x-text="stats.absent"></strong></span>
                </div>
                <div class="flex items-center">
                    <span class="w-2.5 h-2.5 bg-amber-500 rounded-full mr-2"></span>
                    <span class="text-gray-500 text-xs font-medium uppercase tracking-wider">Late: <strong class="text-gray-800 ml-1 text-sm" x-text="stats.late"></strong></span>
                </div>
                <div class="flex items-center">
                    <span class="w-2.5 h-2.5 bg-gray-300 rounded-full mr-2"></span>
                    <span class="text-gray-500 text-xs font-medium uppercase tracking-wider">Unmarked: <strong class="text-gray-800 ml-1 text-sm" x-text="stats.unmarked"></strong></span>
                </div>
            </div>
            <button onclick="document.getElementById('attendanceForm').submit()"
                class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-lg transition-all flex items-center justify-center shadow-md hover:shadow-lg active:scale-95">
                <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                Save Attendance
            </button>
        </div>
    </x-ui.section-card>

    <!-- Student List -->
    <form id="attendanceForm" action="{{ route('admin.attendance.store', $session) }}" method="POST">
        @csrf
        <x-ui.section-card :overflow="true" padding="none">
            
            <!-- Table Header (Desktop) -->
            <div class="hidden md:grid grid-cols-12 gap-4 p-4 bg-gray-50 border-b border-gray-200 font-bold text-xs text-gray-500 uppercase tracking-wider">
                <div class="col-span-1">#</div>
                <div class="col-span-2">Student ID</div>
                <div class="col-span-4">Name</div>
                <div class="col-span-5 text-center">Status</div>
            </div>

            <!-- Mobile Header -->
            <div class="md:hidden p-4 bg-gray-50 border-b border-gray-200 font-bold text-xs text-gray-500 uppercase tracking-wider">
                <div class="flex justify-between">
                    <span>Student</span>
                    <span>Status</span>
                </div>
            </div>

            <!-- Student Rows -->
            <div class="divide-y divide-gray-100">
                @foreach($students as $student)
                    @php 
                        $currentStatus = $existingRecords->get($student->id, null);
                    @endphp
                    <x-student-attendance-row :student="$student" :currentStatus="$currentStatus" :loop="$loop" />
                @endforeach
            </div>
            
            @if($students->isEmpty())
                <x-ui.empty-state 
                    icon="users" 
                    title="No students found in this group."
                    class="border-0 shadow-none"
                />
            @endif
        </x-ui.section-card>
    </form>

</div>
@endsection
