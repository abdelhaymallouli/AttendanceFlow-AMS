@extends('layouts.dashboard')

@section('title', 'Teacher Dashboard')
@section('page_title', 'My Dashboard')

@section('header_actions')
<div class="flex items-center gap-3">
    <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-black bg-brand-50 text-blue-600 border border-brand-100 uppercase tracking-widest">
        <span class="relative flex h-2 w-2">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-600"></span>
        </span>
        Live Session
    </span>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Left Column: Active Session & Controls -->
    <div class="lg:col-span-2 space-y-8">
        
        <!-- Active Session Card (Mockup Style) -->
        <div class="bg-white border border-gray-200 rounded-[2.5rem] p-8 lg:p-10 shadow-2xl shadow-slate-200/50 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-600/5 rounded-full -mr-16 -mt-16 group-hover:scale-110 transition-transform duration-700"></div>
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10 relative z-10">
                <div>
                    <h3 class="text-3xl font-black text-gray-800 tracking-tight mb-2 uppercase italic">Active Pointage</h3>
                    <p class="text-slate-400 font-bold text-sm tracking-widest uppercase">Current Academic Session</p>
                </div>
                <div class="bg-slate-900 text-white px-6 py-3 rounded-2xl font-black text-sm tracking-widest shadow-xl shadow-slate-200">
                    {{ now()->format('H:i') }} • LIVE
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative z-10">
                <div class="p-6 bg-gray-50 rounded-[2rem] border border-gray-100 flex items-center gap-4 group-hover:translate-x-1 transition-transform">
                    <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-sm text-blue-600">
                        <i data-lucide="book-open" class="w-7 h-7"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Current Module</p>
                        <p class="text-sm font-black text-gray-800 uppercase tracking-tight">{{ $currentSession->module->name ?? 'None' }}</p>
                    </div>
                </div>

                <div class="p-6 bg-gray-50 rounded-[2rem] border border-gray-100 flex items-center gap-4 group-hover:-translate-x-1 transition-transform">
                    <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-sm text-blue-600">
                        <i data-lucide="users" class="w-7 h-7"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Target Group</p>
                        <p class="text-sm font-black text-gray-800 uppercase tracking-tight">{{ $currentSession->group->name ?? 'None' }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-10 flex flex-col sm:flex-row gap-4 relative z-10">
                @if($currentSession)
                <a href="{{ route('teacher.sessions.attendance.show', $currentSession) }}" 
                   class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-black py-5 px-8 rounded-2xl flex items-center justify-center gap-3 transition-all shadow-xl shadow-blue-500/20 active:scale-95">
                    <i data-lucide="scan-face" class="w-6 h-6"></i>
                    <span>START POINTAGE NOW</span>
                </a>
                @else
                <button disabled class="flex-1 bg-gray-100 text-gray-400 font-black py-5 px-8 rounded-2xl flex items-center justify-center gap-3 cursor-not-allowed">
                    <i data-lucide="clock" class="w-6 h-6"></i>
                    <span>NO ACTIVE SESSION</span>
                </button>
                @endif
                <button class="bg-white border border-gray-200 hover:bg-gray-50 text-slate-800 font-bold py-5 px-8 rounded-2xl flex items-center justify-center gap-3 transition-all active:scale-95">
                    <i data-lucide="history" class="w-6 h-6 text-slate-400"></i>
                    <span>VIEW HISTORY</span>
                </button>
            </div>
        </div>

        <!-- Student List Summary (Mockup Style Table) -->
        <div class="bg-white border border-gray-200 rounded-[2.5rem] p-8 lg:p-10 shadow-sm relative">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-lg font-black text-gray-800 uppercase tracking-widest flex items-center">
                    <i data-lucide="user-check" class="w-5 h-5 mr-3 text-blue-600"></i>
                    Quick List Overview
                </h3>
            </div>

            <div class="space-y-4">
                @foreach(\App\Models\StudentProfile::with('user')->take(4)->get() as $student)
                <div class="flex items-center justify-between p-4 bg-gray-50/50 rounded-2xl hover:bg-gray-50 transition-colors border border-transparent hover:border-gray-100">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center font-black text-blue-600 shadow-sm border border-gray-100">
                            {{ substr($student->user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-black text-gray-800">{{ $student->user->name }}</p>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $student->student_id }}</p>
                        </div>
                    </div>
                    <span class="text-[10px] font-black text-blue-600 bg-blue-50 px-3 py-1.5 rounded-full uppercase tracking-widest border border-blue-100">92% Attendance</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Right Column: Stats & Schedule -->
    <div class="space-y-8">
        <!-- Daily Progress (Mockup Style) -->
        <div class="bg-slate-900 border border-slate-800 rounded-[2.5rem] p-8 text-white shadow-2xl shadow-slate-300 relative overflow-hidden">
            <div class="absolute bottom-0 right-0 w-32 h-32 bg-white/5 rounded-full -mb-16 -mr-16"></div>
            
            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 py-1 border-b border-slate-800">Pointage Insight</h4>
            
            <div class="space-y-6">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-black uppercase tracking-widest">Global Rate</span>
                        <span class="text-sm font-black">88%</span>
                    </div>
                    <div class="h-2 w-full bg-slate-800 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-500 rounded-full" style="width: 88%"></div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 pt-4">
                    <div class="p-4 bg-slate-800/50 rounded-2xl border border-slate-700/50">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Students</p>
                        <p class="text-2xl font-black italic">{{ $stats['total_students'] }}</p>
                    </div>
                    <div class="p-4 bg-slate-800/50 rounded-2xl border border-slate-700/50">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Justif. </p>
                        <p class="text-2xl font-black italic text-amber-400">{{ $stats['pending_justifications'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today's Schedule (Mockup Style) -->
        <div class="bg-white border border-gray-200 rounded-[2.5rem] p-8 shadow-sm">
            <h3 class="text-lg font-black text-gray-800 mb-6 uppercase tracking-widest flex items-center">
                <i data-lucide="calendar" class="w-5 h-5 mr-3 text-blue-600"></i>
                Schedule
            </h3>
            
            <div class="space-y-6 relative">
                <div class="absolute left-[20px] top-4 bottom-4 w-px bg-gray-100"></div>
                
                @forelse($sessions->limit(3)->get() as $session)
                <div class="relative pl-12 {{ \Carbon\Carbon::parse($session->start_time)->isPast() ? 'opacity-50' : '' }}">
                    <div class="absolute left-0 top-1 w-10 h-10 {{ \Carbon\Carbon::parse($session->start_time)->isToday() ? 'bg-blue-600' : 'bg-gray-100' }} rounded-full border-4 border-white shadow-lg transition-colors z-10 flex items-center justify-center">
                        <i data-lucide="{{ \Carbon\Carbon::parse($session->start_time)->isPast() ? 'check' : 'clock' }}" class="w-4 h-4 {{ \Carbon\Carbon::parse($session->start_time)->isToday() ? 'text-white' : 'text-gray-400' }}"></i>
                    </div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}</p>
                    <p class="text-sm font-black text-gray-800 uppercase tracking-tighter italic">{{ $session->module->name }} / {{ $session->group->name }}</p>
                </div>
                @empty
                <p class="text-xs font-bold text-gray-400 italic text-center py-8">No sessions scheduled</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
