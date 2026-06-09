@extends('layouts.dashboard')

@section('title', 'Modifier — ' . $user->name)
@section('page_title', 'Modifier ' . $user->name)

@section('header_actions')
<a href="{{ route('admin.users.show', $user->id) }}" class="text-gray-500 hover:text-gray-800 transition-colors flex items-center text-sm">
    <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Retour
</a>
@endsection

@section('content')
<div class="max-w-2xl">
    <x-ui.section-card padding="p-6">
        <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-5">
            @csrf @method('PUT')

            @if ($errors->any())
            <div class="p-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nom complet *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-3.5 py-2.5 text-sm bg-white border border-gray-200 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email *</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-3.5 py-2.5 text-sm bg-white border border-gray-200 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Téléphone</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                           class="w-full px-3.5 py-2.5 text-sm bg-white border border-gray-200 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all"
                           placeholder="+212 6XX XXX XXX">
                </div>

                {{-- Student fields --}}
                @if($role === 'student' && $user->studentProfile)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Matricule *</label>
                    <input type="text" name="matricule" value="{{ old('matricule', $user->studentProfile->matricule) }}" required
                           class="w-full px-3.5 py-2.5 text-sm bg-white border border-gray-200 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Groupe *</label>
                    <select name="group_id" required
                            class="w-full px-3.5 py-2.5 text-sm bg-white border border-gray-200 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}" @selected(old('group_id', $user->studentProfile->group_id) == $group->id)>
                                {{ $group->filiere?->name ? $group->filiere->name.' · ' : '' }}{{ $group->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Teacher fields --}}
                @if($role === 'teacher' && $user->teacherProfile)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Spécialité</label>
                    <input type="text" name="specialty" value="{{ old('specialty', $user->teacherProfile->specialty) }}"
                           class="w-full px-3.5 py-2.5 text-sm bg-white border border-gray-200 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all"
                           placeholder="Ex: Développement Web">
                </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nouveau mot de passe</label>
                    <input type="password" name="password"
                           class="w-full px-3.5 py-2.5 text-sm bg-white border border-gray-200 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all"
                           placeholder="Laisser vide pour conserver">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirmer le mot de passe</label>
                    <input type="password" name="password_confirmation"
                           class="w-full px-3.5 py-2.5 text-sm bg-white border border-gray-200 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.users.show', $user->id) }}"
                   class="text-xs font-bold uppercase tracking-wider px-4 py-2.5 text-gray-700 bg-white hover:bg-gray-100 border border-gray-200 rounded-xl transition-colors">
                    Annuler
                </a>
                <button type="submit"
                        class="text-xs font-bold uppercase tracking-wider px-5 py-2.5 text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors shadow-sm flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Enregistrer
                </button>
            </div>
        </form>
    </x-ui.section-card>
</div>
@endsection
