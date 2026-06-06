@extends('layouts.dashboard')

@section('title', 'Mon emploi du temps')
@section('page_title', 'Mon emploi du temps')

@section('content')

@php
    $prevWeek = (clone $weekStart)->subWeek()->toDateString();
    $nextWeek = (clone $weekStart)->addWeek()->toDateString();
    $today    = now()->startOfWeek()->toDateString();
@endphp

<div class="space-y-6">

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 text-sm font-medium flex items-center gap-2">
            <i data-lucide="check-circle-2" class="w-4 h-4"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-5 shadow-sm">
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center shadow-sm">
                    <i data-lucide="calendar-range" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h2 class="text-lg font-extrabold text-gray-800">Mon emploi du temps</h2>
                    <p class="text-xs text-gray-500 mt-0.5">
                        @if($group)
                            Groupe <span class="font-bold text-gray-800">{{ $group->name }}</span>
                            @if($group->filerie) · {{ $group->filiere->name }} @endif
                        @else
                            Aucun groupe assigné.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <x-ui.section-card padding="p-3">
        <div class="flex items-center justify-between gap-3 flex-wrap">
            <div class="flex items-center gap-2">
                <a href="{{ route('student.timetable.index', ['week' => $prevWeek]) }}" class="inline-flex items-center gap-1 text-xs font-bold uppercase tracking-wider text-gray-700 hover:bg-gray-100 px-3 py-2 rounded-lg transition-colors">
                    <i data-lucide="chevron-left" class="w-4 h-4"></i> Précédent
                </a>
                <a href="{{ route('student.timetable.index', ['week' => $today]) }}" class="inline-flex items-center gap-1 text-xs font-bold uppercase tracking-wider text-blue-700 hover:bg-blue-50 border border-blue-200 px-3 py-2 rounded-lg transition-colors">
                    Aujourd'hui
                </a>
                <a href="{{ route('student.timetable.index', ['week' => $nextWeek]) }}" class="inline-flex items-center gap-1 text-xs font-bold uppercase tracking-wider text-gray-700 hover:bg-gray-100 px-3 py-2 rounded-lg transition-colors">
                    Suivant <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </a>
            </div>
            <p class="text-sm font-bold text-gray-700">Semaine du {{ $weekStart->translatedFormat('d M') }} au {{ $weekEnd->translatedFormat('d M Y') }}</p>
        </div>
    </x-ui.section-card>

    <x-ui.section-card padding="p-0">
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="text-left px-3 py-3 w-24 text-[10px] font-bold uppercase tracking-wider text-gray-500"></th>
                        @foreach($grid as $date => $_)
                            @php $day = \Carbon\Carbon::parse($date); @endphp
                            <th class="text-left px-3 py-3 min-w-[160px] {{ $day->isToday() ? 'bg-blue-50/60' : '' }}">
                                <p class="text-[10px] font-bold uppercase tracking-wider {{ $day->isToday() ? 'text-blue-600' : 'text-gray-500' }}">{{ $day->translatedFormat('l') }}</p>
                                <p class="text-base font-extrabold {{ $day->isToday() ? 'text-blue-700' : 'text-gray-800' }}">{{ $day->format('d/m') }}</p>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td class="align-top px-3 py-3 text-[10px] font-bold uppercase tracking-wider text-gray-500">Séances</td>
                        @foreach($grid as $date => $sessions)
                            @php $day = \Carbon\Carbon::parse($date); @endphp
                            <td class="align-top px-3 py-3 space-y-2 {{ $day->isToday() ? 'bg-blue-50/30' : '' }}">
                                @forelse($sessions as $session)
                                    <div class="bg-white border border-gray-200 rounded-lg p-2.5">
                                        <p class="text-xs font-bold text-gray-800 truncate">{{ $session->module->name ?? '—' }}</p>
                                        <p class="text-[10px] text-gray-500 mt-0.5 font-mono">{{ $session->start_time->format('H:i') }} – {{ $session->end_time->format('H:i') }}</p>
                                        <p class="text-[10px] text-gray-500 truncate mt-1">
                                            <i data-lucide="user" class="w-3 h-3 inline -mt-0.5"></i>
                                            {{ $session->teacherProfile?->user?->name ?? '—' }}
                                        </p>
                                        <p class="text-[10px] text-gray-500 truncate">
                                            <i data-lucide="tag" class="w-3 h-3 inline -mt-0.5"></i>
                                            {{ ucfirst($session->type) }}
                                            @if($session->room) · 🚪 {{ $session->room }} @endif
                                        </p>
                                    </div>
                                @empty
                                    <p class="text-[10px] text-gray-300 italic text-center py-4">—</p>
                                @endforelse
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </x-ui.section-card>

</div>
@endsection
