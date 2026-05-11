@extends('layouts.dashboard')

@section('title', 'Teacher Dashboard')
@section('page_title', 'Teacher Dashboard')

@section('content')
<div class="space-y-6" x-data="teacherDashboard()" x-init="init()">

    <!-- Current Session Alert -->
    <div x-show="currentSession"
         class="mb-6 bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl shadow-lg p-6 text-white">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <span class="pulse-dot w-3 h-3 bg-green-400 rounded-full inline-block"></span>
                </div>
                <div>
                    <p class="text-sm opacity-90">Current Session</p>
                    <h3 class="text-xl font-bold" x-text="currentSession.typeLabel"></h3>
                    <p class="text-sm opacity-90" x-text="currentSession.time + ' - ' + currentSession.moduleName"></p>
                    <p class="text-sm opacity-75" x-text="currentSession.groupName + ' \u2022 ' + currentSession.studentsCount + ' students'"></p>
                </div>
            </div>
            <a :href="currentSession.url"
               class="bg-white text-blue-600 font-semibold py-3 px-6 rounded-lg hover:bg-gray-100 transition-colors flex items-center justify-center whitespace-nowrap">
                <i data-lucide="clipboard-check" class="w-5 h-5 mr-2"></i>
                Take Attendance
            </a>
        </div>
    </div>

    <!-- Today's Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-6">
        <x-ui.stat-card 
            title="Total Students" 
            :value="0" 
            icon="users" 
            color="blue" 
            trend="+2.5%" 
            trendColor="green" 
            alpineValue="totalStudents"
        />
        
        <x-ui.stat-card 
            title="Present Today" 
            :value="0" 
            icon="check-circle" 
            color="green" 
            :trend="''" 
            alpineValue="Math.round((avgAttendance/100) * totalStudents)"
        />
        
        <x-ui.stat-card 
            title="Absent Today" 
            :value="0" 
            icon="x-circle" 
            color="red" 
            :trend="''" 
            alpineValue="totalStudents - Math.round((avgAttendance/100) * totalStudents)"
        />
        
        <x-ui.stat-card 
            title="Pending Justifications" 
            :value="0" 
            icon="file-text" 
            color="amber" 
            trend="Action" 
            alpineValue="pendingCount"
        />
    </div>

    <!-- Two-Column Layout: Quick Actions + Sessions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Column: Quick Actions -->
        <div class="lg:col-span-1 bg-white border border-gray-200 rounded-xl p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <i data-lucide="zap" class="w-5 h-5 mr-2 text-blue-600"></i>
                Quick Actions
            </h3>
            <div class="space-y-3">
                <a :href="currentSession ? currentSession.url : '#'"
                   class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition-colors flex items-center justify-center shadow-sm">
                    <i data-lucide="clipboard-check" class="w-5 h-5 mr-2"></i>
                    Take Attendance
                </a>
                <a href="#"
                   class="block w-full bg-white hover:bg-gray-50 text-gray-700 font-medium py-3 px-4 rounded-lg border border-gray-200 transition-colors flex items-center justify-center">
                    <i data-lucide="clock" class="w-5 h-5 mr-2"></i>
                    View Sessions
                </a>
                <a href="#"
                   class="block w-full bg-white hover:bg-gray-50 text-gray-700 font-medium py-3 px-4 rounded-lg border border-gray-200 transition-colors flex items-center justify-center">
                    <i data-lucide="download" class="w-5 h-5 mr-2"></i>
                    Export Report
                </a>
                <button
                   class="w-full bg-white hover:bg-gray-50 text-gray-700 font-medium py-3 px-4 rounded-lg border border-gray-200 transition-colors flex items-center justify-center">
                    <i data-lucide="send" class="w-5 h-5 mr-2"></i>
                    Send Notifications
                </button>
            </div>

            <!-- My Classes Overview -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">My Classes Overview</h4>
                <div class="space-y-2 text-sm">
                    <template x-for="group in teacherGroups" :key="group.id">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600" x-text="group.name"></span>
                            <span class="font-medium"
                                :class="group.attendanceRate >= 95 ? 'text-green-600' : group.attendanceRate >= 90 ? 'text-amber-600' : 'text-red-600'"
                                x-text="group.attendanceRate + '% present'"></span>
                        </div>
                    </template>
                    <div x-show="teacherGroups.length === 0" class="text-center py-4 text-gray-400 text-sm">
                        No classes assigned
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Today's Sessions -->
        <div class="lg:col-span-2 bg-white border border-gray-200 rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                    <i data-lucide="calendar" class="w-5 h-5 mr-2 text-blue-600"></i>
                    Today's Sessions
                </h3>
                <a href="#" class="text-sm text-blue-600 hover:text-blue-700 font-medium">View All</a>
            </div>

            <div class="space-y-4">
                <template x-for="session in sessions" :key="session.id">
                    <div class="flex items-start space-x-4 p-4 rounded-lg hover:bg-gray-50 transition-colors"
                        :class="session.status === 'active' ? 'bg-blue-50 border border-blue-200' : session.status === 'completed' ? 'bg-green-50 border border-green-200' : 'bg-gray-50 border border-gray-200'">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0"
                            :class="getTypeBgClass(session.type)">
                            <i data-lucide="clock" class="w-5 h-5" :class="getTypeTextClass(session.type)"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-800" x-text="session.typeLabel + ' - ' + session.moduleName"></p>
                                <span class="text-xs font-bold px-2 py-1 rounded-full"
                                    :class="session.status === 'completed' ? 'bg-green-100 text-green-700' : session.status === 'active' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600'"
                                    x-text="session.status === 'completed' ? 'Completed' : session.status === 'active' ? 'In Progress' : 'Upcoming'"></span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1" x-text="session.time + ' (' + session.duration_hours + 'h) \u2022 ' + session.groupName + ' \u2022 ' + session.studentsCount + ' students'"></p>
                        </div>
                        <a :href="session.url"
                           class="text-sm font-medium px-3 py-1 rounded-lg transition-colors flex-shrink-0 whitespace-nowrap"
                           :class="session.status === 'completed' ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-blue-600 text-white hover:bg-blue-700'"
                           x-text="session.status === 'completed' ? 'View' : 'Take'"></a>
                    </div>
                </template>
                <div x-show="sessions.length === 0" class="text-center py-8">
                    <i data-lucide="calendar-x" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
                    <p class="text-gray-500 text-sm">No sessions scheduled for today</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="mt-6 bg-white border border-gray-200 rounded-xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <i data-lucide="activity" class="w-5 h-5 mr-2 text-blue-600"></i>
                Recent Activity
            </h3>
            <button class="text-sm text-blue-600 hover:text-blue-700 font-medium">View All</button>
        </div>
        <div class="space-y-4">
            @forelse($recentActivity as $activity)
            <div class="flex items-start space-x-4 p-4 {{ $activity['bg'] }} rounded-lg">
                <div class="w-10 h-10 {{ $activity['iconBg'] }} rounded-full flex items-center justify-center flex-shrink-0">
                    <i data-lucide="{{ $activity['icon'] }}" class="w-5 h-5 {{ $activity['iconColor'] }}"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-800">{{ $activity['message'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $activity['detail'] }} &bull; {{ $activity['time'] }}</p>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
                <i data-lucide="activity" class="w-12 h-12 mx-auto mb-2 text-gray-300"></i>
                <p class="text-sm">No recent activity</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<style>
    .pulse-dot {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
</style>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('teacherDashboard', () => ({
            sessions: @json($sessionsData),
            currentSession: null,
            totalStudents: {{ $stats['total_students'] }},
            avgAttendance: {{ $stats['avg_attendance'] }},
            pendingCount: {{ $stats['pending_justifications'] }},
            teacherGroups: @json($teacherGroups),
            init() {
                this.determineStatuses();
                setTimeout(() => {
                    if (typeof window.initIcons === 'function') {
                        window.initIcons();
                    }
                }, 50);
            },
            determineStatuses() {
                const now = new Date();
                const currentMinutes = now.getHours() * 60 + now.getMinutes();

                this.sessions.forEach(s => {
                    const [startH, startM] = s.start_time.split(':').map(Number);
                    const [endH, endM] = s.end_time.split(':').map(Number);
                    const startMinutes = startH * 60 + startM;
                    const endMinutes = endH * 60 + endM;

                    if (currentMinutes >= endMinutes) {
                        s.status = 'completed';
                    } else if (currentMinutes >= startMinutes) {
                        s.status = 'active';
                    } else {
                        s.status = 'upcoming';
                    }
                });

                this.currentSession = this.sessions.find(s => s.status === 'active') || null;
            },
            getTypeBgClass(type) {
                const classes = { 'lecture': 'bg-purple-100', 'td': 'bg-blue-100', 'tp': 'bg-green-100' };
                return classes[type] || 'bg-gray-100';
            },
            getTypeTextClass(type) {
                const classes = { 'lecture': 'text-purple-600', 'td': 'text-blue-600', 'tp': 'text-green-600' };
                return classes[type] || 'text-gray-600';
            }
        }));
    });
</script>
@endpush
@endsection