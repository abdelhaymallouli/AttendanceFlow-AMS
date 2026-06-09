@extends('layouts.dashboard')

@section('title', 'Mes Étudiants')
@section('page_title', 'Mes Étudiants')

@section('content')
<div class="space-y-6">

    <x-ui.section-card padding="p-4">
        <form method="GET" action="{{ route('teacher.students.index') }}" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <x-preline-search name="search" :value="request('search')" icon="search" placeholder="Rechercher par nom, email ou matricule..." />
            </div>
        </form>
    </x-ui.section-card>

    <x-ui.section-card :overflow="true" padding="none">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-xs text-gray-500 uppercase">Étudiant</th>
                        <th class="px-6 py-4 text-xs text-gray-500 uppercase">Matricule</th>
                        <th class="px-6 py-4 text-xs text-gray-500 uppercase">Groupe</th>
                        <th class="px-6 py-4 text-xs text-gray-500 uppercase">Filière</th>
                        <th class="px-6 py-4 text-xs text-gray-500 uppercase">Téléphone</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($students as $student)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-bold text-blue-600">{{ substr($student->user->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">{{ $student->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $student->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-mono text-gray-600 bg-gray-100 px-2 py-1 rounded">{{ $student->matricule }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <x-ui.badge type="info">{{ $student->group->name ?? '—' }}</x-ui.badge>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $student->group->filiere?->name ?? '—' }}</td>
                        <td class="px-6 py-4">
                            @if($student->user->phone)
                                <span class="text-sm text-gray-800 flex items-center gap-1.5">
                                    <i data-lucide="phone" class="w-3.5 h-3.5 text-gray-400"></i>
                                    {{ $student->user->phone }}
                                </span>
                            @else
                                <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                            <i data-lucide="users" class="w-8 h-8 mx-auto mb-2 opacity-40"></i>
                            <p class="font-medium uppercase tracking-wider text-xs">Aucun étudiant trouvé</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.section-card>

    <div class="mt-4">
        {{ $students->links() }}
    </div>
</div>
@endsection
