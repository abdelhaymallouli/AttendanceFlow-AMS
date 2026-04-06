@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard')

@section('content')
<!-- Today's Stats (Mockup Style) -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">

    <!-- Total Students -->
    <div class="bg-white border border-gray-200 rounded-2xl p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <i data-lucide="users" class="w-6 h-6 text-blue-600"></i>
            </div>
            <span class="text-[10px] font-black text-green-600 bg-green-50 px-2.5 py-1 rounded-full uppercase tracking-widest">+2.5%</span>
        </div>
        <p class="text-3xl font-black text-gray-800 mb-1">{{ \App\Models\StudentProfile::count() }}</p>
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total Students</p>
    </div>

    <!-- Present Today -->
    <div class="bg-white border border-gray-200 rounded-2xl p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
            </div>
            <span class="text-[10px] font-black text-green-600 bg-green-50 px-2.5 py-1 rounded-full uppercase tracking-widest">
                @php 
                    $total = \App\Models\StudentProfile::count();
                    $present = \App\Models\AttendanceRecord::where('date', now()->toDateString())->where('status', 'present')->count();
                    echo $total > 0 ? round(($present/$total)*100) : 0;
                @endphp%
            </span>
        </div>
        <p class="text-3xl font-black text-gray-800 mb-1">{{ $present }}</p>
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Present Today</p>
    </div>

    <!-- Absent Today -->
    <div class="bg-white border border-gray-200 rounded-2xl p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                <i data-lucide="x-circle" class="w-6 h-6 text-red-600"></i>
            </div>
            <span class="text-[10px] font-black text-red-600 bg-red-50 px-2.5 py-1 rounded-full uppercase tracking-widest">
                @php 
                    $absent = \App\Models\AttendanceRecord::where('date', now()->toDateString())->where('status', 'absent')->count();
                    echo $total > 0 ? round(($absent/$total)*100) : 0;
                @endphp%
            </span>
        </div>
        <p class="text-3xl font-black text-gray-800 mb-1">{{ $absent }}</p>
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Absent Today</p>
    </div>

    <!-- Pending Justifications -->
    <a href="{{ route('admin.justifications.index') }}"
        class="bg-white border border-gray-200 rounded-2xl p-6 hover:shadow-md transition-shadow cursor-pointer block group">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center group-hover:bg-amber-200 transition-colors">
                <i data-lucide="file-text" class="w-6 h-6 text-amber-600"></i>
            </div>
            <span class="text-[10px] font-black text-amber-600 bg-amber-50 px-2.5 py-1 rounded-full uppercase tracking-widest">Action</span>
        </div>
        <p class="text-3xl font-black text-gray-800 mb-1">{{ \App\Models\Justification::where('status', 'pending')->count() }}</p>
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Pending Justifications</p>
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    <!-- Quick Actions (Mockup Style) -->
    <div class="lg:col-span-1 bg-white border border-gray-200 rounded-[2rem] p-8 shadow-sm">
        <h3 class="text-lg font-black text-gray-800 mb-6 flex items-center uppercase tracking-widest">
            <i data-lucide="zap" class="w-5 h-5 mr-3 text-blue-600"></i>
            Quick Actions
        </h3>
        <div class="space-y-4">
            <a href="#"
                class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-4 px-6 rounded-2xl transition-all flex items-center justify-center shadow-lg shadow-blue-500/20 active:scale-95">
                <i data-lucide="plus-circle" class="w-5 h-5 mr-2"></i>
                New Session
            </a>
            <a href="{{ route('admin.attendance.index') }}"
                class="block w-full bg-white hover:bg-gray-50 text-gray-700 font-bold py-4 px-6 rounded-2xl border border-gray-200 transition-all flex items-center justify-center">
                <i data-lucide="clipboard-check" class="w-5 h-5 mr-2"></i>
                Attendance Entry
            </a>
            <a href="{{ route('admin.reports.index') }}"
                class="block w-full bg-white hover:bg-gray-50 text-gray-700 font-bold py-4 px-6 rounded-2xl border border-gray-200 transition-all flex items-center justify-center">
                <i data-lucide="download" class="w-5 h-5 mr-2"></i>
                Export Report
            </a>
        </div>

        <!-- Class Summary (Mockup Style Data Integration) -->
        <div class="mt-10 pt-8 border-t border-gray-100">
            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6">Classes Performance</h4>
            <div class="space-y-5">
                @foreach(\App\Models\Group::take(3)->get() as $group)
                <div class="flex justify-between items-center">
                    <span class="text-sm font-bold text-gray-600">{{ $group->name }}</span>
                    <span class="text-xs font-black text-blue-600 bg-blue-50 px-2 py-1 rounded-lg">94% present</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Activity (Mockup Style) -->
    <div class="lg:col-span-2 bg-white border border-gray-200 rounded-[2rem] p-8 shadow-sm">
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-lg font-black text-gray-800 flex items-center uppercase tracking-widest">
                <i data-lucide="activity" class="w-5 h-5 mr-3 text-blue-600"></i>
                Recent Activity
            </h3>
            <button class="text-xs font-black text-blue-600 hover:text-blue-700 uppercase tracking-widest">View All Logs</button>
        </div>

        <div class="space-y-6">
            @foreach(\App\Models\AttendanceRecord::with('studentProfile.user')->latest()->take(5)->get() as $record)
            <div class="flex items-start space-x-4 p-5 bg-gray-50/50 rounded-2xl hover:bg-gray-50 transition-colors border border-transparent hover:border-gray-100">
                <div class="w-12 h-12 {{ $record->status == 'present' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} rounded-xl flex items-center justify-center shrink-0">
                    <i data-lucide="{{ $record->status == 'present' ? 'check-circle' : 'x-circle' }}" class="w-6 h-6"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-black text-gray-800 truncate">
                        {{ $record->studentProfile->user->name }} was marked 
                        <span class="uppercase tracking-tighter">{{ $record->status }}</span>
                    </p>
                    <p class="text-xs font-bold text-gray-400 mt-1 capitalize opacity-80">
                        {{ $record->session->module->name }} • {{ \Carbon\Carbon::parse($record->created_at)->diffForHumans() }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
