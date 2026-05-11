@extends('mobile.layouts.app')

@section('title', 'Tableau de bord - Enseignant')
@section('header_title', 'Teacher Portal')

@section('content')
<div class="px-4 pt-4 pb-20"> <!-- Extra padding for bottom nav -->

    <!-- Today's Sessions Banner (Based on real data) -->
    <div class="bg-gradient-to-r from-blue-500 to-green-500 rounded-2xl p-5 mb-5 text-white shadow-lg shadow-blue-100">
        <p class="text-xs text-blue-100 mb-1">Planning du jour</p>
        <h2 class="text-xl font-bold mb-1">{{ count($sessions) }} Sessions</h2>
        @if(count($sessions) > 0)
            <p class="text-sm text-blue-100 italic">Prochaine: {{ $sessions[0]['module']['name'] }} ({{ $sessions[0]['group']['name'] }})</p>
        @else
            <p class="text-sm text-blue-100 italic">Aucune session prévue</p>
        @endif
        
        <div class="mt-4 inline-flex items-center gap-2 bg-white bg-opacity-20 text-white text-sm font-medium px-4 py-2 rounded-xl border border-white/10 backdrop-blur-sm">
            <i data-lucide="calendar" class="w-4 h-4"></i>
            {{ \Carbon\Carbon::now()->format('d M Y') }}
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-3 gap-3 mb-6">
        <div class="bg-white rounded-2xl p-4 text-center border border-gray-100 shadow-sm">
            <p class="text-xl font-bold text-gray-800">92%</p>
            <p class="text-[10px] text-gray-500 uppercase font-bold mt-1 tracking-tight">Taux global</p>
        </div>
        <div class="bg-white rounded-2xl p-4 text-center border border-gray-100 shadow-sm">
            <p class="text-xl font-bold text-gray-800">124</p>
            <p class="text-[10px] text-gray-500 uppercase font-bold mt-1 tracking-tight">Étudiants</p>
        </div>
        <div class="bg-white rounded-2xl p-4 text-center border border-gray-100 shadow-sm">
            <p class="text-xl font-bold text-amber-500">3</p>
            <p class="text-[10px] text-gray-500 uppercase font-bold mt-1 tracking-tight">En attente</p>
        </div>
    </div>

    <!-- Today's Classes List -->
    <div class="mb-5">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-bold text-gray-800">Séances d'aujourd'hui</h3>
            <span class="text-xs text-blue-600 font-medium">Tout voir</span>
        </div>
        
        <div class="space-y-3">
            @forelse($sessions as $session)
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center gap-4 transition-all active:scale-[0.98] hover:border-blue-100">
                    <div class="w-12 h-12 bg-blue-50 rounded-xl flex flex-col items-center justify-center flex-shrink-0 border border-blue-100">
                        <span class="text-[10px] font-bold text-blue-700 uppercase">{{ substr($session['start_time'], 0, 5) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-gray-800 truncate">{{ $session['module']['name'] ?? 'Module' }}</p>
                        <p class="text-xs text-gray-500 font-medium">{{ $session['group']['name'] ?? 'Groupe' }} • S{{ $session['room'] ?? '201' }}</p>
                    </div>
                    <a href="{{ route('mobile.attendance.flash', $session['id']) }}" 
                       class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-md shadow-blue-100">
                        <i data-lucide="clipboard-check" class="w-5 h-5 text-white"></i>
                    </a>
                </div>
            @empty
                <div class="py-12 text-center text-gray-400 bg-white rounded-2xl border border-gray-100 border-dashed">
                    <i data-lucide="calendar-x" class="w-10 h-10 mx-auto mb-2 opacity-20"></i>
                    <p class="text-xs font-medium">Aucune séance prévue pour aujourd'hui</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Secondary Navigation / Justifications -->
    <div class="mb-5">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-bold text-gray-800">Justifications récentes</h3>
            <a href="#" class="text-xs text-blue-600 font-semibold">Voir tout</a>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm">
            <div class="divide-y divide-gray-50">
                <div class="flex items-center gap-3 p-4">
                    <div class="w-9 h-9 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0 text-amber-600">
                        <i data-lucide="clock" class="w-4 h-4"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-gray-800">Yassine El Amrani</p>
                        <p class="text-[10px] text-gray-500 uppercase font-bold tracking-tight">Médical · Groupe 101</p>
                    </div>
                    <span class="text-xs bg-amber-100 text-amber-700 px-2 py-1 rounded-lg font-bold">En attente</span>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection