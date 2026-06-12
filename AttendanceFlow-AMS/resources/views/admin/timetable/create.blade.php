@extends('layouts.dashboard')

@section('title', 'Nouvelle séance')
@section('page_title', 'Planifier une séance')

@section('content')
<div class="space-y-6"
     x-data="timetableForm(@js([
        'modules'   => $modules->keyBy('id'),
        'groups'    => $groups->keyBy('id'),
        'teachers'  => $teachers->keyBy('id'),
         'storeUrl' => route('admin.timetable.store'),
     ]))" x-init="init()">

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

    <form method="POST" action="{{ route('admin.timetable.store') }}" @submit.prevent="submit($event)" id="timetableForm">
        @csrf

        <x-ui.section-card padding="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Module *</label>
                    <select name="module_id" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        <option value="" disabled>Choisir un module…</option>
                        @foreach($modules as $module)
                            <option value="{{ $module->id }}" @selected(old('module_id') == $module->id)>{{ $module->name }}</option>
                        @endforeach
                    </select>
                    @error('module_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Type *</label>
                    <select name="type" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        <option value="" disabled>Choisir un type…</option>
                        <option value="lecture" @selected(old('type', 'lecture') == 'lecture')>Cours (CM)</option>
                        <option value="td" @selected(old('type', 'lecture') == 'td')>TD</option>
                        <option value="tp" @selected(old('type', 'lecture') == 'tp')>TP</option>
                    </select>
                    @error('type') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Groupe *</label>
                    <select name="group_id" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        <option value="" disabled>Choisir un groupe…</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}" @selected(old('group_id') == $group->id)>{{ $group->filiere?->name ? $group->filiere->name.' · ' : '' }}{{ $group->name }}</option>
                        @endforeach
                    </select>
                    @error('group_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Enseignant *</label>
                    <select name="teacher_profile_id" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        <option value="" disabled>Choisir un enseignant…</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" @selected(old('teacher_profile_id') == $teacher->id)>{{ $teacher->user->name }}</option>
                        @endforeach
                    </select>
                    @error('teacher_profile_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Date *</label>
                    <input 
                        type="date" 
                        name="date" 
                        value="{{ old('date', $defaultDate) }}" 
                        @change="checkConflicts()"
                        class="block w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white shadow-sm cursor-pointer"
                    >
                    @error('date') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Salle (optionnel)</label>
                    <input type="text" name="room" value="{{ old('room') }}" placeholder="Ex : Salle 12"
                           class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Heure de début *</label>
                    <input type="time" name="start_time" value="{{ old('start_time', '09:00') }}" required
                           @change="checkConflicts()"
                           class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    @error('start_time') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Heure de fin *</label>
                    <input type="time" name="end_time" value="{{ old('end_time', '11:00') }}" required
                           @change="checkConflicts()"
                           class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    @error('end_time') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-4 flex items-center gap-2">
                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input type="hidden" name="publish" value="0">
                    <input type="checkbox" name="publish" value="1" checked
                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    Publier immédiatement (visible par les étudiants)
                </label>
            </div>

            <!-- Live conflict feedback -->
            <div x-show="issues.length > 0" x-cloak class="mt-4 space-y-2">
                <template x-for="issue in issues" :key="issue.code">
                    <div :class="issue.level === 'error' ? 'bg-red-50 border-red-200 text-red-800' : 'bg-amber-50 border-amber-200 text-amber-800'"
                         class="border rounded-lg p-3 text-xs flex items-start gap-2">
                        <i :data-lucide="issue.level === 'error' ? 'x-circle' : 'alert-triangle'" class="w-4 h-4 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <p class="font-bold" x-text="issue.code === 'teacher_conflict' ? 'Conflit enseignant' : (issue.code === 'group_conflict' ? 'Conflit groupe' : (issue.code === 'room_conflict' ? 'Conflit salle' : (issue.code === 'module_hours_exceeded' ? 'Dépassement heures module' : (issue.code === 'module_hours_fully_used' ? 'Module complet' : 'Information'))))"></p>
                            <p x-text="issue.message"></p>
                        </div>
                    </div>
                </template>
            </div>
        </x-ui.section-card>

        <div class="mt-4 flex items-center justify-end gap-3">
            <a href="{{ route('admin.timetable.index') }}"
               class="text-xs font-bold uppercase tracking-wider px-4 py-2.5 text-gray-700 bg-white hover:bg-gray-100 border border-gray-200 rounded-xl transition-colors">
                Annuler
            </a>
            <button type="submit"
                    :disabled="hasErrors"
                    :class="hasErrors ? 'bg-gray-300 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700'"
                    class="text-xs font-bold uppercase tracking-wider px-5 py-2.5 text-white rounded-xl transition-colors shadow-sm hover:shadow-md flex items-center gap-2">
                <i data-lucide="save" class="w-4 h-4"></i>
                Planifier la séance
            </button>
        </div>
    </form>

</div>

@push('scripts')
<script>
function timetableForm(cfg) {
    return {
        modules: cfg.modules,
        groups: cfg.groups,
        teachers: cfg.teachers,
        storeUrl: cfg.storeUrl,
        issues: [],
        get hasErrors() { return this.issues.some(i => i.level === 'error'); },

        init() {
            // Auto-check after a short delay (so Preline can init selects)
            setTimeout(() => this.checkConflicts(), 500);
        },

        async checkConflicts() {
            const data = this.collect();
            if (!data.module_id || !data.group_id || !data.teacher_profile_id || !data.date || !data.start_time || !data.end_time) {
                this.issues = [];
                return;
            }
            try {
                const params = new URLSearchParams(data);
                const res = await fetch(`{{ route('admin.timetable.validate') }}?${params.toString()}`, {
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                });
                const json = await res.json();
                this.issues = json.issues || [];
            } catch (e) {
                this.issues = [];
            }
        },

        collect() {
            return {
                module_id: this.$root.querySelector('[name=module_id]')?.value || '',
                group_id: this.$root.querySelector('[name=group_id]')?.value || '',
                teacher_profile_id: this.$root.querySelector('[name=teacher_profile_id]')?.value || '',
                type: this.$root.querySelector('[name=type]')?.value || '',
                date: this.$root.querySelector('[name=date]')?.value || '',
                start_time: this.$root.querySelector('[name=start_time]')?.value || '',
                end_time: this.$root.querySelector('[name=end_time]')?.value || '',
                room: this.$root.querySelector('[name=room]')?.value || '',
            };
        },

        submit(event) {
            // Force Preline selects to commit their values to the underlying <select>
            document.querySelectorAll('select[name]').forEach(s => s.dispatchEvent(new Event('change', { bubbles: true })));
            if (this.hasErrors) {
                alert('Impossible de planifier : corrigez les conflits ci-dessus.');
                return;
            }
            event.target.submit();
        },
    };
}
</script>
@endpush

@endsection
