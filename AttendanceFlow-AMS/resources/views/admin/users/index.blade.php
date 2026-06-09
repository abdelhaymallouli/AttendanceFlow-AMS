@extends('layouts.dashboard')

@section('title', 'Gestion des Utilisateurs')
@section('page_title', 'Gestion des Utilisateurs')

@section('header_actions')
<div class="flex items-center gap-2">
    <a href="{{ route('admin.users.create-student') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors flex items-center text-sm shadow-sm">
        <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i> Ajouter un utilisateur
    </a>
</div>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Search + Role Tabs -->
    <x-ui.section-card padding="p-4">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <form method="GET" action="{{ route('admin.users.index') }}">
                    <input type="hidden" name="role" value="{{ $activeRole }}">
                    <x-preline-search name="search" :value="request('search')" icon="search" placeholder="Rechercher par nom, email, téléphone ou matricule..." />
                </form>
            </div>
        </div>

        <!-- Role Tabs -->
        <div class="flex items-center gap-1 mt-4 border-t border-gray-100 pt-4">
            @php
                $tabs = [
                    '' => ['label' => 'Tous', 'icon' => 'users'],
                    'admin' => ['label' => 'Administrateurs', 'icon' => 'shield'],
                    'teacher' => ['label' => 'Formateurs', 'icon' => 'briefcase'],
                    'student' => ['label' => 'Étudiants', 'icon' => 'graduation-cap'],
                ];
            @endphp
            @foreach($tabs as $key => $tab)
                <a href="{{ route('admin.users.index', array_merge(request()->query(), ['role' => $key ?: null])) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider transition-colors
                          {{ $activeRole === ($key ?: 'all') ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
                    <i data-lucide="{{ $tab['icon'] }}" class="w-3.5 h-3.5"></i>
                    {{ $tab['label'] }}
                </a>
            @endforeach
        </div>
    </x-ui.section-card>

    <!-- Users Table -->
    <x-ui.section-card :overflow="true" padding="none">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-xs text-gray-500 uppercase">Utilisateur</th>
                        <th class="px-6 py-4 text-xs text-gray-500 uppercase">Rôle</th>
                        <th class="px-6 py-4 text-xs text-gray-500 uppercase">Téléphone</th>
                        <th class="px-6 py-4 text-xs text-gray-500 uppercase">Détails</th>
                        <th class="px-6 py-4 text-right text-xs text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($users as $user)
                    @php
                        $roleName = $user->getRoleNames()->first();
                        $roleColors = [
                            'admin' => 'bg-red-100 text-red-700',
                            'teacher' => 'bg-indigo-100 text-indigo-700',
                            'student' => 'bg-blue-100 text-blue-700',
                        ];
                        $roleLabels = [
                            'admin' => 'Admin',
                            'teacher' => 'Formateur',
                            'student' => 'Étudiant',
                        ];
                        $avatarColors = [
                            'admin' => 'bg-red-100 text-red-600',
                            'teacher' => 'bg-indigo-100 text-indigo-600',
                            'student' => 'bg-blue-100 text-blue-600',
                        ];
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 {{ $avatarColors[$roleName] ?? 'bg-gray-100 text-gray-600' }} rounded-full flex items-center justify-center">
                                    <span class="text-sm font-bold">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider rounded-full {{ $roleColors[$roleName] ?? '' }}">
                                {{ $roleLabels[$roleName] ?? $roleName }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $user->phone ?? '—' }}</td>
                        <td class="px-6 py-4 text-xs text-gray-500">
                            @if($roleName === 'student' && $user->studentProfile)
                                <span class="font-mono">{{ $user->studentProfile->matricule }}</span>
                                <span class="text-gray-400 mx-1">·</span>
                                {{ $user->studentProfile->group->name ?? '—' }}
                            @elseif($roleName === 'teacher' && $user->teacherProfile)
                                {{ $user->teacherProfile->specialty ?? '—' }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-1">
                                <a href="{{ route('admin.users.show', $user->id) }}"
                                   class="text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition-colors" title="Voir">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                   class="text-gray-600 hover:bg-gray-100 p-2 rounded-lg transition-colors" title="Modifier">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </a>
                                @if(Auth::id() !== $user->id)
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:bg-red-50 p-2 rounded-lg transition-colors" title="Supprimer">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                            <i data-lucide="users" class="w-8 h-8 mx-auto mb-2 opacity-40"></i>
                            <p class="font-medium uppercase tracking-wider text-xs">Aucun utilisateur trouvé</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.section-card>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection
