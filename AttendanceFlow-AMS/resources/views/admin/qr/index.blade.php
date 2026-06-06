@extends('layouts.dashboard')

@section('title', 'QR Émargement — Toutes les classes')
@section('page_title', 'Lancement QR par groupe')

@section('content')
<div class="space-y-6" x-data="{ date: '{{ $date }}' }">

    <div class="bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-200 rounded-xl p-5 shadow-sm">
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center shadow-sm">
                    <i data-lucide="qr-code" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h2 class="text-lg font-extrabold text-gray-800">QR Émargement — Toutes les classes</h2>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Lancez ou reprenez un émargement QR pour n'importe quel groupe de l'école.
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <i data-lucide="calendar" class="w-4 h-4 text-indigo-600"></i>
                <span class="text-sm font-semibold text-gray-700">
                    {{ \Carbon\Carbon::parse($date)->translatedFormat('l d F Y') }}
                </span>
                <span class="ml-2 inline-flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">
                    <i data-lucide="layers" class="w-3 h-3"></i>
                    {{ $totalSessions }} séance(s)
                </span>
            </div>
        </div>
    </div>

    <x-ui.section-card padding="p-4">
        <form method="GET" action="{{ route('admin.qr.index') }}" id="adminQrDateForm" class="flex items-center gap-3">
            <label class="text-sm font-semibold text-gray-700 whitespace-nowrap">Date :</label>
            <div class="max-w-[220px] flex-1">
                <x-preline-datepicker
                    name="date"
                    :value="$date"
                    onChange="this.form.submit()"
                    icon="calendar"
                />
            </div>
            <a href="{{ route('admin.qr.index', ['date' => now()->toDateString()]) }}"
               class="ml-auto text-xs font-bold uppercase tracking-wider px-3 py-2 text-indigo-600 hover:bg-indigo-50 border border-indigo-200 rounded-lg transition-colors">
                <i data-lucide="rotate-ccw" class="w-3 h-3 inline -mt-0.5"></i>
                Aujourd'hui
            </a>
        </form>
    </x-ui.section-card>

    @if($allGroups->isEmpty())
        <x-ui.section-card>
            <x-ui.empty-state
                icon="users"
                title="Aucun groupe n'est encore configuré."
                description="Créez d'abord des groupes depuis le module Sessions pour pouvoir les sélectionner ici."
            />
        </x-ui.section-card>
    @else
        <div class="space-y-4">
            @foreach($allGroups as $group)
                <x-ui.section-card padding="p-0">
                    <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/60 flex items-center justify-between gap-3 flex-wrap">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center">
                                <i data-lucide="users" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-800">
                                    Groupe {{ $group['name'] }}
                                    @if($group['filiere'])
                                        <span class="text-gray-400 font-normal">· {{ $group['filiere'] }}</span>
                                    @endif
                                </p>
                                <p class="text-[11px] text-gray-500 mt-0.5">
                                    {{ $group['sessions']->count() }} séance(s) ce jour
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($group['sessions']->isEmpty())
                        <div class="px-5 py-6 text-center text-xs text-gray-400">
                            <i data-lucide="calendar-x" class="w-4 h-4 inline -mt-0.5 mr-1"></i>
                            Aucune séance prévue pour ce groupe le {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}.
                        </div>
                    @else
                        <div class="divide-y divide-gray-100">
                            @foreach($group['sessions'] as $session)
                                <div class="px-5 py-3 flex items-center justify-between gap-3 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center gap-3 min-w-0 flex-1">
                                        <div class="text-center min-w-[70px]">
                                            <p class="text-sm font-bold text-gray-800">{{ $session['start_time'] }}</p>
                                            <p class="text-[10px] text-gray-400">→ {{ $session['end_time'] }}</p>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-semibold text-gray-800 truncate">
                                                {{ $session['module'] ?? '—' }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-0.5 flex items-center gap-2 flex-wrap">
                                                <span class="inline-flex items-center gap-1">
                                                    <i data-lucide="user" class="w-3 h-3"></i>
                                                    {{ $session['teacher'] ?? '—' }}
                                                </span>
                                                <span class="inline-flex items-center gap-1">
                                                    <i data-lucide="tag" class="w-3 h-3"></i>
                                                    {{ ucfirst($session['type']) }}
                                                </span>
                                                @if($session['is_active'])
                                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider px-1.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                                        En cours
                                                    </span>
                                                @elseif($session['is_past'])
                                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider px-1.5 py-0.5 rounded-full bg-gray-100 text-gray-500 border border-gray-200">
                                                        Terminée
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider px-1.5 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-100">
                                                        Planifiée
                                                    </span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <a href="{{ $session['show_url'] }}"
                                       class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider px-3.5 py-2 rounded-xl transition-colors shadow-sm
                                              {{ $session['is_active'] ? 'bg-emerald-600 hover:bg-emerald-700 text-white' : 'bg-indigo-600 hover:bg-indigo-700 text-white' }}">
                                        <i data-lucide="qr-code" class="w-3.5 h-3.5"></i>
                                        {{ $session['is_past'] ? 'Consulter' : 'Lancer QR' }}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </x-ui.section-card>
            @endforeach
        </div>
    @endif

</div>
@endsection
