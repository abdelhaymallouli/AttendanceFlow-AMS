@extends('layouts.dashboard')

@section('title', 'Emploi du temps')
@section('page_title', 'Emploi du temps — ' . $weekStart->translatedFormat('d M') . ' → ' . $weekEnd->translatedFormat('d M Y'))

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

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 text-sm">
            <div class="font-semibold flex items-center gap-2 mb-1">
                <i data-lucide="alert-octagon" class="w-4 h-4"></i> Conflits détectés :
            </div>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- HEADER -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-5 shadow-sm">
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center shadow-sm">
                    <i data-lucide="calendar-range" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h2 class="text-lg font-extrabold text-gray-800">Emploi du temps</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Vue hebdomadaire de toutes les séances de l'école.</p>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('admin.timetable.requests') }}"
                   class="relative inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider px-3.5 py-2 rounded-xl text-white bg-amber-500 hover:bg-amber-600 transition-colors shadow-sm">
                    <i data-lucide="inbox" class="w-3.5 h-3.5"></i>
                    Demandes
                    @if($totalRequests > 0)
                        <span class="ml-1 bg-white text-amber-700 text-[10px] font-extrabold px-1.5 py-0.5 rounded-full">{{ $totalRequests }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.timetable.create') }}"
                   class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider px-3.5 py-2 rounded-xl text-white bg-blue-600 hover:bg-blue-700 transition-colors shadow-sm">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    Nouvelle séance
                </a>
            </div>
        </div>
    </div>

    <!-- WEEK NAV -->
    <x-ui.section-card padding="p-3">
        <div class="flex items-center justify-between gap-3 flex-wrap">
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.timetable.index', ['week' => $prevWeek]) }}"
                   class="inline-flex items-center justify-center gap-1.5 h-9 text-xs font-bold uppercase tracking-wider text-gray-700 hover:bg-gray-100 px-3 rounded-lg border border-transparent transition-colors">
                    <i data-lucide="chevron-left" class="w-4 h-4"></i> Précédent
                </a>
                <a href="{{ route('admin.timetable.index', ['week' => $today]) }}"
                   class="inline-flex items-center justify-center gap-1.5 h-9 text-xs font-bold uppercase tracking-wider text-blue-700 hover:bg-blue-50 border border-blue-200 px-3 rounded-lg transition-colors shadow-sm">
                    <i data-lucide="calendar-days" class="w-4 h-4"></i> Aujourd'hui
                </a>
                <a href="{{ route('admin.timetable.index', ['week' => $nextWeek]) }}"
                   class="inline-flex items-center justify-center gap-1.5 h-9 text-xs font-bold uppercase tracking-wider text-gray-700 hover:bg-gray-100 px-3 rounded-lg border border-transparent transition-colors">
                    Suivant <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </a>
            </div>
            <p class="text-sm font-bold text-gray-700">
                Semaine du {{ $weekStart->translatedFormat('d M') }} au {{ $weekEnd->translatedFormat('d M Y') }}
            </p>
        </div>
    </x-ui.section-card>

    <!-- WEEK GRID -->
    <x-ui.section-card padding="p-0">
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="text-left px-3 py-3 w-24 text-[10px] font-bold uppercase tracking-wider text-gray-500"></th>
                        @foreach($grid as $date => $_)
                            @php $day = \Carbon\Carbon::parse($date); @endphp
                            <th class="text-left px-3 py-3 min-w-[160px] {{ $day->isToday() ? 'bg-blue-50/60' : '' }}">
                                <p class="text-[10px] font-bold uppercase tracking-wider {{ $day->isToday() ? 'text-blue-600' : 'text-gray-500' }}">
                                    {{ $day->translatedFormat('l') }}
                                </p>
                                <p class="text-base font-extrabold {{ $day->isToday() ? 'text-blue-700' : 'text-gray-800' }}">
                                    {{ $day->format('d/m') }}
                                </p>
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
                                    <div class="bg-white border border-gray-200 hover:border-blue-300 hover:shadow-sm rounded-lg p-2.5 transition-all group">
                                        <div class="flex items-start justify-between gap-1.5">
                                            <div class="min-w-0 flex-1">
                                                <p class="text-xs font-bold text-gray-800 truncate">{{ $session->module->name ?? '—' }}</p>
                                                <p class="text-[10px] text-gray-500 mt-0.5 font-mono">
                                                    {{ $session->start_time->format('H:i') }} – {{ $session->end_time->format('H:i') }}
                                                </p>
                                                <p class="text-[10px] text-gray-500 truncate mt-1">
                                                    <i data-lucide="users" class="w-3 h-3 inline -mt-0.5"></i>
                                                    {{ $session->group->name ?? '—' }}
                                                    · {{ ucfirst($session->type) }}
                                                </p>
                                                <p class="text-[10px] text-gray-500 truncate">
                                                    <i data-lucide="user" class="w-3 h-3 inline -mt-0.5"></i>
                                                    {{ $session->teacherProfile?->user?->name ?? '—' }}
                                                </p>
                                                @if($session->room)
                                                    <p class="text-[10px] text-gray-500 truncate">
                                                        <i data-lucide="door-open" class="w-3 h-3 inline -mt-0.5"></i> {{ $session->room }}
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="flex flex-col gap-1.5 flex-shrink-0">
                                                <a href="{{ route('admin.timetable.edit', $session->id) }}"
                                                   class="text-blue-600 hover:bg-blue-50 p-1.5 rounded-lg border border-gray-100 bg-gray-50/50 flex items-center justify-center transition-colors" title="Modifier">
                                                    <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                                </a>
                                                <form method="POST" action="{{ route('admin.timetable.destroy', $session->id) }}"
                                                      onsubmit="return confirm('Supprimer cette séance ?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:bg-red-50 p-1.5 rounded-lg border border-gray-100 bg-gray-50/50 flex items-center justify-center transition-colors w-full" title="Supprimer">
                                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
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

    <!-- PENDING REQUESTS PREVIEW -->
    @if($pendingRequests->isNotEmpty())
    <x-ui.section-card padding="p-0">
        <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between bg-amber-50/40">
            <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                <i data-lucide="inbox" class="w-4 h-4 text-amber-600"></i>
                Demandes en attente
            </h3>
            <a href="{{ route('admin.timetable.requests') }}" class="text-xs text-blue-600 hover:underline">Voir tout →</a>
        </div>
        <div class="divide-y divide-gray-100">
            @foreach($pendingRequests as $req)
                <div class="px-5 py-3 flex items-center justify-between gap-3 hover:bg-gray-50">
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold text-gray-800">
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider px-1.5 py-0.5 rounded mr-1
                                {{ $req->action === 'create' ? 'bg-emerald-50 text-emerald-700' : ($req->action === 'update' ? 'bg-blue-50 text-blue-700' : 'bg-red-50 text-red-700') }}">
                                {{ $req->action === 'create' ? 'Création' : ($req->action === 'update' ? 'Modification' : 'Suppression') }}
                            </span>
                            {{ $req->session->module->name ?? $req->proposed_data['module_id'] ?? '—' }}
                        </p>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Par <span class="font-semibold">{{ $req->teacherProfile->user->name }}</span>
                            · {{ $req->created_at->diffForHumans() }}
                        </p>
                    </div>
                    <a href="{{ route('admin.timetable.requests', ['status' => 'pending']) }}" class="text-xs font-bold uppercase text-blue-600 hover:bg-blue-50 px-3 py-1.5 rounded-md">Examiner</a>
                </div>
            @endforeach
        </div>
    </x-ui.section-card>
    @endif

</div>

@endsection
