@extends('layouts.dashboard')

@section('title', $isConsult ? 'Consultation' : 'Édition' . ' — ' . $session->module->name)
@section('page_title', $session->module->name . ' — Groupe ' . $session->group->name)

@section('content')

@php
    $sessionDate = \Carbon\Carbon::parse($session->start_time)->toDateString();
@endphp

<div class="space-y-6" x-data="registryEditor(@js([
    'sessionId'       => $session->id,
    'csrf'            => csrf_token(),
    'updateRowUrl'    => route('admin.attendance.update-row', $session->id),
    'storeUrl'        => route('admin.attendance.store', $session->id),
    'consultUrl'      => route('admin.attendance.show', ['session' => $session->id, 'mode' => 'consult']),
    'editUrl'         => route('admin.attendance.show', ['session' => $session->id]),
    'backUrl'         => route('admin.attendance.index', ['date' => $sessionDate]),
    'isConsult'       => $isConsult,
    'stats'           => $stats,
    'total'           => $students->count(),
]))" x-init="init()">

    <!-- SESSION HEADER -->
    <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
        <div class="flex items-start justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center shadow-sm flex-shrink-0">
                    <i data-lucide="clipboard-check" class="w-6 h-6 text-white"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-extrabold text-gray-800 truncate">
                        {{ $session->module->name }}
                        <span class="opacity-50">·</span>
                        Groupe {{ $session->group->name }}
                    </p>
                    <p class="text-xs text-gray-500 mt-0.5 flex items-center gap-3 flex-wrap">
                        <span class="flex items-center gap-1">
                            <i data-lucide="clock" class="w-3 h-3"></i>
                            {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}
                        </span>
                        <span class="flex items-center gap-1">
                            <i data-lucide="user" class="w-3 h-3"></i>
                            {{ $session->teacherProfile?->user?->name ?? '—' }}
                        </span>
                        <span class="flex items-center gap-1">
                            <i data-lucide="tag" class="w-3 h-3"></i>
                            {{ ucfirst($session->type) }}
                        </span>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                @if($isConsult)
                    <a href="{{ route('admin.attendance.show', ['session' => $session->id]) }}"
                       class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider px-3.5 py-2 rounded-xl text-white bg-blue-600 hover:bg-blue-700 transition-colors shadow-sm">
                        <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                        Passer en édition
                    </a>
                @else
                    <a href="{{ route('admin.attendance.show', ['session' => $session->id, 'mode' => 'consult']) }}"
                       class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider px-3.5 py-2 rounded-xl text-gray-700 bg-white hover:bg-gray-100 border border-gray-200 transition-colors">
                        <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                        Consulter
                    </a>
                @endif
                <a href="{{ route('admin.sessions.qr.show', $session->id) }}"
                   target="_blank"
                   class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider px-3.5 py-2 rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-sm">
                    <i data-lucide="qr-code" class="w-3.5 h-3.5"></i>
                    Workspace QR
                </a>
            </div>
        </div>
    </div>

    <!-- TOAST -->
    <div x-show="toast.show" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed top-4 right-4 z-50 max-w-sm">
        <div :class="toast.type === 'error' ? 'bg-red-50 border-red-200 text-red-800' : 'bg-emerald-50 border-emerald-200 text-emerald-800'"
             class="border rounded-xl p-3 shadow-lg flex items-center gap-2 text-sm font-medium">
            <i :data-lucide="toast.type === 'error' ? 'alert-circle' : 'check-circle-2'" class="w-4 h-4"></i>
            <span x-text="toast.message"></span>
        </div>
    </div>

    <!-- STATS BAR -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="bg-white border border-gray-200 rounded-xl p-3 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Présents</p>
            <p class="text-2xl font-extrabold text-emerald-600 mt-0.5" x-text="stats.present"></p>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-3 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Absents</p>
            <p class="text-2xl font-extrabold text-red-600 mt-0.5" x-text="stats.absent"></p>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-3 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Retards</p>
            <p class="text-2xl font-extrabold text-amber-600 mt-0.5" x-text="stats.late"></p>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-3 shadow-sm">
            <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Non marqués</p>
            <p class="text-2xl font-extrabold text-gray-600 mt-0.5" x-text="stats.unmarked"></p>
        </div>
    </div>

    <!-- CONTROLS (edit mode only) -->
    @if(!$isConsult)
    <x-ui.section-card padding="p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher un étudiant</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                    </div>
                    <input x-model="searchQuery" @input="filterRows()" type="text"
                           class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-gray-50 hover:bg-white transition-colors"
                           placeholder="Nom ou matricule…">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Actions rapides</label>
                <div class="flex space-x-2">
                    <button @click="markAll('present')" type="button"
                            class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-3 rounded-lg transition-colors text-sm flex items-center justify-center shadow-sm">
                        <i data-lucide="check-circle-2" class="w-4 h-4 mr-1.5"></i>
                        Tous présents
                    </button>
                    <button @click="markAll('late')" type="button"
                            class="flex-1 bg-amber-500 hover:bg-amber-600 text-white font-medium py-2 px-3 rounded-lg transition-colors text-sm flex items-center justify-center shadow-sm">
                        <i data-lucide="clock-alert" class="w-4 h-4 mr-1.5"></i>
                        Tous en retard
                    </button>
                    <button @click="markAll(null)" type="button"
                            class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-3 rounded-lg transition-colors text-sm flex items-center justify-center border border-gray-200">
                        <i data-lucide="rotate-ccw" class="w-4 h-4 mr-1.5"></i>
                        Réinitialiser
                    </button>
                </div>
            </div>
        </div>
    </x-ui.section-card>
    @endif

    <!-- REGISTRY TABLE -->
    <form @if(!$isConsult) @submit.prevent="saveAll()" @endif id="registryForm" action="{{ route('admin.attendance.store', $session) }}" method="POST">
        @csrf

        <x-ui.section-card :overflow="true" padding="none">
            @if($students->isEmpty())
                <x-ui.empty-state
                    icon="users"
                    title="Aucun étudiant dans ce groupe."
                />
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr class="text-[10px] font-bold uppercase tracking-wider text-gray-500">
                                <th class="text-left px-4 py-3 w-12">#</th>
                                <th class="text-left px-4 py-3 w-32">Matricule</th>
                                <th class="text-left px-4 py-3">Nom complet</th>
                                @if($isConsult)
                                    <th class="text-center px-2 py-3 w-24">Statut</th>
                                    <th class="text-center px-2 py-3 w-24">Pointage</th>
                                    <th class="text-left px-4 py-3">Note</th>
                                @else
                                    <th class="text-center px-2 py-3 w-32">Présent</th>
                                    <th class="text-center px-2 py-3 w-32">Absent</th>
                                    <th class="text-center px-2 py-3 w-32">Retard</th>
                                    <th class="text-left px-4 py-3 w-48">Note</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($students as $student)
                                @php
                                    $currentRecord = $existingRecords->get($student->id);
                                    $currentStatus = $currentRecord?->status;
                                    $currentNote   = $currentRecord?->note;
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors"
                                    data-student-name="{{ strtolower($student->user->name) }}"
                                    data-student-matricule="{{ strtolower($student->matricule) }}">
                                    <td class="px-4 py-3 text-xs text-gray-400 font-mono">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3 text-xs font-mono text-gray-600">{{ $student->matricule }}</td>
                                    <td class="px-4 py-3">
                                        <p class="text-sm font-semibold text-gray-800">{{ $student->user->name }}</p>
                                        <p class="text-[10px] text-gray-400">{{ $student->user->email }}</p>
                                    </td>

                                    @if($isConsult)
                                        <td class="px-2 py-3 text-center">
                                            @if($currentStatus === 'present')
                                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    <i data-lucide="check" class="w-3 h-3"></i> Présent
                                                </span>
                                            @elseif($currentStatus === 'absent' || $currentStatus === 'absent_unexcused' || $currentStatus === 'absent_excused')
                                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md text-[10px] font-bold uppercase bg-red-50 text-red-700 border border-red-200">
                                                    <i data-lucide="x" class="w-3 h-3"></i> Absent
                                                </span>
                                            @elseif($currentStatus === 'late')
                                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md text-[10px] font-bold uppercase bg-amber-50 text-amber-700 border border-amber-200">
                                                    <i data-lucide="clock-alert" class="w-3 h-3"></i> Retard
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md text-[10px] font-bold uppercase bg-gray-100 text-gray-500 border border-gray-200">
                                                    <i data-lucide="minus" class="w-3 h-3"></i> N.M.
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-2 py-3 text-center">
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-500">
                                                @if($currentRecord?->check_in_method === 'qr') QR
                                                @elseif($currentRecord?->check_in_method === 'manual') Manuel
                                                @else —
                                                @endif
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-xs text-gray-600 italic">
                                            {{ $currentNote ?: '—' }}
                                        </td>
                                    @else
                                        <td class="px-2 py-3 text-center">
                                            <input type="radio" name="attendance[{{ $student->id }}][status]" value="present"
                                                   @checked(old("attendance.{$student->id}.status", $currentStatus) === 'present')
                                                   class="w-4 h-4 text-emerald-600 border-gray-300 focus:ring-emerald-500 cursor-pointer">
                                        </td>
                                        <td class="px-2 py-3 text-center">
                                            <input type="radio" name="attendance[{{ $student->id }}][status]" value="absent"
                                                   @checked(old("attendance.{$student->id}.status", $currentStatus) === 'absent' || $currentStatus === 'absent_unexcused' || $currentStatus === 'absent_excused')
                                                   class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500 cursor-pointer">
                                        </td>
                                        <td class="px-2 py-3 text-center">
                                            <input type="radio" name="attendance[{{ $student->id }}][status]" value="late"
                                                   @checked(old("attendance.{$student->id}.status", $currentStatus) === 'late')
                                                   class="w-4 h-4 text-amber-600 border-gray-300 focus:ring-amber-500 cursor-pointer">
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="text" name="attendance[{{ $student->id }}][note]"
                                                   value="{{ old("attendance.{$student->id}.note", $currentNote) }}"
                                                   placeholder="Note…"
                                                   class="w-full px-2 py-1 text-xs border border-gray-200 rounded-md focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none"
                                                   maxlength="500">
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-ui.section-card>

        @if(!$isConsult && !$students->isEmpty())
            <div class="mt-4 flex items-center justify-end gap-3">
                <a href="{{ route('admin.attendance.show', ['session' => $session->id, 'mode' => 'consult']) }}"
                   class="text-xs font-bold uppercase tracking-wider px-4 py-2.5 text-gray-700 bg-white hover:bg-gray-100 border border-gray-200 rounded-xl transition-colors">
                    Annuler
                </a>
                <button type="submit"
                        class="text-xs font-bold uppercase tracking-wider px-5 py-2.5 text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors shadow-sm hover:shadow-md flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Enregistrer les présences
                </button>
            </div>
        @endif
    </form>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 text-sm font-medium flex items-center gap-2">
            <i data-lucide="check-circle-2" class="w-4 h-4"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- BACK LINK -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.attendance.index', ['date' => $sessionDate]) }}"
           class="inline-flex items-center gap-1.5 text-xs text-gray-500 hover:text-gray-800 transition-colors border border-gray-200 bg-white px-3.5 py-2 rounded-xl font-semibold">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            Retour au registre
        </a>
    </div>

