@extends('layouts.dashboard')

@section('title', 'Global Analytics')
@section('page_title', 'Reports & Analytics')



@section('header_actions')
<button class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors flex items-center text-sm shadow-sm">
    <i data-lucide="download" class="w-4 h-4 mr-2"></i> Export PDF
</button>
@endsection

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'overview' }" x-init="$watch('activeTab', value => { if(value === 'trends') window.initTrendsChart(); })">
    
    <!-- Tabs -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
        <div class="flex border-b border-gray-200 overflow-x-auto">
            <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600 hover:text-gray-800'" class="px-6 py-4 font-medium text-sm whitespace-nowrap transition-colors">
                Overview
            </button>
            <button @click="activeTab = 'trends'" :class="activeTab === 'trends' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600 hover:text-gray-800'" class="px-6 py-4 font-medium text-sm whitespace-nowrap transition-colors">
                Trends
            </button>
            <button @click="activeTab = 'classes'" :class="activeTab === 'classes' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600 hover:text-gray-800'" class="px-6 py-4 font-medium text-sm whitespace-nowrap transition-colors">
                By Class
            </button>
            <button @click="activeTab = 'students'" :class="activeTab === 'students' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600 hover:text-gray-800'" class="px-6 py-4 font-medium text-sm whitespace-nowrap transition-colors">
                At-Risk Students
            </button>
        </div>
    </div>

    <!-- Overview Tab -->
    <div x-show="activeTab === 'overview'" class="space-y-6">

        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <x-ui.stat-card 
                title="Average Attendance" 
                value="95.8%" 
                icon="trending-up" 
                color="green" 
                trend="+1.2% from last month" 
            />
            <x-ui.stat-card 
                title="Absence Rate" 
                value="4.2%" 
                icon="alert-triangle" 
                color="red" 
                trend="-0.8% from last month" 
                trendColor="green" 
            />
            <x-ui.stat-card 
                title="Late Arrivals (Month)" 
                value="124" 
                icon="clock" 
                color="amber" 
                trend="+15 from last month" 
                trendColor="red" 
            />
            <x-ui.stat-card 
                title="Justified Absences" 
                value="87%" 
                icon="file-check" 
                color="blue" 
                trend="342 of 394 absences" 
                trendColor="blue" 
            />
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Monthly Attendance Trend</h3>
                <div class="relative h-[300px]"> <canvas id="monthlyTrendChart"></canvas></div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Current Month Distribution</h3>
                <div class="relative h-[300px]"> <canvas id="statusDistributionChart"></canvas></div>
            </div>
        </div>
    </div>

    <!-- Trends Tab -->
    <div x-show="activeTab === 'trends'" style="display: none;" class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Absence Trends by Day of Week</h3>
            <div class="relative h-[350px]"> <canvas id="weekdayTrendChart"></canvas></div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Peak Absence Days</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-800">Monday, Dec 16</span>
                        <span class="text-sm font-bold text-red-600">42 absences</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-800">Friday, Dec 13</span>
                        <span class="text-sm font-bold text-red-600">38 absences</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-amber-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-800">Monday, Dec 9</span>
                        <span class="text-sm font-bold text-amber-600">35 absences</span>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Seasonal Patterns</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <span class="text-gray-700">September</span>
                        <span class="font-medium text-green-600">97.2% attendance</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <span class="text-gray-700">October</span>
                        <span class="font-medium text-green-600">96.5% attendance</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Classes Tab -->
    <div x-show="activeTab === 'classes'" style="display: none;" class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800">Class Performance Ranking</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @foreach(\App\Models\Group::take(5)->get() as $index => $group)
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center justify-center w-10 h-10 {{ $index < 3 ? 'bg-green-100 text-green-600' : 'bg-amber-100 text-amber-600' }} rounded-full font-bold">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800">{{ $group->name }}</h4>
                                <p class="text-sm text-gray-500">{{ $group->students->count() ?? rand(20, 35) }} students</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold {{ $index < 3 ? 'text-green-600' : 'text-amber-600' }}">{{ 98 - $index * 1.5 }}%</p>
                            <p class="text-xs text-gray-500">attendance rate</p>
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="{{ $index < 3 ? 'bg-green-600' : 'bg-amber-600' }} h-2 rounded-full" style="width: {{ 98 - $index * 1.5 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- At-Risk Students Tab -->
    <div x-show="activeTab === 'students'" style="display: none;" class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800">Students Requiring Attention</h3>
                <p class="text-sm text-gray-500 mt-1">Students with attendance below 90% or pattern concerns</p>
            </div>
            <div class="divide-y divide-gray-200">
                @foreach(\App\Models\StudentProfile::with(['user', 'group'])->take(3)->get() as $student)
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                                <i data-lucide="alert-circle" class="w-6 h-6 text-red-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800">{{ $student->user->name }}</h4>
                                <p class="text-sm text-gray-500">{{ $student->student_id }} • {{ $student->group->name ?? 'G1' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xl font-bold text-red-600">{{ rand(75, 89) }}.5%</p>
                            <p class="text-xs text-gray-500">{{ rand(5, 15) }} absences this month</p>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center space-x-2">
                        <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded-full">Critical</span>
                        <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded-full">Parent contact needed</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush
@endsection
