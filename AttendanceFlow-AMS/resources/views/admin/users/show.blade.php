@extends('layouts.dashboard')

@section('title', $user->name . ' — Détails')
@section('page_title', $user->name)

@section('header_actions')
<div class="flex items-center gap-2">
    <a href="{{ route('admin.users.edit', $user->id) }}"
       class="text-sm font-medium text-gray-700 bg-white hover:bg-gray-100 border border-gray-200 px-4 py-2 rounded-lg transition-colors flex items-center">
        <i data-lucide="pencil" class="w-4 h-4 mr-1.5"></i> Modifier
    </a>
    <a href="{{ route('admin.users.index') }}" class="text-gray-500 hover:text-gray-800 transition-colors flex items-center text-sm">
        <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Retour
    </a>
</div>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Profile Card -->
    @php
        $avatarColors = [
            'admin' => 'bg-red-100 text-red-600',
            'teacher' => 'bg-indigo-100 text-indigo-600',
            'student' => 'bg-blue-100 text-blue-600',
        ];
        $roleLabels = ['admin' => 'Administrateur', 'teacher' => 'Formateur', 'student' => 'Étudiant'];
        $roleBadge = ['admin' => 'danger', 'teacher' => 'info', 'student' => 'default'];
    @endphp
    <x-ui.section-card padding="p-6">
        <div class="flex items-start gap-6">
            <div class="w-20 h-20 {{ $avatarColors[$role] ?? 'bg-gray-100 text-gray-600' }} rounded-2xl flex items-center justify-center flex-shrink-0">
                <span class="text-2xl font-bold">{{ substr($user->name, 0, 1) }}</span>
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-3">
                    <h3 class="text-xl font-bold text-gray-800">{{ $user->name }}</h3>
                    <x-ui.badge :type="$roleBadge[$role] ?? 'default'">{{ $roleLabels[$role] ?? $role }}</x-ui.badge>
                </div>
                <p class="text-sm text-gray-500 mt-1">{{ $user->email }}</p>
                @if($user->phone)
                    <p class="text-sm text-gray-500 mt-0.5 flex items-center gap-1">
                        <i data-lucide="phone" class="w-3.5 h-3.5"></i> {{ $user->phone }}
                    </p>
                @endif
            </div>
        </div>
    </x-ui.section-card>

    {{-- Student Details --}}
    @if($role === 'student' && $user->studentProfile)
        @php $sp = $user->studentProfile; @endphp

        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <x-ui.section-card padding="p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Matricule</p>
                <p class="text-lg font-extrabold text-gray-800 mt-0.5 font-mono">{{ $sp->matricule }}</p>
            </x-ui.section-card>
            <x-ui.section-card padding="p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Groupe</p>
                <p class="text-lg font-extrabold text-blue-600 mt-0.5">{{ $sp->group->name ?? '—' }}</p>
            </x-ui.section-card>
            <x-ui.section-card padding="p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Taux de présence</p>
                <p class="text-lg font-extrabold {{ ($stats['attendanceRate'] ?? 0) >= 70 ? 'text-green-600' : 'text-red-600' }} mt-0.5">{{ $stats['attendanceRate'] }}%</p>
            </x-ui.section-card>
            <x-ui.section-card padding="p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Absences</p>
                <p class="text-lg font-extrabold text-red-600 mt-0.5">{{ $stats['absent'] }}</p>
            </x-ui.section-card>
        </div>

        @if($sp->justifications->isNotEmpty())
        <x-ui.section-card title="Justifications" icon="file-text" padding="p-0">
            <div class="divide-y divide-gray-100">
                @foreach($sp->justifications->take(10) as $just)
                <div class="px-6 py-3 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $just->reason }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $just->session?->module?->name ?? '—' }} · {{ $just->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <x-ui.badge
                        :type="$just->status === 'approved' ? 'success' : ($just->status === 'rejected' ? 'danger' : 'warning')"
                        :text="$just->status === 'approved' ? 'Approuvée' : ($just->status === 'rejected' ? 'Rejetée' : 'En attente')"
                    />
                </div>
                @endforeach
            </div>
        </x-ui.section-card>
        @endif
    @endif

    {{-- Teacher Details --}}
    @if($role === 'teacher' && $user->teacherProfile)
        @php $tp = $user->teacherProfile; @endphp

        <div class="grid grid-cols-3 gap-3">
            <x-ui.section-card padding="p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Spécialité</p>
                <p class="text-lg font-extrabold text-indigo-600 mt-0.5">{{ $tp->specialty ?? '—' }}</p>
            </x-ui.section-card>
            <x-ui.section-card padding="p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Modules</p>
                <p class="text-lg font-extrabold text-gray-800 mt-0.5">{{ $tp->modules->count() }}</p>
            </x-ui.section-card>
            <x-ui.section-card padding="p-4">
                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-500">Groupes</p>
                <p class="text-lg font-extrabold text-gray-800 mt-0.5">{{ $tp->groups->count() }}</p>
            </x-ui.section-card>
        </div>

        @if($tp->modules->isNotEmpty())
        <x-ui.section-card title="Modules enseignés" icon="book-open" padding="p-0">
            <div class="divide-y divide-gray-100">
                @foreach($tp->modules as $module)
                <div class="px-6 py-3 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $module->name }}</p>
                        <p class="text-xs text-gray-500">{{ $module->code }} · Coeff. {{ $module->coefficient }}</p>
                    </div>
                    <span class="text-xs text-gray-400">{{ $module->total_hours }}h</span>
                </div>
                @endforeach
            </div>
        </x-ui.section-card>
        @endif
    @endif

    {{-- Admin: no extra details --}}
    @if($role === 'admin')
        <x-ui.section-card padding="p-6">
            <p class="text-sm text-gray-500">Cet administrateur a accès complet au système.</p>
        </x-ui.section-card>
    @endif

    <!-- Danger Zone -->
    @if(Auth::id() !== $user->id)
    <x-ui.section-card padding="p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-bold text-gray-800">Supprimer cet utilisateur</p>
                <p class="text-xs text-gray-500 mt-0.5">Cette action est irréversible et supprimera toutes les données associées.</p>
            </div>
            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Supprimer cet utilisateur et toutes ses données ?')">
                @csrf @method('DELETE')
                <button type="submit" class="text-xs font-bold uppercase tracking-wider px-4 py-2 text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors flex items-center gap-1.5">
                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Supprimer
                </button>
            </form>
        </div>
    </x-ui.section-card>
    @endif
</div>
@endsection
