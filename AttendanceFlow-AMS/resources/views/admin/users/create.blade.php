@extends('layouts.dashboard')

@section('title', $role === 'student' ? 'Ajouter un étudiant' : 'Ajouter un formateur')
@section('page_title', $role === 'student' ? 'Nouvel étudiant' : 'Nouveau formateur')

@section('header_actions')
<a href="{{ route('admin.users.index') }}" class="text-gray-500 hover:text-gray-800 transition-colors flex items-center text-sm">
    <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Retour
</a>
@endsection

@section('content')
<div class="max-w-2xl">

    <!-- Role Switcher -->
    <div class="flex items-center gap-2 mb-6">
        <a href="{{ route('admin.users.create-student') }}"
           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wider transition-colors
                  {{ $role === 'student' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
            <i data-lucide="graduation-cap" class="w-3.5 h-3.5"></i> Étudiant
        </a>
        <a href="{{ route('admin.users.create-teacher') }}"
           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wider transition-colors
                  {{ $role === 'teacher' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
            <i data-lucide="briefcase" class="w-3.5 h-3.5"></i> Formateur
        </a>
    </div>

    <x-ui.section-card padding="p-6">
        <form method="POST" action="{{ $role === 'student' ? route('admin.users.store-student') : route('admin.users.store-teacher') }}" class="space-y-5">
            @csrf

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
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-3.5 py-2.5 text-sm bg-white border border-gray-200 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all"
                           placeholder="Nom et prénom">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-3.5 py-2.5 text-sm bg-white border border-gray-200 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all"
                           placeholder="email@exemple.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Téléphone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           class="w-full px-3.5 py-2.5 text-sm bg-white border border-gray-200 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all"
                           placeholder="+212 6XX XXX XXX">
                </div>

                @if($role === 'student')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Matricule *</label>
                    <input type="text" name="matricule" value="{{ old('matricule') }}" required
                           class="w-full px-3.5 py-2.5 text-sm bg-white border border-gray-200 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all"
                           placeholder="Ex: DEV101">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Groupe *</label>
                    <select name="group_id" required
                            class="w-full px-3.5 py-2.5 text-sm bg-white border border-gray-200 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                        <option value="">Choisir un groupe...</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}" @selected(old('group_id') == $group->id)>
                                {{ $group->filiere?->name ? $group->filiere->name.' · ' : '' }}{{ $group->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                @if($role === 'teacher')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Spécialité</label>
                    <input type="text" name="specialty" value="{{ old('specialty') }}"
                           class="w-full px-3.5 py-2.5 text-sm bg-white border border-gray-200 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all"
                           placeholder="Ex: Développement Web">
                </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Mot de passe *</label>
                    <input type="password" name="password" required
                           class="w-full px-3.5 py-2.5 text-sm bg-white border border-gray-200 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all"
                           placeholder="Min. 6 caractères">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirmer le mot de passe *</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-3.5 py-2.5 text-sm bg-white border border-gray-200 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all"
                           placeholder="Retapez le mot de passe">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.users.index') }}"
                   class="text-xs font-bold uppercase tracking-wider px-4 py-2.5 text-gray-700 bg-white hover:bg-gray-100 border border-gray-200 rounded-xl transition-colors">
                    Annuler
                </a>
                <button type="submit"
                        class="text-xs font-bold uppercase tracking-wider px-5 py-2.5 text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors shadow-sm flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Créer {{ $role === 'student' ? "l'étudiant" : 'le formateur' }}
                </button>
            </div>
        </form>
    </x-ui.section-card>
</div>
@endsection
