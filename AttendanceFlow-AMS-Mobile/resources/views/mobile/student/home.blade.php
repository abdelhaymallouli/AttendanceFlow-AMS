@extends('mobile.layouts.app')

@section('title', 'Mon Assiduité')
@section('header_title', 'AttendanceFlow')

@section('content')
<div class="px-4 pt-4 pb-24">

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-2xl p-4 mb-4 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    <!-- Profile Card -->
    <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-3xl p-5 mb-6 text-white shadow-lg shadow-blue-100">
        <div class="flex items-center gap-4 mb-5">
            <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/10">
                <span class="text-xl font-bold">{{ substr(session('mobile_user.name', 'S'), 0, 1) }}</span>
            </div>
            <div>
                <p class="text-blue-200 text-[10px] uppercase font-bold tracking-widest mb-0.5">Stagiaire</p>
                <h2 class="text-lg font-bold">{{ session('mobile_user.name', 'Étudiant') }}</h2>
                <p class="text-blue-200 text-xs font-medium">
                    Matricule: {{ session('mobile_student_matricule', 'N/A') }}
                </p>
            </div>
        </div>

        <!-- Attendance Rate -->
        <div class="bg-white/10 rounded-2xl p-4 border border-white/5 backdrop-blur-md">
            <div class="flex justify-between text-xs mb-2">
                <span class="text-blue-100 font-bold uppercase tracking-tighter">Taux de présence</span>
                <span class="font-black text-lg">{{ $stats['attendance_rate'] ?? 0 }}%</span>
            </div>
            <div class="bg-blue-900/30 rounded-full h-3 overflow-hidden">
                <div class="bg-white rounded-full h-3 transition-all duration-1000" style="width:{{ $stats['attendance_rate'] ?? 0 }}%"></div>
            </div>
            <div class="flex justify-between items-center mt-3">
                <p class="text-[10px] text-blue-200 font-bold uppercase">{{ $stats['absences'] ?? 0 }} absences</p>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-3 gap-3 mb-6">
        <div class="bg-white rounded-2xl p-4 text-center border border-gray-100 shadow-sm">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter mb-1">Taux</p>
            <p class="text-xl font-black text-blue-600">{{ $stats['attendance_rate'] ?? 0 }}%</p>
        </div>
        <div class="bg-white rounded-2xl p-4 text-center border border-gray-100 shadow-sm">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter mb-1">Absences</p>
            <p class="text-xl font-black text-red-500">{{ $stats['absences'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-2xl p-4 text-center border border-gray-100 shadow-sm">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter mb-1">En attente</p>
            <p class="text-xl font-black text-amber-500">{{ $stats['pending_justifications'] ?? 0 }}</p>
        </div>
    </div>

    <!-- QR Scan CTA -->
    <a href="{{ route('mobile.scan') }}" class="block bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-3xl p-5 mb-4 shadow-md shadow-blue-200 active:scale-95 transition-all">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                <i data-lucide="qr-code" class="w-6 h-6"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold">Scanner le QR</p>
                <p class="text-[10px] text-blue-100 font-medium">Valider ta présence</p>
            </div>
            <i data-lucide="chevron-right" class="w-5 h-5 opacity-80"></i>
        </div>
    </a>

    <!-- Absences CTA -->
    <a href="{{ route('mobile.absences') }}" class="block bg-white border border-gray-100 rounded-3xl p-5 mb-4 shadow-sm active:scale-95 transition-all">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center flex-shrink-0">
                <i data-lucide="calendar-x" class="w-6 h-6 text-red-500"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-gray-800">Mes absences</p>
                <p class="text-[10px] text-gray-500 font-medium">Consulter l'historique</p>
            </div>
            <i data-lucide="chevron-right" class="w-5 h-5 text-gray-300"></i>
        </div>
    </a>

    <!-- Justify CTA -->
    <a href="{{ route('mobile.justifications.create') }}" class="block bg-white border border-gray-100 rounded-3xl p-5 mb-4 shadow-sm active:scale-95 transition-all">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center flex-shrink-0">
                <i data-lucide="file-plus" class="w-6 h-6 text-blue-600"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-gray-800">Justifier une absence</p>
                <p class="text-[10px] text-gray-500 font-medium">Déposer un justificatif</p>
            </div>
            <i data-lucide="chevron-right" class="w-5 h-5 text-gray-300"></i>
        </div>
    </a>

    <!-- Recent Absences -->
    @php
        $recentAbsences = collect($attendance ?? [])->filter(fn($r) => in_array($r['status'] ?? '', ['absent_unexcused', 'absent_excused', 'absent']))->take(5);
    @endphp
    @if($recentAbsences->count())
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Absences récentes</h3>
            <a href="{{ route('mobile.absences') }}" class="text-xs text-blue-600 font-bold">Tout voir</a>
        </div>
        <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-sm">
            <div class="divide-y divide-gray-50">
                @foreach($recentAbsences as $record)
                    <div class="flex items-center gap-4 p-4">
                        <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i data-lucide="calendar-x" class="w-5 h-5 text-red-500"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-gray-400 font-bold uppercase">{{ $record['session']['module']['name'] ?? 'Séance' }}</p>
                            <p class="text-sm font-bold text-gray-800">{{ \Carbon\Carbon::parse($record['date'])->format('d M Y') }}</p>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-1 rounded-lg uppercase bg-red-100 text-red-700">
                            Abs
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

</div>
@endsection
