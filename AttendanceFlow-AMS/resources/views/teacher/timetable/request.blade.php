@extends('layouts.dashboard')

@section('title', 'Demande de modification')
@section('page_title', 'Demande de modification de séance')

@section('content')
<div class="space-y-6">

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

    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-sm text-amber-900 flex items-start gap-3">
        <i data-lucide="info" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
        <div>
            <p class="font-bold">Toute modification de votre emploi du temps doit être validée par un administrateur.</p>
            <p class="text-xs mt-1">Renseignez la séance finale souhaitée. L'administrateur examinera la demande et vous serez notifié(e) de la décision.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('teacher.timetable.store-request') }}">
        @csrf

        <x-ui.section-card padding="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Action *</label>
                    <x-preline-select name="action" :options="['create' => 'Créer', 'update' => 'Modifier', 'delete' => 'Supprimer']" :allowBlank="false" :value="old('action', $session ? 'update' : 'create')" icon="settings" />
                </div>
                @if($session)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Séance concernée</label>
                    <input type="text" value="{{ $session->module->name }} — {{ $session->group->name }} · {{ $session->start_time->format('d/m/Y H:i') }}" disabled
                           class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg bg-gray-50 text-gray-700">
                    <input type="hidden" name="session_id" value="{{ $session->id }}">
                </div>
                @endif
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Module *</label>
                    <x-preline-select name="module_id" :options="$modules->pluck('name', 'id')" :allowBlank="false" :value="old('module_id', $session?->module_id)" icon="book" :hasSearch="true" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Type *</label>
                    <x-preline-select name="type" :options="['lecture' => 'CM', 'td' => 'TD', 'tp' => 'TP']" :allowBlank="false" :value="old('type', $session?->type ?? 'lecture')" icon="tag" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Groupe *</label>
                    <x-preline-select name="group_id" :options="$groups->pluck('name', 'id')" :allowBlank="false" :value="old('group_id', $session?->group_id)" icon="users" :hasSearch="true" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Date *</label>
                    <x-preline-datepicker name="date" :value="old('date', $session?->start_time?->toDateString())" icon="calendar" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Heure de début *</label>
                    <input type="time" name="start_time" value="{{ old('start_time', $session?->start_time?->format('H:i') ?? '09:00') }}" required
                           class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Heure de fin *</label>
                    <input type="time" name="end_time" value="{{ old('end_time', $session?->end_time?->format('H:i') ?? '11:00') }}" required
                           class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Salle (optionnel)</label>
                    <input type="text" name="room" value="{{ old('room', $session?->room) }}" placeholder="Ex : Salle 12"
                           class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Motif (optionnel mais recommandé)</label>
                <textarea name="reason" rows="3" placeholder="Expliquez la raison de votre demande…"
                          class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">{{ old('reason') }}</textarea>
            </div>
        </x-ui.section-card>

        <div class="mt-4 flex items-center justify-end gap-3">
            <a href="{{ route('teacher.timetable.index') }}" class="text-xs font-bold uppercase tracking-wider px-4 py-2.5 text-gray-700 bg-white hover:bg-gray-100 border border-gray-200 rounded-xl">Annuler</a>
            <button type="submit" class="text-xs font-bold uppercase tracking-wider px-5 py-2.5 text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors shadow-sm flex items-center gap-2">
                <i data-lucide="send" class="w-4 h-4"></i>
                Envoyer la demande
            </button>
        </div>
    </form>

</div>
@endsection
