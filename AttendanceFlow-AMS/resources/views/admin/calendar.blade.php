@extends('layouts.dashboard')

@section('title', 'Academic Schedule')
@section('page_title', 'Calendar Hub')

@section('header_actions')
<div class="flex items-center gap-3">
    <button class="bg-white border border-gray-100 text-gray-500 font-black py-3 px-6 rounded-2xl transition-all hover:bg-slate-50 flex items-center gap-2 text-[10px] uppercase tracking-widest shadow-sm">
        <i data-lucide="filter" class="w-4 h-4 text-blue-600"></i>
        <span>Filter Groups</span>
    </button>
    <button class="bg-slate-900 hover:bg-blue-600 text-white font-black py-3 px-6 rounded-2xl transition-all shadow-xl shadow-slate-200 hover:shadow-blue-500/20 active:scale-95 flex items-center gap-2 text-[10px] uppercase tracking-widest">
        <i data-lucide="plus" class="w-4 h-4"></i>
        <span>New Session</span>
    </button>
</div>
@endsection

@section('content')
<div class="space-y-12">
    
    @php
        $groupedSessions = $sessions->groupBy(function($s) {
            return \Carbon\Carbon::parse($s->start_time)->format('Y-m-d');
        });
    @endphp

    @forelse($groupedSessions as $date => $daySessions)
    <div class="space-y-6">
        <!-- Date Header (Mockup Style) -->
        <div class="flex items-center gap-6 group">
            <div class="flex flex-col items-center justify-center w-20 h-20 bg-white border border-gray-100 rounded-3xl shadow-sm group-hover:bg-blue-600 group-hover:text-white transition-all duration-500">
                <span class="text-[10px] font-black uppercase tracking-widest opacity-60">{{ \Carbon\Carbon::parse($date)->format('M') }}</span>
                <span class="text-2xl font-black italic">{{ \Carbon\Carbon::parse($date)->format('d') }}</span>
            </div>
            <div class="flex-1">
                <h3 class="text-xl font-black text-gray-800 uppercase italic tracking-tighter">{{ \Carbon\Carbon::parse($date)->format('l') }}</h3>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mt-1">{{ count($daySessions) }} Sessions Scheduled</p>
            </div>
            <div class="h-px flex-1 bg-gray-100 hidden md:block"></div>
        </div>

        <!-- Session Grid (Mockup Style) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($daySessions as $session)
            <a href="{{ route('teacher.sessions.attendance.show', $session) }}" class="bg-white border border-gray-100 rounded-[2.5rem] p-8 shadow-sm hover:shadow-2xl hover:shadow-slate-200/50 hover:border-blue-100 transition-all duration-500 group relative overflow-hidden active:scale-[0.98] block">
                <!-- Interactive Glow -->
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-600 to-indigo-600"></div>
                
                <div class="flex items-start justify-between mb-8">
                    <div class="bg-gray-50 text-gray-800 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest border border-gray-100 group-hover:bg-blue-50 group-hover:text-blue-600 group-hover:border-blue-100 transition-colors">
                        {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-gray-50 text-gray-400 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all group-hover:translate-x-0 translate-x-4">
                        <i data-lucide="clipboard-check" class="w-5 h-5"></i>
                    </div>
                </div>

                <h4 class="text-xl font-black text-gray-800 leading-tight mb-4 uppercase italic tracking-tighter group-hover:text-blue-600 transition-colors">{{ $session->module->name }}</h4>
                
                <div class="flex items-center gap-4 mb-8">
                    <div class="flex items-center gap-2 px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[9px] font-black uppercase tracking-widest">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        {{ $session->group->name }}
                    </div>
                    <div class="flex items-center gap-2 px-3 py-1 bg-slate-50 text-slate-400 rounded-lg text-[9px] font-black uppercase tracking-widest">
                        {{ $session->type }}
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center font-black text-sm text-blue-600 shadow-sm border border-white">
                            {{ substr($session->teacherProfile->user->name, 0, 1) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-black text-gray-800 truncate">{{ $session->teacherProfile->user->name }}</p>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Teacher</p>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @empty
    <div class="py-32 text-center flex flex-col items-center justify-center bg-white border border-gray-100 rounded-[3rem] shadow-inner">
        <div class="w-24 h-24 bg-gray-50 rounded-[2rem] flex items-center justify-center mb-8 shadow-sm">
            <i data-lucide="calendar-x" class="w-12 h-12 text-gray-300"></i>
        </div>
        <h4 class="text-3xl font-black text-gray-800 uppercase italic tracking-tighter">Empty Schedule</h4>
        <p class="text-gray-400 font-bold mt-3 uppercase tracking-widest text-xs opacity-60">No academic sessions found in the system.</p>
        <button class="mt-10 bg-blue-600 text-white font-black py-4 px-10 rounded-2xl shadow-xl shadow-blue-500/20 active:scale-95 transition-all text-[10px] uppercase tracking-widest">
            Schedule First Session
        </button>
    </div>
    @endforelse

</div>
@endsection
