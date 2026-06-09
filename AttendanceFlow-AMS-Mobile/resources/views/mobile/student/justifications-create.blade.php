@extends('mobile.layouts.app')

@section('title', 'Nouvelle Justification')
@section('header_title', 'Justifier')

@section('content')
<div class="px-4 pt-4 pb-24" x-data="justificationForm()">

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-2xl p-4 mb-4 text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif

    <h2 class="text-lg font-bold text-gray-800 mb-1">Nouvelle Justification</h2>
    <p class="text-xs text-gray-500 mb-6">Justifie ton absence en soumettant un justificatif.</p>

    @if(count($absentSessions) > 0)
        <form action="{{ route('mobile.justifications.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <!-- Session -->
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Séance concernée</label>
                <select name="session_id" x-model="selectedSession" required
                    class="w-full bg-white border border-gray-200 rounded-2xl px-4 py-3.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none">
                    <option value="">Sélectionner une séance</option>
                    @foreach($absentSessions as $s)
                        <option value="{{ $s['id'] }}">
                            {{ $s['module']['name'] ?? 'Séance' }} — {{ \Carbon\Carbon::parse($s['start_time'])->format('d/m/Y H:i') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Type -->
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Type</label>
                <div class="grid grid-cols-2 gap-2">
                    @foreach(['medical' => 'Médical', 'family' => 'Familial', 'academic' => 'Académique', 'other' => 'Autre'] as $val => $label)
                        <label class="relative cursor-pointer">
                            <input type="radio" name="type" value="{{ $val }}" x-model="type" required class="peer sr-only">
                            <div class="border-2 border-gray-200 rounded-xl px-3 py-3 text-center text-xs font-bold text-gray-600 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 transition-all">
                                {{ $label }}
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Reason -->
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Motif</label>
                <textarea name="reason" rows="4" required maxlength="1000"
                    class="w-full bg-white border border-gray-200 rounded-2xl px-4 py-3.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                    placeholder="Décris le motif de ton absence..."></textarea>
                <p class="text-[10px] text-gray-400 mt-1 text-right">Max 1000 caractères</p>
            </div>

            <!-- Document -->
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Document (optionnel)</label>
                <label class="block border-2 border-dashed border-gray-200 rounded-2xl p-6 text-center cursor-pointer hover:border-blue-300 transition-colors">
                    <input type="file" name="document" accept=".pdf,.jpg,.png,.jpeg" class="hidden" @change="fileName = $event.target.files[0]?.name || ''">
                    <div x-show="!fileName">
                        <i data-lucide="upload" class="w-8 h-8 text-gray-300 mx-auto mb-2"></i>
                        <p class="text-xs font-bold text-gray-500">Appuyez pour choisir un fichier</p>
                        <p class="text-[10px] text-gray-400 mt-1">PDF, JPG, PNG — Max 2 Mo</p>
                    </div>
                    <div x-show="fileName" x-cloak>
                        <i data-lucide="file-check" class="w-8 h-8 text-blue-500 mx-auto mb-2"></i>
                        <p class="text-xs font-bold text-blue-600" x-text="fileName"></p>
                    </div>
                </label>
            </div>

            <!-- Submit -->
            <button type="submit" :disabled="submitting"
                class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold py-4 rounded-2xl shadow-md shadow-blue-200 active:scale-95 transition-all disabled:opacity-50">
                <span x-show="!submitting">Soumettre la justification</span>
                <span x-show="submitting" x-cloak>Envoi en cours...</span>
            </button>
        </form>
    @else
        <div class="bg-white rounded-3xl border border-gray-100 p-12 text-center shadow-sm">
            <div class="w-16 h-16 bg-green-50 rounded-3xl flex items-center justify-center mx-auto mb-4">
                <i data-lucide="check-circle" class="w-8 h-8 text-green-500"></i>
            </div>
            <h3 class="text-sm font-bold text-gray-800 mb-1">Aucune absence à justifier</h3>
            <p class="text-xs text-gray-500">Tu n'as aucune absence non justifiée.</p>
        </div>
    @endif

</div>

<script>
function justificationForm() {
    return {
        selectedSession: '',
        type: '',
        fileName: '',
        submitting: false,
    };
}
</script>
@endsection
