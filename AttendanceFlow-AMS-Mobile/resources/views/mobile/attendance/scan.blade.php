@extends('mobile.layouts.app')

@section('title', 'Scan QR')

@section('content')
<div class="p-4 space-y-4" x-data="mobileQrScan({
    submitUrl: '{{ route('mobile.attendance.scan.submit') }}',
    syncUrl: '{{ route('mobile.attendance.scan.sync') }}',
    pendingCount: {{ (int) $pendingCount }},
    studentProfileId: {{ (int) (session('mobile_student_profile_id') ?? 0) }},
    csrf: '{{ csrf_token() }}',
})">

    <div class="bg-white rounded-2xl shadow-sm p-4">
        <h2 class="text-lg font-bold mb-2">Scanner le QR</h2>
        <p class="text-sm text-gray-600 mb-3">
            Pointe l'appareil photo vers le code affiché par l'enseignant.
        </p>
        <div class="relative aspect-square bg-black rounded-xl overflow-hidden">
            <video x-ref="video" class="w-full h-full object-cover" autoplay playsinline muted></video>
            <div class="absolute inset-0 border-4 border-white/40 rounded-xl pointer-events-none"></div>
        </div>
        <canvas x-ref="canvas" class="hidden"></canvas>

        <div class="mt-3 flex justify-between items-center text-xs text-gray-500">
            <span x-text="status"></span>
            <button @click="manualEntry = !manualEntry" type="button" class="text-indigo-600 underline">
                Saisie manuelle
            </button>
        </div>

        <div x-show="manualEntry" x-cloak class="mt-3 space-y-2">
            <label class="block text-xs font-medium text-gray-600">Code QR (texte)</label>
            <textarea x-model="manualToken" rows="3"
                      class="w-full text-xs font-mono border rounded p-2"
                      placeholder="AMS-QR.payload.signature"></textarea>
            <button @click="submitManual()" :disabled="busy || !manualToken"
                    class="w-full py-2 bg-indigo-600 text-white rounded disabled:opacity-50">
                Soumettre
            </button>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-4">
        <div class="flex items-center justify-between mb-2">
            <h3 class="font-semibold">File hors-ligne</h3>
            <span class="text-xs bg-amber-100 text-amber-800 px-2 py-0.5 rounded-full"
                  x-text="`${pending} en attente`"></span>
        </div>
        <p class="text-xs text-gray-500 mb-3">
            Les scans capturés sans réseau seront synchronisés dès que possible.
        </p>
        <button @click="sync()" :disabled="busy || pending === 0"
                class="w-full py-2 bg-amber-600 text-white rounded disabled:opacity-50">
            Synchroniser maintenant
        </button>
    </div>

    <div x-show="lastResult" x-cloak class="bg-white rounded-2xl shadow-sm p-4">
        <h3 class="font-semibold mb-2">Dernier résultat</h3>
        <template x-if="lastResult && lastResult.status === 'present'">
            <div class="bg-green-50 border border-green-200 text-green-800 rounded p-3 text-sm">
                ✓ Présence enregistrée (score <span x-text="lastResult.score"></span>/<span x-text="lastResult.max_score"></span>)
            </div>
        </template>
        <template x-if="lastResult && lastResult.status === 'late'">
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 rounded p-3 text-sm">
                ⏱ Marqué en retard (score <span x-text="lastResult.score"></span>/<span x-text="lastResult.max_score"></span>)
            </div>
        </template>
        <template x-if="lastResult && lastResult.status === 'rejected'">
            <div class="bg-red-50 border border-red-200 text-red-800 rounded p-3 text-sm">
                ✕ Rejeté : <span x-text="lastResult.rejection_reason"></span>
            </div>
        </template>
        <template x-if="lastResult && lastResult.status === 'queued'">
            <div class="bg-blue-50 border border-blue-200 text-blue-800 rounded p-3 text-sm">
                ⏳ Mis en file d'attente (hors-ligne)
            </div>
        </template>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
