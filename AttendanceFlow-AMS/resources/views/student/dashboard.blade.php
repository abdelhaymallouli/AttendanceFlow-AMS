@extends('layouts.dashboard')

@section('title', 'Student Portfolio')
@section('page_title', 'My Overview')

@section('header_actions')
<div class="flex items-center gap-3">
    <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-[10px] font-black bg-brand-50 text-blue-600 border border-brand-100 uppercase tracking-widest">
        Academic Year 2025/26
    </span>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Left Column: Attendance Stats & Profile -->
    <div class="lg:col-span-2 space-y-8">
        
        <!-- Performance Card (Mockup Style) -->
        <div class="bg-white border border-gray-200 rounded-[2.5rem] p-8 lg:p-12 shadow-2xl shadow-slate-200/50 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-48 h-48 bg-blue-600/5 rounded-full -mr-24 -mt-24 group-hover:scale-110 transition-transform duration-1000"></div>
            
            <div class="flex flex-col md:flex-row items-center gap-8 mb-10 relative z-10">
                <div class="w-24 h-24 bg-blue-100 rounded-[2rem] flex items-center justify-center font-black text-3xl text-blue-600 shadow-xl shadow-blue-500/10">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="text-center md:text-left">
                    <h3 class="text-3xl font-black text-gray-800 tracking-tight mb-2 uppercase italic">{{ Auth::user()->name }}</h3>
                    <div class="flex flex-wrap justify-center md:justify-start gap-4">
                        <span class="text-[10px] font-black text-gray-400 border border-gray-100 px-3 py-1 rounded-full uppercase tracking-widest">ID: {{ Auth::user()->studentProfile->student_id }}</span>
                        <span class="text-[10px] font-black text-blue-600 bg-blue-50 px-3 py-1 rounded-full uppercase tracking-widest">{{ Auth::user()->studentProfile->group->name }}</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 relative z-10">
                <div class="p-6 bg-gray-50 rounded-[2rem] border border-gray-100 text-center hover:bg-white transition-colors duration-300">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Total Presence</p>
                    <p class="text-4xl font-black text-gray-800">{{ $stats['attendance_rate'] }}%</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-[2rem] border border-gray-100 text-center hover:bg-white transition-colors duration-300">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Total Absences</p>
                    <p class="text-4xl font-black text-red-500">{{ $stats['total_absences'] }}h</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-[2rem] border border-gray-100 text-center hover:bg-white transition-colors duration-300">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Justified</p>
                    <p class="text-4xl font-black text-emerald-500">{{ $stats['justified_absences'] }}h</p>
                </div>
            </div>
            
            <div class="mt-10 p-6 bg-slate-900 rounded-[2rem] text-white flex flex-col md:flex-row items-center justify-between gap-6 relative z-10">
                <div>
                    <p class="text-sm font-black uppercase tracking-widest italic opacity-70">Weekly Progress</p>
                    <p class="text-[10px] font-bold text-slate-400 mt-1">Goal: Above 90% Attendance</p>
                </div>
                <div class="flex-1 w-full max-w-md h-3 bg-slate-800 rounded-full overflow-hidden mx-auto">
                    <div class="h-full bg-blue-500 rounded-full shadow-[0_0_15px_rgba(59,130,246,0.5)]" style="width: 92%"></div>
                </div>
            </div>
        </div>

        <!-- Attendance History (Mockup Style) -->
        <div class="bg-white border border-gray-200 rounded-[2.5rem] p-8 lg:p-10 shadow-sm relative">
            <h3 class="text-lg font-black text-gray-800 uppercase tracking-widest mb-8 flex items-center">
                <i data-lucide="history" class="w-5 h-5 mr-3 text-blue-600"></i>
                Session History
            </h3>
            
            <div class="space-y-4">
                @foreach(Auth::user()->studentProfile->attendanceRecords()->with('session.module')->latest()->take(5)->get() as $record)
                <div class="flex items-center justify-between p-5 bg-gray-50/50 rounded-2xl hover:bg-gray-50 transition-colors border border-transparent hover:border-gray-100">
                    <div class="flex items-center gap-5">
                        <div class="w-12 h-12 {{ $record->status == 'present' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} rounded-xl flex items-center justify-center font-black shadow-sm">
                            <i data-lucide="{{ $record->status == 'present' ? 'check' : 'x' }}" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <p class="text-sm font-black text-gray-800 uppercase italic">{{ $record->session->module->name }}</p>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">{{ \Carbon\Carbon::parse($record->date)->format('M d, Y') }} • {{ $record->session->start_time }}</p>
                        </div>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full {{ $record->status == 'present' ? 'bg-green-50 text-green-600 border border-green-100' : 'bg-red-50 text-red-600 border border-red-100' }}">
                        {{ $record->status }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Right Column: Justifications & Actions -->
    <div class="space-y-8">
        <!-- Quick Justification Card (Mockup Style) -->
        <div class="bg-white border border-gray-200 rounded-[2.5rem] p-8 shadow-sm group">
            <div class="w-16 h-16 bg-amber-100 rounded-2xl flex items-center justify-center text-amber-600 mb-6 group-hover:scale-110 transition-transform">
                <i data-lucide="upload-cloud" class="w-8 h-8"></i>
            </div>
            <h4 class="text-xl font-black text-gray-800 uppercase italic mb-3 tracking-tighter">Absence Justification</h4>
            <p class="text-sm font-bold text-gray-400 leading-relaxed mb-8 opacity-80">Did you miss a class? Upload your documentation now for administrative approval.</p>
            <a href="{{ route('student.justifications.index') }}" 
               class="w-full bg-slate-900 hover:bg-blue-600 text-white font-black py-4 px-6 rounded-2xl transition-all flex items-center justify-center gap-3 active:scale-95">
                <span>Upload Document</span>
                <i data-lucide="arrow-right" class="w-5 h-5"></i>
            </a>
        </div>

        <!-- Schedule Preview (Mockup Style) -->
        <div class="bg-white border border-gray-200 rounded-[2.5rem] p-8 shadow-sm">
            <h3 class="text-lg font-black text-gray-800 mb-6 uppercase tracking-widest flex items-center">
                <i data-lucide="calendar-check" class="w-5 h-5 mr-3 text-blue-600"></i>
                Today's Schedule
            </h3>
            
            <div class="space-y-6 relative border-l-2 border-gray-100 pl-8 ml-3">
                @forelse($stats['upcoming_sessions'] as $session)
                <div class="relative">
                    <div class="absolute -left-[41px] top-1 w-6 h-6 bg-blue-600 rounded-full border-4 border-white shadow-lg shadow-blue-500/20"></div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}</p>
                    <p class="text-sm font-black text-gray-800 uppercase">{{ $session->module->name }} / {{ $session->type }}</p>
                </div>
                @empty
                <p class="text-xs font-bold text-gray-400 italic text-center py-8">No upcoming sessions</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
