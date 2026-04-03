@extends('mobile.layouts.app')

@section('title', 'Mon Assiduité - Solicode AMS')
@section('header_title', 'My Attendance')

@section('content')
<div class="px-4 pt-4 pb-24">

    <!-- Student Profile Card -->
    <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-3xl p-5 mb-6 text-white shadow-lg shadow-indigo-100">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center flex-shrink-0 text-white font-bold text-xl backdrop-blur-sm border border-white/10">
                {{ substr($stats['name'] ?? 'S', 0, 1) }}
            </div>
            <div>
                <p class="text-indigo-100 text-[10px] uppercase font-bold tracking-widest mb-0.5">Stagiaire</p>
                <h2 class="text-xl font-bold italic">{{ $stats['name'] ?? 'Étudiant' }}</h2>
                <p class="text-indigo-200 text-xs font-medium">Groupe {{ $stats['group'] ?? 'GRP' }} • ID: {{ $stats['student_id'] ?? 'N/A' }}</p>
            </div>
        </div>
        <!-- Progress bar style -->
        <div class="mt-6 bg-white bg-opacity-10 rounded-2xl p-4 border border-white/5 backdrop-blur-md">
            <div class="flex justify-between text-xs mb-2">
                <span class="text-indigo-100 font-bold uppercase tracking-tighter">Taux Global</span>
                <span class="font-black text-lg">{{ $stats['attendance_rate'] ?? 0 }}%</span>
            </div>
            <div class="bg-indigo-900 bg-opacity-30 rounded-full h-3 overflow-hidden">
                <div class="bg-white rounded-full h-3 transition-all duration-1000 shadow-sm" style="width:{{ $stats['attendance_rate'] ?? 0 }}%"></div>
            </div>
            <div class="flex justify-between items-center mt-3">
                <p class="text-[10px] text-indigo-200 font-bold uppercase">{{ $stats['absences'] ?? 0 }} absences ce semestre</p>
                <div class="w-2 h-2 rounded-full bg-green-400 animate-pulse shadow-sm shadow-green-400"></div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div class="grid grid-cols-3 gap-3 mb-6">
        <div class="bg-white rounded-2xl p-4 text-center border border-gray-100 shadow-sm">
            <p class="text-sm font-bold text-gray-400 uppercase tracking-tighter mb-1">Taux</p>
            <p class="text-xl font-black text-indigo-600">{{ $stats['attendance_rate'] ?? 0 }}%</p>
        </div>
        <div class="bg-white rounded-2xl p-4 text-center border border-gray-100 shadow-sm">
            <p class="text-sm font-bold text-gray-400 uppercase tracking-tighter mb-1">Absences</p>
            <p class="text-xl font-black text-red-500">{{ $stats['absences'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-2xl p-4 text-center border border-gray-100 shadow-sm">
            <p class="text-sm font-bold text-gray-400 uppercase tracking-tighter mb-1">Alertes</p>
            <p class="text-xl font-black text-amber-500">{{ $stats['pending_justifications'] ?? 0 }}</p>
        </div>
    </div>

    <!-- Justification CTA -->
    <a href="#" class="block bg-white border-2 border-dashed border-indigo-200 rounded-3xl p-5 mb-6 shadow-sm active:scale-95 transition-all">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center flex-shrink-0">
                <i data-lucide="file-plus" class="w-6 h-6 text-indigo-600"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-gray-800">Justifier une absence</p>
                <p class="text-[10px] text-gray-500 font-medium">Déposer un document ou certificat médical</p>
            </div>
            <i data-lucide="chevron-right" class="w-5 h-5 text-gray-300"></i>
        </div>
    </a>

    <!-- Recent Absences -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Absences Récentes</h3>
            <span class="text-xs text-indigo-600 font-bold">Historique</span>
        </div>
        <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-sm">
            <div class="divide-y divide-gray-50">
                @forelse($attendanceData ?? [] as $record)
                    @if($record['status'] == 'absent')
                        <div class="flex items-center gap-4 p-5 hover:bg-gray-50 transition-colors">
                            <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center flex-shrink-0 text-red-500">
                                <i data-lucide="calendar-x" class="w-5 h-5"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[10px] text-gray-400 font-bold uppercase">{{ $record['session']['module']['name'] ?? 'Séance' }}</p>
                                <p class="text-sm font-bold text-gray-800 italic">{{ \Carbon\Carbon::parse($record['date'])->format('d M Y') }}</p>
                            </div>
                            <span class="text-[10px] font-bold px-2 py-1 rounded-lg uppercase 
                                {{ $record['status'] == 'absent' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                Abs
                            </span>
                        </div>
                    @endif
                @empty
                    <div class="p-12 text-center text-gray-300">
                        <i data-lucide="smile" class="w-10 h-10 mx-auto mb-2 opacity-20"></i>
                        <p class="text-xs font-bold uppercase">Aucune absence trouvée</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Weekly Attendance Tracking -->
    <div>
        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4">Suivi Hebdomadaire</h3>
        <div class="flex justify-between bg-white rounded-3xl p-5 border border-gray-100 shadow-sm">
            @php $days = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven']; @endphp
            @foreach($days as $day)
                <div class="flex flex-col items-center gap-2">
                    <div class="w-10 h-10 bg-indigo-50 rounded-2xl flex items-center justify-center border border-indigo-100 shadow-sm">
                        <i data-lucide="check" class="w-4 h-4 text-indigo-400"></i>
                    </div>
                    <span class="text-[10px] font-bold text-gray-500 uppercase">{{ $day }}</span>
                </div>
            @endforeach
        </div>
    </div>

</div>

<!-- Overriding Bottom Nav for Student (as per maquete) -->
@push('styles')
<style>
    .student-nav-item.active i { color: #4f46e5; }
    .student-nav-item.active span { color: #4f46e5; }
</style>
@endpush

@section('bottom_nav')
<nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 z-40 pb-2">
    <div class="flex items-center justify-around py-2">
        <a href="{{ route('mobile.student.dashboard') }}" class="flex flex-col items-center gap-1 px-4 py-2 text-indigo-600">
            <i data-lucide="home" class="w-6 h-6"></i>
            <span class="text-[10px] font-bold uppercase tracking-tight">Accueil</span>
        </a>
        <div class="px-2">
            <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center -mt-6 shadow-lg shadow-indigo-200 text-white active:scale-95 transition-all">
                <i data-lucide="file-plus" class="w-6 h-6 text-white"></i>
            </div>
        </div>
        <a href="#" class="flex flex-col items-center gap-1 px-4 py-2 text-gray-400">
            <i data-lucide="history" class="w-6 h-6"></i>
            <span class="text-[10px] font-bold uppercase tracking-tight">Historique</span>
        </a>
    </div>
</nav>
@endsection
@endsection
