@extends('layouts.dashboard')

@section('title', 'Sélection de séance')
@section('page_title', 'Marquer la présence')

@section('content')
<div class="space-y-6">
    <x-ui.section-card padding="p-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs flex items-center justify-center font-bold shadow-sm shadow-blue-500/30">1</span>
                    Choisir une séance
                </h3>
                <p class="text-xs text-gray-500 mt-1 ml-8">Sélectionnez la séance pour démarrer l'émargement ou modifier les statuts.</p>
            </div>

            <form method="GET" action="{{ route('teacher.attendance.index') }}" id="date-filter-form" class="contents">
                <x-date-filter
                    label="Date :"
                    name="date"
                    value="{{ $date }}"
                    onChange="this.form.submit()"
                    showTodayLink="true"
                    todayUrl="{{ route('teacher.attendance.index') }}"
                />
            </form>
        </div>

        @if (session('success'))
            <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 flex items-center gap-2 text-sm">
                <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($sessionsData as $session)
                @php
                    $status = $session['status'];
                    $colors = match ($status) {
                        'in_progress' => ['border-emerald-200', 'bg-emerald-50/40', 'text-emerald-700', 'En cours', 'bg-emerald-600', 'Démarrer / Continuer', 'arrow-right'],
                        'upcoming' => ['border-blue-200', 'bg-blue-50/40', 'text-blue-700', 'Planifiée', 'bg-blue-600', 'Préparer', 'arrow-right'],
                        'closed' => ['border-red-300', 'bg-red-50/60', 'text-red-700', 'Clôturée', 'bg-red-200', 'Non modifiable', 'lock'],
                    };
                @endphp

                @if ($status === 'closed')
                    <div class="border-2 {{ $colors[0] }} {{ $colors[1] }} rounded-xl p-4 opacity-90 cursor-not-allowed relative overflow-hidden">
                        <div class="absolute top-2 right-2">
                            <span class="inline-flex items-center gap-1 text-[10px] font-extrabold uppercase tracking-wider px-2 py-1 rounded-full bg-red-200 text-red-800">
                                <i data-lucide="lock" class="w-3 h-3"></i>
                                {{ $colors[3] }}
                            </span>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-red-100 text-red-600 shrink-0 mt-0.5">
                                <i data-lucide="clock" class="w-5 h-5"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-sm text-red-900">
                                    {{ $session['time'] }}
                                    <span class="font-normal text-xs ml-1 text-red-600">({{ $session['duration'] }}h)</span>
                                </p>
                                <p class="text-xs text-red-700 mt-0.5 font-semibold truncate">
                                    {{ $session['module'] }} · Groupe {{ $session['group'] }}
                                </p>
                                <div class="mt-3 flex items-center gap-1.5 text-[10px] text-red-700 font-bold">
                                    <i data-lucide="check-circle-2" class="w-3 h-3"></i>
                                    {{ $session['record_count'] }}/{{ $session['student_count'] }} stagiaires marqués
                                </div>
                                <p class="text-[10px] text-red-500 mt-1 italic">
                                    Séance terminée — feuille d'émargement clôturée.
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ $session['url'] }}"
                       class="border-2 {{ $colors[0] }} {{ $colors[1] }} rounded-xl p-4 hover:shadow-md transition-all group block">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center {{ $status === 'in_progress' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700' }} shrink-0 mt-0.5">
                                @if ($status === 'in_progress')
                                    <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                                @else
                                    <i data-lucide="calendar-clock" class="w-5 h-5"></i>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-sm text-gray-800">
                                    {{ $session['time'] }}
                                    <span class="font-normal text-xs ml-1 text-gray-400">({{ $session['duration'] }}h)</span>
                                </p>
                                <p class="text-xs {{ $colors[2] }} mt-0.5 font-semibold">
                                    {{ $colors[3] }}
                                </p>
                                <p class="text-xs text-gray-700 mt-1 truncate">
                                    <span class="font-semibold">{{ $session['module'] }}</span>
                                    <span class="text-gray-300 mx-1">·</span>
                                    Groupe {{ $session['group'] }}
                                </p>
                                @if ($session['record_count'] > 0)
                                    <div class="mt-2 flex items-center gap-1.5 text-[10px] text-emerald-700 font-bold">
                                        <i data-lucide="users" class="w-3 h-3"></i>
                                        {{ $session['record_count'] }}/{{ $session['student_count'] }} déjà pointés
                                    </div>
                                @endif
                                <div class="mt-3 inline-flex items-center gap-1 text-[10px] font-extrabold uppercase tracking-wider {{ $colors[4] }} text-white px-2.5 py-1.5 rounded-lg">
                                    <i data-lucide="{{ $colors[6] }}" class="w-3 h-3"></i>
                                    {{ $colors[5] }}
                                </div>
                            </div>
                        </div>
                    </a>
                @endif
            @empty
                <div class="col-span-full">
                    <x-ui.empty-state
                        icon="calendar-x"
                        title="Aucune séance prévue à cette date"
                        class="border-dashed"
                    />
                </div>
            @endforelse
        </div>
    </x-ui.section-card>
</div>
@endsection
