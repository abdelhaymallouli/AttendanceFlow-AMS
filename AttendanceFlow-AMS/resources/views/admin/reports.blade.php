@extends('layouts.dashboard')

@section('title', 'Analytiques globales')
@section('page_title', 'Rapports et analyses')

@section('header_actions')
<div class="flex items-center gap-2">
    <a href="{{ route('admin.export.students') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-4 rounded-lg transition-colors flex items-center text-sm shadow-sm">
        <i data-lucide="users" class="w-4 h-4 mr-2"></i> Exporter étudiants (CSV)
    </a>
    <a href="{{ route('admin.export.attendance') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors flex items-center text-sm shadow-sm">
        <i data-lucide="download" class="w-4 h-4 mr-2"></i> Exporter absences (CSV)
    </a>
</div>
@endsection

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'overview' }" x-init="$watch('activeTab', value => { if(value === 'trends') window.initTrendsChart(); })">
    
    <!-- Tabs -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
        <div class="flex border-b border-gray-200 overflow-x-auto">
            <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600 hover:text-gray-800'" class="px-6 py-4 font-medium text-sm whitespace-nowrap transition-colors">
                Aperçu
            </button>
            <button @click="activeTab = 'trends'" :class="activeTab === 'trends' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600 hover:text-gray-800'" class="px-6 py-4 font-medium text-sm whitespace-nowrap transition-colors">
                Tendances
            </button>
            <button @click="activeTab = 'classes'" :class="activeTab === 'classes' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600 hover:text-gray-800'" class="px-6 py-4 font-medium text-sm whitespace-nowrap transition-colors">
                Par classe
            </button>
            <button @click="activeTab = 'students'" :class="activeTab === 'students' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600 hover:text-gray-800'" class="px-6 py-4 font-medium text-sm whitespace-nowrap transition-colors">
                Étudiants à risque
            </button>
        </div>
    </div>

    <!-- Overview Tab -->
    <div x-show="activeTab === 'overview'" class="space-y-6">

        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <x-ui.stat-card 
                title="Présence moyenne" 
                :value="$avgAttendance . '%'" 
                icon="trending-up" 
                color="green" 
                trend="+1.2% vs mois dernier" 
            />
            <x-ui.stat-card 
                title="Taux d'absence" 
                :value="$absenceRate . '%'" 
                icon="alert-triangle" 
                color="red" 
                trend="-0.8% vs mois dernier" 
                trendColor="green" 
            />
            <x-ui.stat-card 
                title="Retards (Mois)" 
                :value="$lateRecords" 
                icon="clock" 
                color="amber" 
                trend="+15 vs mois dernier" 
                trendColor="red" 
            />
            <x-ui.stat-card 
                title="Absences justifiées" 
                :value="$justifiedRate . '%'" 
                icon="file-check" 
                color="blue" 
                :trend="$approvedJustifications . ' sur ' . ($justifiedRate > 0 ? round($approvedJustifications / ($justifiedRate/100)) : 0) . ' absences'" 
                trendColor="blue" 
            />
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Tendance mensuelle des présences</h3>
                <div class="relative h-[300px]"> <canvas id="monthlyTrendChart"></canvas></div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Répartition du mois en cours</h3>
                <div class="relative h-[300px]"> <canvas id="statusDistributionChart"></canvas></div>
            </div>
        </div>
    </div>

    <!-- Trends Tab -->
    <div x-show="activeTab === 'trends'" style="display: none;" class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Tendances d'absence par jour</h3>
            <div class="relative h-[350px]"> <canvas id="weekdayTrendChart"></canvas></div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Jours de pic d'absence</h3>
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
                <h3 class="text-lg font-bold text-gray-800 mb-4">Schémas saisonniers</h3>
                <div class="space-y-3 text-sm">
                    @foreach($monthlyTrend as $trend)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <span class="text-gray-700">{{ $trend['month'] }}</span>
                        <span class="font-medium text-green-600">{{ $trend['rate'] }}% attendance</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Classes Tab -->
    <div x-show="activeTab === 'classes'" style="display: none;" class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-800">Classement des classes</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @foreach($groups as $group)
                <div class="p-6 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center justify-center w-10 h-10 bg-green-100 text-green-600 rounded-full font-bold">
                                {{ $loop->iteration }}
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800">{{ $group->name }}</h4>
                                <p class="text-sm text-gray-500">{{ $group->student_profiles_count ?? 0 }} étudiants</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-green-600">{{ $group->attendance_rate ?? 0 }}%</p>
                            <p class="text-xs text-gray-500">taux de présence</p>
                        </div>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ $group->attendance_rate ?? 0 }}%"></div>
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
                <h3 class="text-lg font-bold text-gray-800">Étudiants nécessitant une attention</h3>
                <p class="text-sm text-gray-500 mt-1">Étudiants avec une présence inférieure à 90%</p>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($atRiskStudents as $student)
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
                            <p class="text-xl font-bold text-red-600">{{ $student->attendance_rate ?? 0 }}%</p>
                            <p class="text-xs text-gray-500">{{ $student->absences_count ?? 0 }} absences ce mois</p>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center space-x-2">
                        <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded-full">Critique</span>
                        <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded-full">Contact parental requis</span>
                    </div>
                </div>
                @empty
                <div class="p-12 text-center text-gray-500">
                    <p>Aucun étudiant à risque trouvé.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.reportData = {
        monthlyLabels: @json($monthlyTrend->pluck('month')),
        monthlyRates: @json($monthlyTrend->pluck('rate')),
        statusLabels: ['Présent', 'Absence non justifiée', 'Absence justifiée', 'Retard'],
        statusData: [{{ $avgAttendance }}, {{ $absenceRate }}, {{ $justifiedRate }}, {{ $lateRecords }}],
        weekdayLabels: ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'],
        weekdayData: [48, 32, 28, 35, 52]
    };
</script>
@endpush
@endsection