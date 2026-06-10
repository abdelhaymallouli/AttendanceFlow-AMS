@extends('layouts.dashboard')

@section('title', 'Valider ma présence')
@section('page_title', 'Valider ma présence')

@section('content')
<div class="max-w-5xl mx-auto space-y-6"
     x-data="qrStudentScan({
         precheckEndpoint: '{{ url('/api/attendance/qr/pre-check') }}',
         scanEndpoint: '{{ url('/api/attendance/qr/scan') }}',
         studentProfileId: {{ $student->id }},
         csrf: '{{ csrf_token() }}',
     })"
     x-init="init()">

    {{-- Result banners --}}
    <template x-if="result && result.status === 'present'">
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 flex items-center gap-3">
            <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 shrink-0"></i>
            <div>
                <p class="font-bold text-sm">Présence validée avec succès !</p>
                <p class="text-xs text-emerald-700 mt-0.5">
                    Score de validation : <span class="font-bold" x-text="result.score"></span>/<span x-text="result.max_score"></span>
                </p>
            </div>
        </div>
    </template>

    <template x-if="result && result.status === 'rejected'">
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 flex items-center gap-3">
            <i data-lucide="x-circle" class="w-5 h-5 text-red-600 shrink-0"></i>
            <div>
                <p class="font-bold text-sm">Validation refusée</p>
                <p class="text-xs text-red-700 mt-0.5">
                    Raison : <span class="font-bold" x-text="formatReason(result.rejection_reason)"></span>
                </p>
            </div>
        </div>
    </template>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

        {{-- ==================== LEFT: CODE ENTRY ==================== --}}
        <div class="lg:col-span-3">
            <x-ui.section-card padding="p-0">
                {{-- Header --}}
                <div class="px-6 py-5 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-sm">
                            <i data-lucide="key-round" class="w-5 h-5 text-white"></i>
                        </div>
                        <div>
                            <h2 class="font-bold text-gray-800">Code d'émargement</h2>
                            <p class="text-xs text-gray-500">Saisissez le code affiché par votre formateur</p>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-6 space-y-6">

                    {{-- Readiness summary --}}
                    <div class="rounded-xl border px-4 py-3 flex items-center gap-3 transition-all"
                         :class="allReady()
                             ? 'bg-emerald-50 border-emerald-200'
                             : 'bg-amber-50 border-amber-200'">
                        <template x-if="allReady()">
                            <i data-lucide="shield-check" class="w-5 h-5 text-emerald-600 shrink-0"></i>
                        </template>
                        <template x-if="!allReady()">
                            <i data-lucide="shield-alert" class="w-5 h-5 text-amber-600 shrink-0"></i>
                        </template>
                        <p class="text-xs font-semibold"
                           :class="allReady() ? 'text-emerald-800' : 'text-amber-800'"
                           x-text="allReady()
                               ? 'Toutes les vérifications sont validées. Vous pouvez émarger.'
                               : 'Complétez les vérifications ci-contre avant de soumettre votre présence.'">
                        </p>
                    </div>

                    {{-- Code input --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-2">
                            Code de la séance
                        </label>
                        <input
                            id="attendance-code-input"
                            type="text"
                            x-model="token"
                            :disabled="!allReady()"
                            maxlength="64"
                            autocomplete="off"
                            spellcheck="false"
                            placeholder="Ex : AMS-QR… ou XYZABC"
                            class="w-full px-4 py-4 text-center text-xl font-mono font-bold tracking-widest uppercase border-2 rounded-xl outline-none transition-all
                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                   disabled:bg-gray-50 disabled:border-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed
                                   enabled:border-blue-300 enabled:bg-white enabled:text-gray-800"
                        >
                        <p class="text-[10px] text-gray-400 mt-1.5 text-center">
                            Ce champ est activé uniquement lorsque toutes les validations sont confirmées.
                        </p>
                    </div>

                    {{-- Submit button --}}
                    <button
                        @click="submit()"
                        :disabled="busy || !token || !allReady()"
                        class="w-full py-3.5 font-extrabold uppercase tracking-wider text-sm rounded-xl transition-all shadow-sm
                               disabled:opacity-40 disabled:cursor-not-allowed
                               enabled:bg-blue-600 enabled:hover:bg-blue-700 enabled:text-white
                               bg-gray-300 text-gray-500">
                        <span x-show="!busy" class="flex items-center justify-center gap-2">
                            <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                            Enregistrer ma présence
                        </span>
                        <span x-show="busy" class="flex items-center justify-center gap-2">
                            <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                            Validation en cours...
                        </span>
                    </button>

                </div>
            </x-ui.section-card>

            {{-- Recent records --}}
            <div class="mt-6">
                <x-ui.section-card padding="p-0">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <h3 class="text-sm font-bold text-gray-800">Historique des émargements</h3>
                    </div>
                    @if ($recentRecords->isEmpty())
                        <div class="px-5 py-8 text-center text-xs text-gray-400 italic">
                            Aucun émargement enregistré pour le moment.
                        </div>
                    @else
                        <div class="divide-y divide-gray-100">
                            @foreach ($recentRecords as $rec)
                                <div class="px-5 py-3 flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800">
                                            {{ $rec->session?->module?->name ?? 'Séance' }}
                                        </p>
                                        <p class="text-[10px] text-gray-400 font-mono mt-0.5">
                                            {{ \Carbon\Carbon::parse($rec->date)->format('d/m/Y') }}
                                            · {{ ucfirst($rec->check_in_method ?? 'manuel') }}
                                        </p>
                                    </div>
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                        @if($rec->status === 'present') bg-emerald-50 text-emerald-700
                                        @elseif($rec->status === 'late') bg-amber-50 text-amber-700
                                        @else bg-red-50 text-red-700
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $rec->status)) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </x-ui.section-card>
            </div>

        </div>

        {{-- ==================== RIGHT: VALIDATION STATUS PANEL ==================== --}}
        <div class="lg:col-span-2">
            <x-ui.section-card padding="p-0">
                <div class="px-5 py-4 border-b border-gray-100">
                    <div class="flex items-center gap-2">
                        <i data-lucide="shield" class="w-4 h-4 text-indigo-600"></i>
                        <h3 class="text-sm font-bold text-gray-800">Statut de validation</h3>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-0.5">Les 4 vérifications doivent être validées</p>
                </div>

                <div class="divide-y divide-gray-100">

                    {{-- 1. Location Permission --}}
                    <div class="px-5 py-4">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 mt-0.5 transition-all"
                                 :class="{
                                     'bg-emerald-50 text-emerald-600': checks.location === 'ok',
                                     'bg-red-50 text-red-500': checks.location === 'fail',
                                     'bg-gray-100 text-gray-400': checks.location === 'pending',
                                     'bg-amber-50 text-amber-500': checks.location === 'checking',
                                 }">
                                <template x-if="checks.location === 'ok'">
                                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                                </template>
                                <template x-if="checks.location === 'fail'">
                                    <i data-lucide="map-pin-off" class="w-4 h-4"></i>
                                </template>
                                <template x-if="checks.location === 'checking'">
                                    <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                                </template>
                                <template x-if="checks.location === 'pending'">
                                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                                </template>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2">
                                    <p class="text-xs font-bold text-gray-800">Permission de localisation</p>
                                    <span class="text-[10px] font-bold shrink-0"
                                          :class="{
                                              'text-emerald-600': checks.location === 'ok',
                                              'text-red-500': checks.location === 'fail',
                                              'text-amber-500': checks.location === 'checking',
                                              'text-gray-400': checks.location === 'pending',
                                          }"
                                          x-text="{ok:'✓ Validé', fail:'✕ Refusé', checking:'…', pending:'—'}[checks.location]">
                                    </span>
                                </div>
                                <p class="text-[10px] text-gray-500 mt-0.5"
                                   x-text="locationDetail()">
                                </p>
                                <template x-if="checks.location !== 'ok'">
                                    <button @click="requestGeolocation()" :disabled="checks.location === 'checking'"
                                            class="mt-2 inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white text-[10px] font-bold uppercase tracking-wider rounded-lg transition-all">
                                        <i data-lucide="navigation" class="w-3 h-3"></i>
                                        Activer la localisation
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- 2. GPS Radius --}}
                    <div class="px-5 py-4">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 mt-0.5 transition-all"
                                 :class="{
                                     'bg-emerald-50 text-emerald-600': checks.gps === 'ok',
                                     'bg-red-50 text-red-500': checks.gps === 'fail',
                                     'bg-gray-100 text-gray-400': checks.gps === 'pending',
                                     'bg-amber-50 text-amber-500': checks.gps === 'checking',
                                 }">
                                <template x-if="checks.gps === 'ok'">
                                    <i data-lucide="radio" class="w-4 h-4"></i>
                                </template>
                                <template x-if="checks.gps === 'fail'">
                                    <i data-lucide="signal-zero" class="w-4 h-4"></i>
                                </template>
                                <template x-if="checks.gps === 'checking'">
                                    <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                                </template>
                                <template x-if="checks.gps === 'pending'">
                                    <i data-lucide="radio" class="w-4 h-4"></i>
                                </template>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2">
                                    <p class="text-xs font-bold text-gray-800">Rayon GPS Campus</p>
                                    <span class="text-[10px] font-bold shrink-0"
                                          :class="{
                                              'text-emerald-600': checks.gps === 'ok',
                                              'text-red-500': checks.gps === 'fail',
                                              'text-amber-500': checks.gps === 'checking',
                                              'text-gray-400': checks.gps === 'pending',
                                          }"
                                          x-text="{ok:'✓ Validé', fail:'✕ Hors zone', checking:'…', pending:'—'}[checks.gps]">
                                    </span>
                                </div>
                                <p class="text-[10px] text-gray-500 mt-0.5" x-text="gpsDetail()"></p>
                                <template x-if="checks.gps === 'fail'">
                                    <button @click="requestGeolocation()" :disabled="checks.location === 'checking'"
                                            class="mt-2 inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-500 hover:bg-amber-600 disabled:opacity-50 text-white text-[10px] font-bold uppercase tracking-wider rounded-lg transition-all">
                                        <i data-lucide="refresh-cw" class="w-3 h-3"></i>
                                        Ré-essayer le GPS
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- 3. Network Validation --}}
                    <div class="px-5 py-4">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 mt-0.5 transition-all"
                                 :class="{
                                     'bg-emerald-50 text-emerald-600': checks.network === 'ok',
                                     'bg-red-50 text-red-500': checks.network === 'fail',
                                     'bg-gray-100 text-gray-400': checks.network === 'pending',
                                     'bg-amber-50 text-amber-500': checks.network === 'checking',
                                 }">
                                <template x-if="checks.network === 'ok'">
                                    <i data-lucide="wifi" class="w-4 h-4"></i>
                                </template>
                                <template x-if="checks.network === 'fail'">
                                    <i data-lucide="wifi-off" class="w-4 h-4"></i>
                                </template>
                                <template x-if="checks.network === 'checking'">
                                    <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                                </template>
                                <template x-if="checks.network === 'pending'">
                                    <i data-lucide="wifi" class="w-4 h-4"></i>
                                </template>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2">
                                    <p class="text-xs font-bold text-gray-800">Réseau de l'école</p>
                                    <span class="text-[10px] font-bold shrink-0"
                                          :class="{
                                              'text-emerald-600': checks.network === 'ok',
                                              'text-red-500': checks.network === 'fail',
                                              'text-amber-500': checks.network === 'checking',
                                              'text-gray-400': checks.network === 'pending',
                                          }"
                                          x-text="{ok:'✓ Autorisé', fail:'✕ Non autorisé', checking:'…', pending:'—'}[checks.network]">
                                    </span>
                                </div>
                                <p class="text-[10px] text-gray-500 mt-0.5" x-text="networkDetail()"></p>
                                <template x-if="checks.network !== 'ok'">
                                    <button @click="runNetworkCheck()" :disabled="checks.network === 'checking'"
                                            class="mt-2 inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white text-[10px] font-bold uppercase tracking-wider rounded-lg transition-all">
                                        <i data-lucide="refresh-cw" class="w-3 h-3"></i>
                                        Vérifier le réseau
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- 4. API Connection --}}
                    <div class="px-5 py-4">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 mt-0.5 transition-all"
                                 :class="{
                                     'bg-emerald-50 text-emerald-600': checks.api === 'ok',
                                     'bg-red-50 text-red-500': checks.api === 'fail',
                                     'bg-gray-100 text-gray-400': checks.api === 'pending',
                                     'bg-amber-50 text-amber-500': checks.api === 'checking',
                                 }">
                                <template x-if="checks.api === 'ok'">
                                    <i data-lucide="server" class="w-4 h-4"></i>
                                </template>
                                <template x-if="checks.api === 'fail'">
                                    <i data-lucide="server-off" class="w-4 h-4"></i>
                                </template>
                                <template x-if="checks.api === 'checking'">
                                    <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>
                                </template>
                                <template x-if="checks.api === 'pending'">
                                    <i data-lucide="server" class="w-4 h-4"></i>
                                </template>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2">
                                    <p class="text-xs font-bold text-gray-800">Connexion API</p>
                                    <span class="text-[10px] font-bold shrink-0"
                                          :class="{
                                              'text-emerald-600': checks.api === 'ok',
                                              'text-red-500': checks.api === 'fail',
                                              'text-amber-500': checks.api === 'checking',
                                              'text-gray-400': checks.api === 'pending',
                                          }"
                                          x-text="{ok:'✓ Connecté', fail:'✕ Échec', checking:'…', pending:'—'}[checks.api]">
                                    </span>
                                </div>
                                <p class="text-[10px] text-gray-500 mt-0.5">
                                    <span x-show="clientIp" x-text="'IP : ' + clientIp"></span>
                                    <span x-show="!clientIp">Connexion au serveur en cours…</span>
                                </p>
                                <template x-if="checks.api === 'fail'">
                                    <button @click="runApiCheck()" :disabled="checks.api === 'checking'"
                                            class="mt-2 inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-700 hover:bg-gray-800 disabled:opacity-50 text-white text-[10px] font-bold uppercase tracking-wider rounded-lg transition-all">
                                        <i data-lucide="refresh-cw" class="w-3 h-3"></i>
                                        Reconnecter
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Refresh all button --}}
                <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/60 rounded-b-xl">
                    <button @click="refreshAll()"
                            class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-sm">
                        <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
                        Rafraîchir toutes les validations
                    </button>
                </div>

            </x-ui.section-card>
        </div>

    </div>
