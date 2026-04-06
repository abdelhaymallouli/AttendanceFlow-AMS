@extends('layouts.dashboard')

@section('title', 'Attendance Entry')
@section('page_title', 'Session Selection')

@section('content')
<div class="space-y-8">
    
    <!-- Today's Active Sessions (Mockup Style) -->
    <div class="bg-white border border-gray-200 rounded-[2.5rem] p-8 lg:p-10 shadow-sm relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-600/5 rounded-full -mr-16 -mt-16 group-hover:scale-110 transition-transform duration-700"></div>
        
        <div class="flex items-center justify-between mb-8 relative z-10">
            <div>
                <h3 class="text-2xl font-black text-gray-800 uppercase italic tracking-tighter">Today's Schedule</h3>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Sessions for {{ now()->format('l, M d Y') }}</p>
            </div>
            <div class="bg-blue-600 text-white px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest">
                {{ $todaySessions->count() }} Sessions
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 relative z-10">
            @forelse($todaySessions as $session)
            <div class="bg-gray-50 border border-gray-100 rounded-3xl p-6 hover:shadow-xl hover:shadow-slate-200/50 transition-all group/item">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm text-blue-600">
                        <i data-lucide="book-open" class="w-6 h-6"></i>
                    </div>
                    <span class="text-[10px] font-black {{ \Carbon\Carbon::parse($session->start_time)->isPast() ? 'text-gray-400 bg-gray-100' : 'text-blue-600 bg-blue-50' }} px-3 py-1 rounded-full uppercase tracking-widest">
                        {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }}
                    </span>
                </div>
                <h4 class="text-base font-black text-gray-800 uppercase italic leading-tight mb-2">{{ $session->module->name }}</h4>
                <div class="space-y-2 mb-6">
                    <div class="flex items-center gap-2 text-xs font-bold text-gray-400">
                        <i data-lucide="users" class="w-3.5 h-3.5"></i>
                        <span>{{ $session->group->name }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs font-bold text-gray-400">
                        <i data-lucide="user" class="w-3.5 h-3.5"></i>
                        <span>{{ $session->teacherProfile->user->name }}</span>
                    </div>
                </div>
                <a href="{{ route('teacher.sessions.attendance.show', $session) }}" 
                    class="w-full bg-slate-900 border border-slate-900 hover:bg-blue-600 hover:border-blue-600 text-white font-black py-3 rounded-2xl flex items-center justify-center gap-2 transition-all active:scale-95 text-xs uppercase tracking-widest">
                    <span>Mark Attendance</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
            @empty
            <div class="col-span-full py-12 text-center bg-gray-50/50 rounded-3xl border-2 border-dashed border-gray-100">
                <i data-lucide="calendar-off" class="w-12 h-12 text-gray-200 mx-auto mb-4"></i>
                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">No sessions scheduled for today</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Other Sessions (Mockup Style List) -->
    <div class="bg-white border border-gray-200 rounded-[2.5rem] p-8 lg:p-10 shadow-sm">
        <h3 class="text-lg font-black text-gray-800 uppercase tracking-widest mb-8 flex items-center">
            <i data-lucide="list" class="w-5 h-5 mr-3 text-blue-600"></i>
            All Planned Sessions
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="pb-4 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Module / Group</th>
                        <th class="pb-4 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Teacher</th>
                        <th class="pb-4 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Date & Time</th>
                        <th class="pb-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($pastSessions->merge($upcomingSessions) as $session)
                    <tr class="group hover:bg-gray-50 transition-colors">
                        <td class="py-5 pr-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400 group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors">
                                    <i data-lucide="book-open" class="w-5 h-5"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-gray-800 uppercase italic tracking-tighter">{{ $session->module->name }}</p>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $session->group->name }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 pr-4">
                            <p class="text-sm font-bold text-gray-600">{{ $session->teacherProfile->user->name }}</p>
                        </td>
                        <td class="py-5 pr-4">
                            <p class="text-sm font-black text-gray-800">{{ \Carbon\Carbon::parse($session->start_time)->format('M d, Y') }}</p>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}</p>
                        </td>
                        <td class="py-5 text-right">
                            <a href="{{ route('teacher.sessions.attendance.show', $session) }}" 
                                class="inline-flex items-center gap-2 text-[10px] font-black text-blue-600 bg-blue-50 hover:bg-blue-600 hover:text-white px-4 py-2 rounded-xl transition-all uppercase tracking-widest">
                                Mark 
                                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
