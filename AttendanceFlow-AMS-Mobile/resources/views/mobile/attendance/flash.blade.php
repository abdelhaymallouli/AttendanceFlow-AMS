@extends('mobile.layouts.app')

@section('title', 'Pointage Rapide - Solicode AMS')
@section('header_title', 'Attendance Entry')

@section('content')
<div x-data="attendanceApp()" 
     x-init="initData(@json($session['group']['student_profiles'] ?? []))"
     class="pb-32"> <!-- Extra padding for fixed bottom button -->

    <!-- Top Navigation Bar Info (Sub-header) -->
    <div class="bg-white border-b border-gray-100 px-4 py-3 sticky top-14 z-30 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="flex-1">
                <h1 class="text-xs font-bold uppercase tracking-wider text-blue-600">{{ $session['module']['name'] ?? 'Module' }}</h1>
                <p class="text-sm font-bold text-gray-800">Groupe {{ $session['group']['name'] ?? 'GP' }} • Salle {{ $session['room'] ?? '201' }}</p>
            </div>
        </div>
    </div>

    <!-- Session Info Horizontal Scroll -->
    <div class="bg-gray-50 border-b border-gray-100 px-4 py-3">
        <div class="flex gap-3 overflow-x-auto pb-1 -mx-1 px-1 no-scrollbar" style="scrollbar-width:none">
            <div class="bg-blue-600 text-white rounded-xl px-4 py-2 flex-shrink-0 text-center shadow-md shadow-blue-100">
                <p class="text-[10px] text-blue-200 uppercase font-bold">Date</p>
                <p class="text-sm font-bold">{{ \Carbon\Carbon::now()->format('d M') }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl px-4 py-2 flex-shrink-0 text-center">
                <p class="text-[10px] text-gray-500 uppercase font-bold">Session</p>
                <p class="text-sm font-bold text-gray-800">{{ substr($session['start_time'], 0, 5) }}–{{ substr($session['end_time'], 0, 5) }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl px-4 py-2 flex-shrink-0 text-center">
                <p class="text-[10px] text-gray-500 uppercase font-bold">Type</p>
                <p class="text-sm font-bold text-gray-800">{{ strtoupper($session['type'] ?? 'TP') }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl px-4 py-2 flex-shrink-0 text-center">
                <p class="text-[10px] text-gray-500 uppercase font-bold">Étudiants</p>
                <p class="text-sm font-bold text-gray-800">{{ count($session['group']['student_profiles'] ?? []) }}</p>
            </div>
        </div>
    </div>

    <!-- Summary Counts Bar -->
    <div class="bg-white border-b border-gray-100 px-4 py-3 sticky top-[125px] z-20">
        <div class="flex justify-between items-center bg-gray-50 rounded-xl p-3 border border-gray-100">
            <div class="flex items-center gap-2">
                <div class="w-2.5 h-2.5 rounded-full bg-green-500"></div>
                <span class="text-xs text-gray-600 font-bold"><span x-text="presentCount" class="text-green-600">0</span> Présents</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-2.5 h-2.5 rounded-full bg-red-500"></div>
                <span class="text-xs text-gray-600 font-bold"><span x-text="absentCount" class="text-red-600">0</span> Absents</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-2.5 h-2.5 rounded-full bg-amber-500"></div>
                <span class="text-xs text-gray-600 font-bold"><span x-text="lateCount" class="text-amber-600">0</span> Retards</span>
            </div>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="px-4 py-3 bg-white border-b border-gray-100">
        <div class="flex gap-2">
            <button @click="markAll('present')" class="flex-1 bg-green-50 border border-green-200 text-green-700 text-xs font-bold py-3 rounded-xl flex items-center justify-center gap-2 transition-all active:scale-95">
                <i data-lucide="check-circle" class="w-4 h-4"></i> Tout Présents
            </button>
            <button @click="markAll('absent')" class="flex-1 bg-red-50 border border-red-200 text-red-600 text-xs font-bold py-3 rounded-xl flex items-center justify-center gap-2 transition-all active:scale-95">
                <i data-lucide="x-circle" class="w-4 h-4"></i> Tout Absents
            </button>
        </div>
    </div>

    <!-- Unified Student List -->
    <div class="px-4 py-4 space-y-3">
        <template x-for="(student, index) in students" :key="student.id">
            <div class="student-row rounded-2xl p-4 border-2 transition-all shadow-sm bg-white"
                 :class="{ 
                    'present border-green-200 bg-green-50/30': student.status === 'present', 
                    'absent border-red-200 bg-red-50/30': student.status === 'absent', 
                    'late border-amber-200 bg-amber-50/30': student.status === 'late',
                    'border-gray-100': !student.status 
                 }">
                <div class="flex items-center gap-3">
                    <!-- Initials circle -->
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 font-bold text-xs"
                         :class="{
                            'bg-green-100 text-green-700': student.status === 'present',
                            'bg-red-100 text-red-600': student.status === 'absent',
                            'bg-amber-100 text-amber-700': student.status === 'late',
                            'bg-gray-100 text-gray-500': !student.status
                         }"
                         x-text="student.initials"></div>
                    
                    <!-- Info -->
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-gray-800 truncate" x-text="student.name"></p>
                        <p class="text-[10px] uppercase font-bold text-gray-400 tracking-tighter" x-text="student.matricule || 'Sans ID'"></p>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-1.5 flex-shrink-0">
                        <button @click="setStatus(student, 'present')"
                            class="w-9 h-9 rounded-xl flex items-center justify-center transition-all border shadow-sm"
                            :class="student.status === 'present' ? 'bg-green-500 text-white border-green-600 shadow-green-100' : 'bg-white text-gray-300 border-gray-100'">
                            <i data-lucide="check" class="w-4 h-4"></i>
                        </button>
                        <button @click="setStatus(student, 'late')"
                            class="w-9 h-9 rounded-xl flex items-center justify-center transition-all border shadow-sm"
                            :class="student.status === 'late' ? 'bg-amber-500 text-white border-amber-600 shadow-amber-100' : 'bg-white text-gray-300 border-gray-100'">
                            <i data-lucide="clock" class="w-4 h-4"></i>
                        </button>
                        <button @click="setStatus(student, 'absent')"
                            class="w-9 h-9 rounded-xl flex items-center justify-center transition-all border shadow-sm"
                            :class="student.status === 'absent' ? 'bg-red-500 text-white border-red-600 shadow-red-100' : 'bg-white text-gray-300 border-gray-100'">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Fixed Bottom Action Bar -->
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 p-4 pb-8 z-50 shadow-2xl shadow-gray-500">
        <button @click="submitAttendance()" 
                :disabled="loading"
                class="w-full bg-blue-600 text-white font-bold py-4 rounded-2xl flex items-center justify-center gap-3 shadow-lg shadow-blue-200 transition-all active:scale-95 disabled:opacity-50">
            <template x-if="!loading">
                <div class="flex items-center gap-3">
                    <i data-lucide="save" class="w-5 h-5"></i>
                    Fiche du Pointage
                </div>
            </template>
            <template x-if="loading">
                <div class="flex items-center gap-3">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Traitement...
                </div>
            </template>
        </button>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function attendanceApp() {
        return {
            students: [],
            loading: false,
            initData(profiles) {
                this.students = profiles.map(sp => ({
                    id: sp.id,
                    matricule: sp.matricule,
                    name: sp.user.name,
                    initials: sp.user.name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase(),
                    status: 'present' // Default to present as in maquete
                }));
            },
            get presentCount() { return this.students.filter(s => s.status === 'present').length; },
            get absentCount() { return this.students.filter(s => s.status === 'absent').length; },
            get lateCount() { return this.students.filter(s => s.status === 'late').length; },
            setStatus(student, status) {
                student.status = status;
            },
            markAll(status) {
                this.students.forEach(s => s.status = status);
            },
            async submitAttendance() {
                this.loading = true;
                const payload = {
                    session_id: {{ $session['id'] }},
                    date: new Date().toISOString().split('T')[0],
                    records: this.students.map(s => ({
                        student_profile_id: s.id,
                        status: s.status
                    }))
                };

                try {
                    const response = await fetch("{{ config('services.ams.url') }}/attendance/record", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(payload)
                    });

                    if (response.ok) {
                        alert('Pointage enregistré avec succès !');
                        window.location.href = "{{ route('mobile.sessions') }}";
                    } else {
                        const error = await response.json();
                        alert('Erreur: ' + (error.message || 'Problème lors de l\'enregistrement'));
                    }
                } catch (e) {
                    console.error(e);
                    alert('Erreur réseau. Vérifiez votre connexion.');
                } finally {
                    this.loading = false;
                }
            }
        };
    }
</script>
@endpush