</div>

<script>
function qrStudentScan(config) {
    return {
        token: '',
        latitude: null,
        longitude: null,
        accuracy: null,
        busy: false,
        result: null,
        clientIp: null,

        precheckEndpoint: config.precheckEndpoint,
        scanEndpoint: config.scanEndpoint,
        studentProfileId: config.studentProfileId,
        csrf: config.csrf,

        checks: {
            location: 'pending',
            gps:      'pending',
            network:  'pending',
            api:      'pending',
        },

        init() {
            this.runApiCheck();
            this.requestGeolocation();
        },

        allReady() {
            return this.checks.location === 'ok'
                && this.checks.gps      === 'ok'
                && this.checks.network  === 'ok'
                && this.checks.api      === 'ok';
        },

        /* ---- Geolocation ---- */
        requestGeolocation() {
            if (!navigator.geolocation) {
                this.checks.location = 'fail';
                this.checks.gps = 'fail';
                return;
            }
            this.checks.location = 'checking';
            this.checks.gps      = 'checking';

            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    this.latitude  = pos.coords.latitude;
                    this.longitude = pos.coords.longitude;
                    this.accuracy  = pos.coords.accuracy;
                    this.checks.location = 'ok';
                    this.runApiCheck(); // recheck GPS radius via server
                },
                () => {
                    this.checks.location = 'fail';
                    this.checks.gps      = 'fail';
                },
                { enableHighAccuracy: true, timeout: 12000, maximumAge: 30000 }
            );
        },

        /* ---- Network + GPS radius + API via single call ---- */
        async runNetworkCheck() {
            this.checks.network = 'checking';
            await this.runApiCheck();
        },

        async runApiCheck() {
            this.checks.api = 'checking';

            try {
                let url = this.precheckEndpoint;
                const params = [];
                if (this.latitude !== null)  params.push('latitude='  + this.latitude);
                if (this.longitude !== null) params.push('longitude=' + this.longitude);
                if (this.accuracy  !== null) params.push('accuracy='  + this.accuracy);
                if (params.length) url += '?' + params.join('&');

                const res = await fetch(url, {
                    headers: { 'Accept': 'application/json' },
                    credentials: 'same-origin',
                });

                if (!res.ok) {
                    this.checks.api     = 'fail';
                    this.checks.network = 'fail';
                    return;
                }

                const data = await res.json();

                this.clientIp       = data.client_ip || null;
                this.checks.api     = 'ok';
                this.checks.network = data.network_ok ? 'ok' : 'fail';

                if (this.latitude !== null) {
                    this.checks.gps = data.gps_ok ? 'ok' : 'fail';
                } else {
                    this.checks.gps = 'pending';
                }

            } catch (e) {
                this.checks.api     = 'fail';
                this.checks.network = 'fail';
            }
        },

        refreshAll() {
            this.checks = { location: 'pending', gps: 'pending', network: 'pending', api: 'pending' };
            this.result = null;
            this.runApiCheck();
            this.requestGeolocation();
        },

        /* ---- Submission ---- */
        async submit() {
            if (!this.token || !this.allReady()) return;
            this.busy   = true;
            this.result = null;
            try {
                const res = await fetch(this.scanEndpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.csrf,
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        token:              this.token,
                        student_profile_id: this.studentProfileId,
                        latitude:           this.latitude,
                        longitude:          this.longitude,
                        accuracy:           this.accuracy,
                    }),
                });
                const json = await res.json();
                this.result = json.data ?? json;

                if (this.result?.status === 'present') {
                    this.token = '';
                    setTimeout(() => window.location.reload(), 2500);
                }
            } catch (e) {
                this.result = { status: 'rejected', rejection_reason: 'network_error' };
            } finally {
                this.busy = false;
            }
        },

        /* ---- Detail helpers ---- */
        locationDetail() {
            if (this.checks.location === 'ok' && this.latitude !== null) {
                return this.latitude.toFixed(5) + ', ' + this.longitude.toFixed(5)
                    + ' (±' + Math.round(this.accuracy || 0) + 'm)';
            }
            if (this.checks.location === 'fail') {
                return 'Autorisation refusée. Activez la localisation dans votre navigateur.';
            }
            if (this.checks.location === 'checking') return 'Acquisition de la position GPS…';
            return 'En attente d\'autorisation de localisation.';
        },

        gpsDetail() {
            if (this.checks.location !== 'ok') return 'La localisation doit être activée en premier.';
            if (this.checks.gps === 'ok')      return 'Vous êtes dans le rayon autorisé du campus.';
            if (this.checks.gps === 'fail')    return 'Vous n\'êtes pas dans le périmètre de l\'école.';
            if (this.checks.gps === 'checking') return 'Vérification de votre position…';
            return 'En attente de la position GPS.';
        },

        networkDetail() {
            if (this.checks.network === 'ok')
                return 'Connecté au réseau local autorisé de Solicode.';
            if (this.checks.network === 'fail')
                return 'Connectez-vous au Wi-Fi de Solicode pour continuer.';
            if (this.checks.network === 'checking') return 'Vérification du réseau…';
            return 'En attente de vérification.';
        },

        formatReason(reason) {
            const map = {
                'invalid_or_expired_qr_token': 'Code invalide ou expiré',
                'outside_campus_radius':        'Hors du rayon du campus',
                'gps_missing':                  'Géolocalisation requise',
                'gps_accuracy_too_low':         'Signal GPS insuffisant',
                'ip_not_in_campus_subnets':     'Réseau non autorisé',
                'token_already_consumed':       'Code déjà utilisé',
                'network_error':                'Erreur de connexion réseau',
                'student_not_found':            'Profil étudiant introuvable',
                'no_student_profile':           'Aucun profil étudiant associé',
                'server_error':                 'Erreur interne du serveur',
            };
            return map[reason] || reason || 'Erreur inconnue';
        },
    };
}
</script>
@endsection