<script>
function mobileQrScan(config) {
    return {
        submitUrl: config.submitUrl,
        syncUrl: config.syncUrl,
        csrf: config.csrf,
        studentProfileId: config.studentProfileId,
        pending: config.pendingCount,
        status: 'Initialisation...',
        busy: false,
        manualEntry: false,
        manualToken: '',
        lastResult: null,
        stream: null,
        scanning: false,

        async init() {
            try {
                this.stream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'environment' },
                    audio: false,
                });
                this.$refs.video.srcObject = this.stream;
                this.status = 'Pointe la caméra vers le QR';
                this.scanLoop();
            } catch (e) {
                this.status = 'Caméra indisponible. Utilise la saisie manuelle.';
                this.manualEntry = true;
            }
        },

        scanLoop() {
            if (this.scanning) return;
            this.scanning = true;
            const tick = () => {
                if (! this.scanning) return;
                if (this.$refs.video.readyState === this.$refs.video.HAVE_ENOUGH_DATA) {
                    const canvas = this.$refs.canvas;
                    const v = this.$refs.video;
                    canvas.width = v.videoWidth;
                    canvas.height = v.videoHeight;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(v, 0, 0, canvas.width, canvas.height);
                    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                    if (window.jsQR) {
                        const code = jsQR(imageData.data, imageData.width, imageData.height, { inversionAttempts: 'dontInvert' });
                        if (code && code.data) {
                            this.scanning = false;
                            this.submit(code.data);
                            return;
                        }
                    }
                }
                requestAnimationFrame(tick);
            };
            tick();
        },

        async submit(token) {
            if (this.busy) return;
            this.busy = true;
            this.status = 'Envoi...';
            try {
                const pos = await this.getPosition();
                const fp = await this.fingerprint();
                const res = await fetch(this.submitUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.csrf,
                    },
                    body: JSON.stringify({
                        token,
                        student_profile_id: this.studentProfileId,
                        latitude: pos?.latitude ?? 0,
                        longitude: pos?.longitude ?? 0,
                        accuracy: pos?.accuracy ?? null,
                        device_fingerprint: fp,
                        force_online: navigator.onLine,
                    }),
                });
                const data = await res.json();
                this.lastResult = data;
                if (data.queued) this.pending++;
                this.status = navigator.onLine ? 'Prêt' : 'Hors-ligne';
                setTimeout(() => { this.scanning = true; this.scanLoop(); }, 4000);
            } catch (e) {
                this.lastResult = { status: 'rejected', rejection_reason: 'network_error' };
                this.status = 'Erreur — réessaie';
                this.scanning = true;
                this.scanLoop();
            } finally {
                this.busy = false;
            }
        },

        async submitManual() {
            await this.submit(this.manualToken);
            this.manualToken = '';
        },

        getPosition() {
            return new Promise((resolve) => {
                if (! navigator.geolocation) return resolve(null);
                navigator.geolocation.getCurrentPosition(
                    (p) => resolve({ latitude: p.coords.latitude, longitude: p.coords.longitude, accuracy: p.coords.accuracy }),
                    () => resolve(null),
                    { enableHighAccuracy: true, timeout: 8000 }
                );
            });
        },

        async fingerprint() {
            const comp = {
                ua: navigator.userAgent,
                screen: `${screen.width}x${screen.height}`,
                tz: Intl.DateTimeFormat().resolvedOptions().timeZone,
                canvas: '',
            };
            try {
                const c = document.createElement('canvas');
                const ctx = c.getContext('2d');
                ctx.textBaseline = 'top';
                ctx.font = '14px Arial';
                ctx.fillStyle = '#f60';
                ctx.fillRect(0, 0, 100, 30);
                ctx.fillStyle = '#069';
                ctx.fillText('fingerprint', 2, 2);
                comp.canvas = c.toDataURL();
            } catch (e) {}
            const payload = `${comp.ua}|${comp.screen}|${comp.tz}|${comp.canvas}`;
            const buf = new TextEncoder().encode(payload);
            const hash = await crypto.subtle.digest('SHA-256', buf);
            return Array.from(new Uint8Array(hash)).map(b => b.toString(16).padStart(2, '0')).join('');
        },

        async sync() {
            if (this.busy || this.pending === 0) return;
            this.busy = true;
            try {
                const res = await fetch(this.syncUrl, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.csrf,
                    },
                });
                const data = await res.json();
                this.pending = data.rejected ?? 0;
                this.lastResult = { status: 'queued', message: `Sync: ${data.accepted} acceptés, ${data.rejected} rejetés` };
            } finally {
                this.busy = false;
            }
        },
    };
}
</script>
@endsection