</div>

@push('scripts')
<script>
function registryEditor(cfg) {
    return {
        sessionId: cfg.sessionId,
        csrf: cfg.csrf,
        updateRowUrl: cfg.updateRowUrl,
        storeUrl: cfg.storeUrl,
        isConsult: cfg.isConsult,
        searchQuery: '',
        stats: { ...cfg.stats },
        total: cfg.total,
        toast: { show: false, type: 'success', message: '' },

        init() {
            // Refresh lucide icons after Alpine renders the table
            this.$nextTick(() => {
                if (window.lucide) window.lucide.createIcons();
            });
        },

        filterRows() {
            const q = this.searchQuery.trim().toLowerCase();
            this.$root.querySelectorAll('tbody tr[data-student-name]').forEach(row => {
                const name = row.dataset.studentName || '';
                const matricule = row.dataset.studentMatricule || '';
                row.style.display = (!q || name.includes(q) || matricule.includes(q)) ? '' : 'none';
            });
        },

        markAll(status) {
            if (!status && !confirm('Réinitialiser toutes les présences ?')) return;
            this.$root.querySelectorAll('tbody tr[data-student-name]').forEach(row => {
                row.querySelectorAll('input[type="radio"]').forEach(r => r.checked = false);
                if (status) {
                    const target = row.querySelector(`input[type="radio"][value="${status}"]`);
                    if (target) target.checked = true;
                }
            });
        },

        saveAll() {
            document.getElementById('registryForm').submit();
        },

        showToast(message, type = 'success') {
            this.toast = { show: true, type, message };
            if (window.lucide) this.$nextTick(() => window.lucide.createIcons());
            setTimeout(() => { this.toast.show = false; }, 3500);
        },
    };
}
</script>
@endpush

@endsection
