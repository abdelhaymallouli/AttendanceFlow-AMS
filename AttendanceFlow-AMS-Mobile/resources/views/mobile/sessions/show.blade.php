@extends('mobile.layouts.app')

@section('title', 'Détails du Pointage - Solicode AMS')
@section('header_title', 'Attendance Review')

@section('content')
<div class="px-4 pt-4 pb-24">

    <!-- Session Context Card -->
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-sm font-bold text-gray-800">{{ $session['module']['name'] ?? 'Module' }}</h2>
            <p class="text-xs text-gray-500">Groupe {{ $session['group']['name'] ?? 'GP' }} • {{ \Carbon\Carbon::now()->format('d M Y') }}</p>
        </div>
        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center border border-blue-100">
            <i data-lucide="info" class="w-5 h-5 text-blue-600"></i>
        </div>
    </div>

    <!-- Results Summary -->
    <div class="grid grid-cols-3 gap-3 mb-6">
        <div class="bg-green-50 rounded-2xl p-4 text-center border border-green-100">
            <p class="text-xl font-bold text-green-700">{{ count(array_filter($attendanceData, fn($r) => $r['status'] == 'present')) }}</p>
            <p class="text-[10px] text-green-600 uppercase font-bold mt-1">Présents</p>
        </div>
        <div class="bg-red-50 rounded-2xl p-4 text-center border border-red-100">
            <p class="text-xl font-bold text-red-700">{{ count(array_filter($attendanceData, fn($r) => $r['status'] == 'absent')) }}</p>
            <p class="text-[10px] text-red-600 uppercase font-bold mt-1">Absents</p>
        </div>
        <div class="bg-amber-50 rounded-2xl p-4 text-center border border-amber-100">
            <p class="text-xl font-bold text-amber-700">{{ count(array_filter($attendanceData, fn($r) => $r['status'] == 'late')) }}</p>
            <p class="text-[10px] text-amber-600 uppercase font-bold mt-1">Retards</p>
        </div>
    </div>

    <!-- Student List Table style -->
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm mb-6">
        <div class="p-4 bg-gray-50/50 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Liste d'appel</h3>
            <span class="text-[10px] bg-gray-200 text-gray-700 px-2 py-0.5 rounded-full font-bold">{{ count($attendanceData) }} TOT</span>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($attendanceData as $record)
                <div class="flex items-center gap-3 p-4">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 font-bold text-xs
                        {{ $record['status'] == 'present' ? 'bg-green-100 text-green-700 border border-green-200' : ($record['status'] == 'absent' ? 'bg-red-100 text-red-700 border border-red-200' : 'bg-amber-100 text-amber-700 border border-amber-200') }}">
                        {{ substr($record['student_profile']['user']['name'] ?? 'S', 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-gray-800">{{ $record['student_profile']['user']['name'] ?? 'Étudiant' }}</p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tight">ID: {{ $record['student_profile']['matricule'] ?? $record['student_profile_id'] }}</p>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-1 rounded-lg uppercase 
                        {{ $record['status'] == 'present' ? 'bg-green-100 text-green-700' : ($record['status'] == 'absent' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                        {{ $record['status'] }}
                    </span>
                </div>
            @empty
                <div class="p-12 text-center text-gray-400">
                    <i data-lucide="info" class="w-10 h-10 mx-auto mb-2 opacity-20"></i>
                    <p class="text-xs font-medium">Aucun pointage enregistré</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Actions -->
    <div class="grid grid-cols-2 gap-3 mb-8">
        <a href="{{ route('mobile.sessions') }}" class="py-4 bg-gray-100 text-gray-600 font-bold rounded-2xl text-center text-sm transition-all active:scale-95 flex items-center justify-center gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Retour
        </a>
        <a href="{{ route('mobile.attendance.flash', $session['id']) }}" class="py-4 bg-blue-600 text-white font-bold rounded-2xl text-center text-sm shadow-lg shadow-blue-100 transition-all active:scale-95 flex items-center justify-center gap-2">
            <i data-lucide="edit-3" class="w-4 h-4"></i>
            Modifier
        </a>
    </div>

</div>
@endsection
