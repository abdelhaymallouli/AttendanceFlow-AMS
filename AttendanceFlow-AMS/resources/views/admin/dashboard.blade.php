@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard')

@section('content')
<!-- Today's Stats -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-6">

    <!-- Total Students -->
    <x-ui.stat-card 
        title="Total Students" 
        :value="\App\Models\StudentProfile::count()" 
        icon="users" 
        color="blue" 
        trend="+2.5%" 
        trendColor="green" 
    />

    @php 
        $total = \App\Models\StudentProfile::count();
        $present = \App\Models\AttendanceRecord::where('date', now()->toDateString())->where('status', 'present')->count();
        $absent = \App\Models\AttendanceRecord::where('date', now()->toDateString())->where('status', 'absent')->count();
        $presentRate = $total > 0 ? round(($present/$total)*100) : 0;
        $absentRate = $total > 0 ? round(($absent/$total)*100) : 0;
    @endphp

    <!-- Present Today -->
    <x-ui.stat-card 
        title="Present Today" 
        :value="$present" 
        icon="check-circle" 
        color="green" 
        :trend="$presentRate . '%'" 
    />

    <!-- Absent Today -->
    <x-ui.stat-card 
        title="Absent Today" 
        :value="$absent" 
        icon="x-circle" 
        color="red" 
        :trend="$absentRate . '%'" 
    />

    <!-- Pending Justifications -->
    <x-ui.stat-card 
        title="Pending Justifications" 
        :value="\App\Models\Justification::where('status', 'pending')->count()" 
        icon="file-text" 
        color="amber" 
        trend="Action" 
        :route="route('admin.justifications.index')"
    />
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Quick Actions -->
    <div class="lg:col-span-1 bg-white border border-gray-200 rounded-xl p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
            <i data-lucide="zap" class="w-5 h-5 mr-2 text-blue-600"></i>
            Quick Actions
        </h3>
        <div class="space-y-3">
            <a href="{{ route('admin.sessions.create') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition-colors flex items-center justify-center shadow-sm">
                <i data-lucide="plus-circle" class="w-5 h-5 mr-2"></i>
                New Session
            </a>
            <a href="{{ route('admin.attendance.index') }}" class="block w-full bg-white hover:bg-gray-50 text-gray-700 font-medium py-3 px-4 rounded-lg border border-gray-200 transition-colors flex items-center justify-center">
                <i data-lucide="clipboard-check" class="w-5 h-5 mr-2"></i>
                Attendance Entry
            </a>
            <a href="{{ route('admin.reports.index') }}" class="block w-full bg-white hover:bg-gray-50 text-gray-700 font-medium py-3 px-4 rounded-lg border border-gray-200 transition-colors flex items-center justify-center">
                <i data-lucide="download" class="w-5 h-5 mr-2"></i>
                Export Report
            </a>
            <button class="w-full bg-white hover:bg-gray-50 text-gray-700 font-medium py-3 px-4 rounded-lg border border-gray-200 transition-colors flex items-center justify-center">
                <i data-lucide="send" class="w-5 h-5 mr-2"></i>
                Send Notifications
            </button>
        </div>

        <!-- Class Summary -->
        <div class="mt-6 pt-6 border-t border-gray-200">
            <h4 class="text-sm font-semibold text-gray-700 mb-3">Classes Overview</h4>
            <div class="space-y-2 text-sm">
                @foreach(\App\Models\Group::take(3)->get() as $group)
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">{{ $group->name }}</span>
                    <span class="font-medium text-green-600">94% present</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="lg:col-span-2 bg-white border border-gray-200 rounded-xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <i data-lucide="activity" class="w-5 h-5 mr-2 text-blue-600"></i>
                Recent Activity
            </h3>
            <button class="text-sm text-blue-600 hover:text-blue-700 font-medium">View All</button>
        </div>

        <div class="space-y-4">
            @forelse(\App\Models\AttendanceRecord::with('studentProfile.user')->latest()->take(5)->get() as $record)
            <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                <div class="w-10 h-10 {{ $record->status == 'present' ? 'bg-green-100' : 'bg-red-100' }} rounded-full flex items-center justify-center flex-shrink-0">
                    <i data-lucide="{{ $record->status == 'present' ? 'check-circle' : 'x-circle' }}" class="w-5 h-5 {{ $record->status == 'present' ? 'text-green-600' : 'text-red-600' }}"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800">
                        {{ $record->studentProfile->user->name }} was marked {{ $record->status }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $record->session->module->name ?? 'Module' }} • {{ \Carbon\Carbon::parse($record->created_at)->diffForHumans() }}
                    </p>
                </div>
            </div>
            @empty
            <div class="text-center p-6 text-gray-500 text-sm">
                No recent activity found.
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
