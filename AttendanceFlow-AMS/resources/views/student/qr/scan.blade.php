@extends('layouts.dashboard')

@section('title', 'Scanner le code QR')
@section('page_title', 'Scanner le code QR')

@section('content')
<div class="max-w-3xl mx-auto space-y-6" x-data="qrStudentScan({
    endpoint: '{{ url('/api/attendance/qr/scan') }}',
    studentProfileId: {{ $student->id }},
    csrf: '{{ csrf_token() }}',
})">
    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 rounded-md p-4">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-md p-4">
            {{ session('error') }}
        </div>
    @endif

    <x-ui.section-card>
        <h3 class="font-semibold text-lg mb-2">Soumettre un QR</h3>
        <p class="text-sm text-gray-600 mb-4">
            Collez ici le code QR affiché par l'enseignant (depuis l'application mobile ou un second écran),
            ou utilisez l'app mobile <strong>AttendanceFlow</strong> pour scanner automatiquement.
        </p>

        <div class="space-y-3">
            <div>
                <label class="block text-sm font-medium text-gray-700">Code QR</label>
                <textarea x-model="token" rows="3"
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm font-mono text-xs"
                          placeholder="AMS-QR.payload.signature"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Latitude</label>
                    <input type="number" step="any" x-model.number="latitude"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Longitude</label>
                    <input type="number" step="any" x-model.number="longitude"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
            </div>

            <div>
                <button @click="requestGeolocation()"
                        class="text-sm text-indigo-600 hover:underline" type="button">
                    Utiliser ma position actuelle
                </button>
            </div>

            <button @click="submit()" :disabled="busy || !token"
                    class="w-full py-2 px-4 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50">
                <span x-show="!busy">Valider ma présence</span>
                <span x-show="busy">Envoi...</span>
            </button>
        </div>

        <div x-show="result" class="mt-4" x-cloak>
            <template x-if="result && result.status === 'present'">
                <div class="bg-green-50 border border-green-200 text-green-800 rounded-md p-3">
                    ✓ Présence enregistrée (score <span x-text="result.score"></span>/<span x-text="result.max_score"></span>)
                </div>
            </template>
            <template x-if="result && result.status === 'late'">
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-md p-3">
                    ⏱ Marqué en retard (score <span x-text="result.score"></span>/<span x-text="result.max_score"></span>)
                </div>
            </template>
            <template x-if="result && result.status === 'rejected'">
                <div class="bg-red-50 border border-red-200 text-red-800 rounded-md p-3">
                    ✕ Rejeté : <span x-text="result.rejection_reason"></span>
                </div>
            </template>
        </div>
    </x-ui.section-card>

    <x-ui.section-card>
        <h3 class="font-semibold text-lg mb-2">Mes dernières présences</h3>
        @if ($recentRecords->isEmpty())
            <p class="text-sm text-gray-500">Aucun enregistrement pour l'instant.</p>
        @else
            <ul class="divide-y">
                @foreach ($recentRecords as $record)
                    <li class="py-2 flex items-center justify-between">
                        <div>
                            <p class="font-medium">
                                {{ $record->session?->module?->name ?? 'Module inconnu' }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($record->date)->format('d/m/Y') }}
                                · {{ ucfirst(str_replace('_', ' ', $record->status)) }}
                            </p>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-full
                            @class([
                                'bg-green-100 text-green-800' => $record->status === 'present',
                                'bg-yellow-100 text-yellow-800' => $record->status === 'late',
                                'bg-red-100 text-red-800' => in_array($record->status, ['absent_unexcused', 'absent_excused']),
                            ])">
                            {{ $record->check_in_method ?? 'manual' }}
                        </span>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-ui.section-card>
</div>

<script>
function qrStudentScan(config) {
    return {
        token: '',
        latitude: null,
        longitude: null,
        busy: false,
        result: null,
        endpoint: config.endpoint,
        studentProfileId: config.studentProfileId,
        csrf: config.csrf,

        requestGeolocation() {
            if (! navigator.geolocation) { return; }
            navigator.geolocation.getCurrentPosition((pos) => {
                this.latitude  = pos.coords.latitude;
                this.longitude = pos.coords.longitude;
            }, (err) => {
                alert('Impossible d\'obtenir la position : ' + err.message);
            }, { enableHighAccuracy: true, timeout: 10000 });
        },

        async submit() {
            this.busy = true;
            this.result = null;
            try {
                const res = await fetch(this.endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.csrf,
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        token: this.token,
                        student_profile_id: this.studentProfileId,
                        latitude: this.latitude,
                        longitude: this.longitude,
                    }),
                });
                this.result = await res.json();
            } catch (e) {
                this.result = { status: 'rejected', rejection_reason: 'network_error' };
            } finally {
                this.busy = false;
            }
        },
    };
}
</script>
@endsection
