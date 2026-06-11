@extends('layouts.dashboard')

@section('title', 'Créer une séance')
@section('page_title', 'Créer une nouvelle séance')

@section('header_actions')
<a href="{{ route('admin.sessions.index') }}" class="text-gray-500 hover:text-blue-600 transition-colors flex items-center">
    <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i> Retour au calendrier
</a>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden" x-data="sessionForm('09:00', '11:00')">
        <div class="p-6 border-b border-gray-200 bg-gray-50/50">
            <h3 class="text-lg font-semibold text-gray-800">Configuration de la séance</h3>
            <p class="text-sm text-gray-500">Remplissez les informations pour la nouvelle séance.</p>
        </div>

        <form method="POST" action="{{ route('admin.sessions.store') }}" class="p-6 md:p-8 space-y-6">
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Module Selection -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Module</label>
                    <select name="module_id" class="w-full ps-10 pe-4 py-3 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none bg-white shadow-sm">
                        <option value="" disabled>Choisir un module...</option>
                        @foreach($modules as $module)
                            <option value="{{ $module->id }}" @selected(old('module_id') == $module->id)>{{ $module->name }}</option>
                        @endforeach
                    </select>
                    @error('module_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- Teacher Selection -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Formateur</label>
                    <select name="teacher_id" class="w-full ps-10 pe-4 py-3 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none bg-white shadow-sm">
                        <option value="" disabled>Choisir un formateur...</option>
                        @foreach($teacherProfiles as $teacher)
                            <option value="{{ $teacher->id }}" @selected(old('teacher_id') == $teacher->id)>{{ $teacher->user->name }} ({{ $teacher->specialty }})</option>
                        @endforeach
                    </select>
                    @error('teacher_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- Group Selection -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Groupe</label>
                    <select name="group_id" class="w-full ps-10 pe-4 py-3 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none bg-white shadow-sm">
                        <option value="" disabled>Choisir un groupe...</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}" @selected(old('group_id') == $group->id)>{{ $group->name }}</option>
                        @endforeach
                    </select>
                    @error('group_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- Session Type -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Type de séance</label>
                    <select name="type" class="w-full ps-10 pe-4 py-3 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none bg-white shadow-sm">
                        <option value="" disabled>Choisir un type...</option>
                        <option value="lecture" @selected(old('type') == 'lecture')>Cours</option>
                        <option value="td" @selected(old('type') == 'td')>TD (Travaux Dirigés)</option>
                        <option value="tp" @selected(old('type') == 'tp')>TP (Travaux Pratiques)</option>
                    </select>
                    @error('type') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <hr class="border-gray-100">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Date -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Date</label>
                    <x-preline-datepicker
                        name="date"
                        :value="old('date', \Carbon\Carbon::today()->toDateString())"
                        icon="calendar"
                        placeholder="Choisir une date..."
                    />
                    @error('date') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- Start Time -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Heure de début</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-3 z-10">
                            <i data-lucide="clock" class="w-4 h-4 text-gray-400"></i>
                        </div>
                        <input type="time" x-model="startTime" name="start_time" required class="w-full ps-10 pe-4 py-3 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none bg-white shadow-sm">
                    </div>
                    @error('start_time') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- End Time -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Heure de fin</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-3 z-10">
                            <i data-lucide="clock" class="w-4 h-4 text-gray-400"></i>
                        </div>
                        <input type="time" x-model="endTime" name="end_time" required class="w-full ps-10 pe-4 py-3 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none bg-white shadow-sm">
                    </div>
                    @error('end_time') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Computed Duration -->
            <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100 flex items-center justify-between">
                <span class="text-sm font-medium text-gray-700">Durée calculée :</span>
                <span class="font-bold text-blue-700" x-text="durationText"></span>
            </div>

            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.sessions.index') }}" class="px-6 py-3 rounded-xl text-gray-700 font-medium hover:bg-gray-100 transition-colors">
                    Annuler
                </a>
                <button type="submit" class="px-8 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 hover:shadow-lg active:scale-95 transition-all flex items-center">
                    <i data-lucide="save" class="w-5 h-5 mr-2"></i>
                    Créer la séance
                </button>
            </div>
        </form>
    </div>
</div>

@endsection