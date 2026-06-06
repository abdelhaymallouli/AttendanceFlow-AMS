@extends('layouts.dashboard')

@section('title', 'Registre des présences')
@section('page_title', 'Registre des présences')

@section('content')
<div class="space-y-6"
     x-data="attendanceApp(@js($allSessionsData), '{{ $date }}')">

    <!-- HEADER -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-5 shadow-sm">
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center shadow-sm">
                    <i data-lucide="book-open" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h2 class="text-lg font-extrabold text-gray-800">Registre des présences</h2>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Consultez et modifiez les présences de toutes les classes pour une date donnée.
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <i data-lucide="calendar" class="w-4 h-4 text-blue-600"></i>
                <span class="text-sm font-semibold text-gray-700">
                    {{ \Carbon\Carbon::parse($date)->translatedFormat('l d F Y') }}
                </span>
            </div>
        </div>
    </div>

    <!-- STATS CARDS -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Séances</p>
                    <p class="text-2xl font-extrabold text-gray-800 mt-1">{{ $totals['sessions'] }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                    <i data-lucide="calendar-days" class="w-5 h-5 text-blue-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Présents</p>
                    <p class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $totals['present'] }}</p>
                </div>
                <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center">
                    <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Absents</p>
                    <p class="text-2xl font-extrabold text-red-600 mt-1">{{ $totals['absent'] }}</p>
                </div>
                <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center">
                    <i data-lucide="x-circle" class="w-5 h-5 text-red-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Retards</p>
                    <p class="text-2xl font-extrabold text-amber-600 mt-1">{{ $totals['late'] }}</p>
                </div>
                <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center">
                    <i data-lucide="clock-alert" class="w-5 h-5 text-amber-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- DATE FILTER -->
    <x-ui.section-card padding="p-4">
        <form method="GET" action="{{ route('admin.attendance.index') }}" id="adminDateForm" class="flex items-center gap-3 flex-wrap">
            <label class="text-sm font-semibold text-gray-700 whitespace-nowrap">Date :</label>
            <div class="max-w-[220px] flex-1">
                <x-preline-datepicker
                    name="date"
                    :value="$date"
                    onChange="this.form.submit()"
                    icon="calendar"
                />
            </div>
            <a href="{{ route('admin.attendance.index', ['date' => \Carbon\Carbon::today()->toDateString()]) }}"
               class="ml-auto text-xs font-bold uppercase tracking-wider px-3 py-2 text-blue-600 hover:bg-blue-50 border border-blue-200 rounded-lg transition-colors">
                <i data-lucide="rotate-ccw" class="w-3 h-3 inline -mt-0.5"></i>
                Aujourd'hui
            </a>
        </form>
    </x-ui.section-card>

    <!-- SESSIONS TABLE -->
    <x-ui.section-card padding="p-0">

        @if($allSessionsData->isEmpty())
            <x-ui.empty-state
                icon="calendar-x"
                title="Aucune séance programmée pour cette date"
                description="Sélectionnez une autre date ou créez de nouvelles séances depuis le menu Sessions."
            />
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-[10px] font-bold uppercase tracking-wider text-gray-500">
                            <th class="text-left px-4 py-3">Groupe</th>
                            <th class="text-left px-4 py-3">Module</th>
                            <th class="text-left px-4 py-3">Enseignant</th>
                            <th class="text-left px-4 py-3">Horaire</th>
                            <th class="text-center px-2 py-3">P</th>
                            <th class="text-center px-2 py-3">A</th>
                            <th class="text-center px-2 py-3">R</th>
                            <th class="text-center px-2 py-3">N.M.</th>
                            <th class="text-center px-2 py-3">Rempl.</th>
                            <th class="text-right px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($allSessionsData as $session)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 bg-indigo-100 text-indigo-600 rounded-md flex items-center justify-center">
                                            <i data-lucide="users" class="w-3.5 h-3.5"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-800">{{ $session['group'] }}</p>
                                            @if($session['group_filiere'])
                                                <p class="text-[10px] text-gray-400">{{ $session['group_filiere'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-sm font-medium text-gray-800">{{ $session['module'] }}</p>
                                    <p class="text-[10px] text-gray-400 uppercase tracking-wider">{{ $session['type'] }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-xs text-gray-600">{{ $session['teacher'] ?: '—' }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-xs font-mono text-gray-700">{{ $session['time'] }}</p>
                                    @if($session['is_active'])
                                        <span class="inline-flex items-center gap-1 text-[9px] font-bold uppercase tracking-wider text-emerald-700">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                            En cours
                                        </span>
                                    @elseif($session['is_past'])
                                        <span class="text-[9px] font-bold uppercase tracking-wider text-gray-400">Terminée</span>
                                    @else
                                        <span class="text-[9px] font-bold uppercase tracking-wider text-amber-600">Planifiée</span>
                                    @endif
                                </td>
                                <td class="px-2 py-3 text-center">
                                    <span class="inline-flex items-center justify-center min-w-[28px] h-6 px-2 rounded-md text-xs font-bold {{ $session['present'] > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-400' }}">
                                        {{ $session['present'] }}
                                    </span>
                                </td>
                                <td class="px-2 py-3 text-center">
                                    <span class="inline-flex items-center justify-center min-w-[28px] h-6 px-2 rounded-md text-xs font-bold {{ $session['absent'] > 0 ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-400' }}">
                                        {{ $session['absent'] }}
                                    </span>
                                </td>
                                <td class="px-2 py-3 text-center">
                                    <span class="inline-flex items-center justify-center min-w-[28px] h-6 px-2 rounded-md text-xs font-bold {{ $session['late'] > 0 ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-400' }}">
                                        {{ $session['late'] }}
                                    </span>
                                </td>
                                <td class="px-2 py-3 text-center">
                                    <span class="inline-flex items-center justify-center min-w-[28px] h-6 px-2 rounded-md text-xs font-bold {{ $session['unmarked'] > 0 ? 'bg-gray-200 text-gray-600' : 'bg-gray-100 text-gray-400' }}">
                                        {{ $session['unmarked'] }}
                                    </span>
                                </td>
                                <td class="px-2 py-3 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <div class="w-12 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                            <div class="h-full {{ $session['fill_rate'] >= 80 ? 'bg-emerald-500' : ($session['fill_rate'] >= 50 ? 'bg-amber-500' : 'bg-red-500') }}"
                                                 style="width: {{ $session['fill_rate'] }}%"></div>
                                        </div>
                                        <span class="text-[10px] font-bold text-gray-600">{{ $session['fill_rate'] }}%</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="inline-flex items-center gap-1">
                                        <a href="{{ $session['edit_url'] }}"
                                           class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-blue-700 hover:bg-blue-50 px-2 py-1.5 rounded-md transition-colors border border-blue-200"
                                           title="Modifier les présences">
                                            <i data-lucide="edit-3" class="w-3 h-3"></i>
                                            Éditer
                                        </a>
                                        <a href="{{ $session['consult_url'] }}"
                                           class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-gray-700 hover:bg-gray-100 px-2 py-1.5 rounded-md transition-colors border border-gray-200"
                                           title="Consulter en lecture seule">
                                            <i data-lucide="eye" class="w-3 h-3"></i>
                                            Consulter
                                        </a>
                                        <a href="{{ $session['qr_url'] }}"
                                           class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-indigo-700 hover:bg-indigo-50 px-2 py-1.5 rounded-md transition-colors border border-indigo-200"
                                           title="Ouvrir le workspace QR">
                                            <i data-lucide="qr-code" class="w-3 h-3"></i>
                                            QR
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    </x-ui.section-card>

    <!-- LEGEND -->
    @if(!$allSessionsData->isEmpty())
        <div class="flex items-center justify-center gap-6 text-[10px] font-bold uppercase tracking-wider text-gray-500 flex-wrap">
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 bg-emerald-500 rounded-full"></span> P = Présents</span>
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 bg-red-500 rounded-full"></span> A = Absents</span>
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 bg-amber-500 rounded-full"></span> R = Retards</span>
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 bg-gray-300 rounded-full"></span> N.M. = Non marqués</span>
        </div>
    @endif

</div>
@endsection
