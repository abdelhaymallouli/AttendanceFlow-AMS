@extends('layouts.dashboard')

@section('title', 'Attendance Entry')
@section('page_title', 'Pointage Terminal')

@section('header_actions')
<div class="flex items-center gap-3">
    <div class="hidden sm:flex flex-col items-end pr-4 border-r border-gray-100">
        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Target Group</span>
        <span class="text-xs font-black text-blue-600 uppercase">{{ $session->group->name }}</span>
    </div>
    <div class="bg-slate-900 text-white px-5 py-2 rounded-xl text-[10px] font-black tracking-[0.2em] uppercase shadow-lg shadow-slate-200">
        Session: {{ $session->start_time }} - {{ $session->end_time }}
    </div>
</div>
@endsection

@section('content')
<div class="space-y-8" x-data="{ 
    markingMode: 'individual',
    stats: { present: 0, absent: 0, late: 0 },
    updateStats() {
        const statuses = Array.from(document.querySelectorAll('input[type=radio]:checked')).map(r => r.value);
        this.stats.present = statuses.filter(s => s === 'present').length;
        this.stats.absent = statuses.filter(s => s === 'absent').length;
        this.stats.late = statuses.filter(s => s === 'late').length;
    }
}" x-init="updateStats()">

    <!-- Session Hero Header (Mockup Style) -->
    <div class="bg-white border border-gray-100 rounded-[3rem] p-8 lg:p-12 shadow-2xl shadow-slate-200/50 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-64 h-64 bg-blue-600/5 rounded-full -mr-32 -mt-32 group-hover:scale-110 transition-transform duration-1000 opacity-50"></div>
        
        <div class="max-w-4xl relative z-10">
            <h2 class="text-4xl lg:text-5xl font-black text-gray-800 tracking-tighter mb-4 uppercase italic">
                {{ $session->module->name }}
            </h2>
            <div class="flex flex-wrap items-center gap-6 mt-8">
                <div class="flex items-center gap-3 px-5 py-3 bg-gray-50 rounded-2xl border border-gray-100">
                    <i data-lucide="users" class="w-5 h-5 text-blue-600"></i>
                    <span class="text-xs font-black text-gray-600 uppercase tracking-widest">{{ $session->group->name }} ({{ $students->count() }} Students)</span>
                </div>
                <div class="flex items-center gap-3 px-5 py-3 bg-gray-100 rounded-2xl border border-gray-100">
                    <i data-lucide="map-pin" class="w-5 h-5 text-gray-400"></i>
                    <span class="text-xs font-black text-gray-500 uppercase tracking-widest">Main Lab / Room 02</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Status Overview -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-6 shadow-sm shadow-emerald-500/5">
            <p class="text-[9px] font-black text-emerald-600/60 uppercase tracking-widest mb-1">Present Today</p>
            <p class="text-3xl font-black text-emerald-600 italic" x-text="stats.present">0</p>
        </div>
        <div class="bg-red-50 border border-red-100 rounded-2xl p-6 shadow-sm shadow-red-500/5">
            <p class="text-[9px] font-black text-red-600/60 uppercase tracking-widest mb-1">Total Absent</p>
            <p class="text-3xl font-black text-red-600 italic" x-text="stats.absent">0</p>
        </div>
        <div class="bg-amber-50 border border-amber-100 rounded-2xl p-6 shadow-sm shadow-amber-500/5">
            <p class="text-[9px] font-black text-amber-600/60 uppercase tracking-widest mb-1">Late Arrival</p>
            <p class="text-3xl font-black text-amber-600 italic" x-text="stats.late">0</p>
        </div>
        <button @click="document.getElementById('attendanceForm').submit()" 
                class="bg-blue-600 hover:bg-blue-700 text-white border border-blue-500 rounded-2xl p-6 shadow-xl shadow-blue-500/20 active:scale-95 transition-all text-left">
            <p class="text-[9px] font-black text-white/60 uppercase tracking-widest mb-1">Action</p>
            <p class="text-xl font-black uppercase italic leading-none">COMMIT POINTAGE</p>
        </button>
    </div>

    <form id="attendanceForm" action="{{ route('teacher.sessions.attendance.store', $session) }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($students as $student)
            @php 
                $record = $session->attendanceRecords->where('student_profile_id', $student->id)->first();
                $currentStatus = $record ? $record->status : 'absent';
            @endphp
            <div class="bg-white border border-gray-100 rounded-[2rem] p-6 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 transition-all group overflow-hidden relative">
                <div class="flex items-center gap-5 relative z-10">
                    <div class="w-14 h-14 bg-gray-50 text-gray-400 rounded-2xl flex items-center justify-center font-black group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors uppercase italic shadow-inner">
                        {{ substr($student->user->name, 0, 1) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-black text-gray-800 uppercase italic tracking-tighter truncate">{{ $student->user->name }}</p>
                        <p class="text-[10px] font-bold text-gray-300 uppercase tracking-widest mt-1">{{ $student->student_id }}</p>
                    </div>
                </div>

                <div class="mt-8 grid grid-cols-3 gap-3 relative z-10">
                    <label class="cursor-pointer">
                        <input type="radio" name="attendance[{{ $student->id }}]" value="present" 
                               class="peer hidden" {{ $currentStatus == 'present' ? 'checked' : '' }} @change="updateStats()">
                        <div class="bg-gray-50 text-gray-400 border border-transparent py-3 px-2 rounded-xl text-center text-[10px] font-black uppercase tracking-widest transition-all peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:shadow-lg peer-checked:shadow-emerald-500/20 active:scale-90">
                            Present
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="attendance[{{ $student->id }}]" value="absent" 
                               class="peer hidden" {{ $currentStatus == 'absent' ? 'checked' : '' }} @change="updateStats()">
                        <div class="bg-gray-50 text-gray-400 border border-transparent py-3 px-2 rounded-xl text-center text-[10px] font-black uppercase tracking-widest transition-all peer-checked:bg-red-500 peer-checked:text-white peer-checked:shadow-lg peer-checked:shadow-red-500/20 active:scale-90">
                            Absent
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="attendance[{{ $student->id }}]" value="late" 
                               class="peer hidden" {{ $currentStatus == 'late' ? 'checked' : '' }} @change="updateStats()">
                        <div class="bg-gray-50 text-gray-400 border border-transparent py-3 px-2 rounded-xl text-center text-[10px] font-black uppercase tracking-widest transition-all peer-checked:bg-amber-500 peer-checked:text-white peer-checked:shadow-lg peer-checked:shadow-amber-500/20 active:scale-90">
                            Late
                        </div>
                    </label>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-16 flex justify-center pb-20">
            <button type="submit" 
                    class="bg-slate-900 hover:bg-blue-600 text-white font-black py-6 px-12 rounded-[2rem] transition-all duration-300 flex items-center gap-4 shadow-2xl shadow-slate-200 hover:shadow-blue-500/20 active:scale-95 text-lg uppercase tracking-widest leading-none italic group">
                <i data-lucide="save" class="w-6 h-6 group-hover:scale-110 transition-transform"></i>
                Commit Official Attendance
            </button>
        </div>
    </form>

</div>
@endsection
