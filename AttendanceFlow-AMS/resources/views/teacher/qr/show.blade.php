@extends('layouts.dashboard')

@section('title', 'QR d\'émargement — ' . $session->module->name)
@section('page_title', 'Séance : ' . ($session->module->name ?? '—'))

@section('content')
@php
    $sessionDate = \Carbon\Carbon::parse($session->start_time)->toDateString();

    if ($isAdmin ?? false) {
        $liveScansUrl  = route('admin.sessions.live-scans', $session->id);
        $saveUrl       = route('admin.attendance.store', $session->id);
        $reinitUrl     = route('admin.sessions.qr.reinitialize', $session->id);
        $qrShowUrl     = route('admin.sessions.qr.show', $session->id);
        $backToListUrl = route('admin.qr.index', ['date' => $sessionDate]);
        $backLabel     = 'Toutes les classes';
    } else {
        $liveScansUrl  = route('teacher.sessions.live-scans', $session->id);
        $saveUrl       = route('teacher.sessions.attendance.store', $session->id);
        $reinitUrl     = route('teacher.sessions.qr.reinitialize', $session->id);
        $qrShowUrl     = route('teacher.sessions.qr.show', $session->id);
        $backToListUrl = route('teacher.attendance.index', ['date' => $sessionDate]);
        $backLabel     = 'Retour à la sélection';
    }

    $config = [
        'sessionId' => (int) $session->id,
        'initialToken' => $token['token'] ?? '',
        'initialTextCode' => $token['text_code'] ?? '',
        'initialExpiresAt' => $token ? (int) $token['expires_at']->timestamp : 0,
        'ttlSeconds' => (int) config('qr_attendance.token.ttl_seconds'),
        'students' => $studentsData,
        'presentCount' => (int) $presentCount,
        'totalCount' => (int) $totalCount,
        'started' => (bool) $started,
        'closed' => (bool) $closed,
        'isPast' => (bool) $isPast,
        'initialFeed' => $liveFeed,
        'liveScansUrl' => $liveScansUrl,
        'saveUrl' => $saveUrl,
        'reinitUrl' => $reinitUrl,
        'backToListUrl' => $backToListUrl,
        'qrShowUrl' => $qrShowUrl,
        'tokenUrl' => url('/api/attendance/qr/token/' . $session->id),
        'isAdmin' => (bool) ($isAdmin ?? false),
    ];
@endphp

<script type="application/json" id="qr-config">{!! json_encode($config, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>

<div class="space-y-4" x-data="qrSessionWorkspace()" x-init="init()">

    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 text-sm font-medium flex items-center gap-2">
            <i data-lucide="check-circle-2" class="w-4 h-4"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="flex items-center justify-between gap-3 flex-wrap">
        <a :href="backToListUrl"
           class="inline-flex items-center gap-1.5 text-xs text-gray-500 hover:text-gray-800 transition-colors border border-gray-200 bg-white px-3.5 py-2 rounded-xl font-semibold">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            {{ $backLabel }}
        </a>

        <div class="flex flex-wrap gap-2" x-show="started" x-cloak>
            <button @click="showProjector()" type="button"
                    x-show="! isProjectorMode"
                    class="inline-flex items-center gap-1.5 text-xs text-blue-700 hover:text-blue-900 border border-blue-200 bg-blue-50 hover:bg-blue-100 px-3.5 py-2 rounded-xl font-semibold">
                <i data-lucide="qr-code" class="w-3.5 h-3.5"></i>
                Afficher le QR
            </button>

            <button @click="showEditor()" type="button"
                    x-show="isProjectorMode"
                    class="inline-flex items-center gap-1.5 text-xs text-gray-700 hover:text-gray-900 border border-gray-200 bg-white px-3.5 py-2 rounded-xl font-semibold">
                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                Éditer les présences
            </button>

            <span x-show="presentCount > 0" x-cloak
                  class="inline-flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">
                <i data-lucide="check-circle-2" class="w-3 h-3"></i>
                <span x-text="presentCount"></span>/<span x-text="totalCount"></span> scannés
            </span>
        </div>
    </div>

    {{-- LANDING CARD: not yet started --}}
    <div x-show="! started" x-cloak>
        <x-ui.section-card>
            <div class="text-center py-10 px-4">
                <div class="w-20 h-20 rounded-2xl bg-blue-50 border-2 border-blue-100 flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="qr-code" class="w-10 h-10 text-blue-600"></i>
                </div>
                <h2 class="text-2xl font-extrabold text-gray-800 mb-2">Démarrer la séance</h2>
                <p class="text-sm text-gray-500 max-w-md mx-auto mb-1">
                    Module : <span class="font-bold text-gray-800">{{ $session->module->name ?? '—' }}</span>
                    @if ($session->group) · Groupe <span class="font-bold text-gray-800">{{ $session->group->name }}</span>@endif
                </p>
                <p class="text-xs text-gray-500 mb-8">
                    Horaire : {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}
                    · {{ $totalCount }} stagiaire(s)
                </p>

                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 max-w-xl mx-auto text-left mb-8">
                    <h3 class="text-sm font-bold text-amber-900 mb-2 flex items-center gap-2">
                        <i data-lucide="info" class="w-4 h-4"></i>
                        Comment ça marche
                    </h3>
                    <ol class="text-xs text-amber-800 space-y-1 list-decimal pl-5">
                        <li>Cliquez sur <strong>Démarrer la projection</strong> : un QR code rotatif s'affichera.</li>
                        <li>Projetez ou affichez ce QR en classe.</li>
                        <li>Les étudiants le scannent depuis l'application mobile <strong>AttendanceFlow</strong>.</li>
                        <li>À tout moment, cliquez sur <strong>Éditer les présences</strong> pour ajuster manuellement.</li>
                        <li>Enregistrez la feuille : vous serez redirigé vers la sélection.</li>
                    </ol>
                </div>

                <a :href="qrShowUrl + '?start=1'"
                   class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-extrabold uppercase tracking-wider text-sm py-3.5 px-8 rounded-xl shadow-lg shadow-blue-500/20 transition-all">
                    <i data-lucide="play" class="w-5 h-5"></i>
                    Démarrer la projection
                </a>
            </div>
        </x-ui.section-card>
    </div>

    {{-- SESSION CLOSED BANNER --}}
    <div x-show="started && isPast" x-cloak>
        <div class="bg-red-50 border-2 border-red-200 rounded-xl p-4 flex items-start gap-3">
            <i data-lucide="lock" class="w-5 h-5 text-red-600 shrink-0 mt-0.5"></i>
            <div>
                <p class="text-sm font-bold text-red-900">Séance terminée — feuille d'émargement clôturée</p>
                <p class="text-xs text-red-700 mt-1">
                    L'horaire de la séance est dépassé. Les statuts ne sont plus modifiables.
                    Pour rouvrir la séance, utilisez le bouton <strong>Réinitialiser la séance</strong> dans la feuille d'émargement.
                </p>
            </div>
        </div>
    </div>

    <div x-show="started" x-cloak class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 flex flex-col gap-6">
            {{-- QR PROJECTOR --}}
            <div x-show="isProjectorMode" x-cloak>
                <x-ui.section-card padding="p-0">
                    <div class="px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <h2 class="text-lg font-bold text-gray-800">QR Code d'émargement dynamique</h2>
                            <p class="text-xs text-gray-500 mt-1">
                                Projetez cet écran en classe. Le QR change toutes les <strong x-text="ttlSeconds"></strong>s.
                            </p>
                        </div>
                        <span class="inline-flex items-center gap-2 text-[10px] text-emerald-700 font-mono bg-emerald-50 border border-emerald-100 px-2.5 py-1.5 rounded-full">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                            Wifi Smart Campus
                        </span>
                    </div>

                    <div class="px-6 py-6 grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-56 h-56 bg-white border-2 border-gray-200 rounded-2xl p-3 flex items-center justify-center shadow-sm">
                                <div id="qr-canvas" class="w-full h-full"></div>
                            </div>
                            <!-- Unique QR Text Code Display -->
                            <div x-show="textCode" x-cloak class="text-center bg-gray-50 border border-gray-200 rounded-xl py-1 px-3 w-full">
                                <span class="text-[10px] text-gray-500 uppercase font-semibold block">Code d'émargement alternatif</span>
                                <span class="text-lg font-black tracking-widest text-indigo-700 font-mono select-all" x-text="textCode"></span>
                            </div>
                            <div class="flex items-center justify-between w-full px-1 text-[10px] text-gray-500 font-mono uppercase tracking-wider">
                                <span>Projection Active v1.0</span>
                                <button x-show="currentToken" @click="isFullscreen = true" type="button"
                                        class="text-blue-600 hover:text-blue-800 font-bold transition-colors flex items-center gap-1">
                                    <i data-lucide="maximize-2" class="w-3 h-3"></i>
                                    Plein écran
                                </button>
                            </div>
                        </div>

                        <div class="flex flex-col gap-4">
                            <div>
                                <h3 class="text-base font-bold text-gray-800">{{ $session->module->name ?? '—' }}</h3>
                                <p class="text-xs text-gray-500 mt-1">
                                    @if ($session->group) Groupe : {{ $session->group->name }} @endif
                                    · Type : {{ strtoupper($session->type ?? 'CM') }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    Horaire : {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}
                                </p>
                            </div>

                            <div class="bg-gray-50 border border-gray-100 rounded-xl p-4">
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-gray-500 font-medium">Jeton cryptographique</span>
                                    <span class="font-bold text-blue-600 font-mono flex items-center gap-1">
                                        <i data-lucide="clock" class="w-3 h-3"></i>
                                        <span x-text="secondsLeft"></span>s restantes
                                    </span>
                                </div>
                                <div class="w-full h-1.5 bg-gray-200 rounded-full overflow-hidden mt-2">
                                    <div class="h-full bg-blue-500 transition-all" :style="'width: ' + progressPct + '%'"></div>
                                </div>
                                <p class="text-[9px] font-mono text-gray-400 mt-1.5 truncate" :title="currentToken">
                                    Payload : <span x-text="currentToken || 'Calcul...'"></span>
                                </p>
                            </div>

                            <div>
                                <div class="flex justify-between text-xs mb-1.5">
                                    <span class="text-gray-500 font-medium">Présences enregistrées</span>
                                    <span class="font-bold text-emerald-600">
                                        <span x-text="presentCount"></span>/<span x-text="totalCount"></span>
                                        (<span x-text="scanProgressPct"></span>%)
                                    </span>
                                </div>
                                <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-emerald-500 transition-all" :style="'width: ' + scanProgressPct + '%'"></div>
                                </div>
                            </div>

                            <button @click="showEditor()" type="button" :disabled="isPast"
                                    :class="isPast ? 'bg-gray-300 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700'"
                                    class="w-full mt-2 inline-flex items-center justify-center gap-2 text-white font-bold py-3 rounded-xl transition-colors">
                                <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                                Éditer les présences
                            </button>
                        </div>
                    </div>
                </x-ui.section-card>
            </div>

            {{-- REGISTRY SHEET (default once records exist) --}}
            <div x-show="! isProjectorMode" x-cloak>
                <x-ui.section-card padding="p-0">
                    <div class="px-6 py-5 border-b border-gray-100 flex flex-col gap-3">
                        <div class="flex justify-between items-start">
                            <div>
                                <h2 class="text-lg font-bold text-gray-800">Registre des présences</h2>
                                <p class="text-xs text-gray-500 mt-1">
                                    Séance : {{ $session->module->name ?? '—' }}
                                    @if ($session->group) · Groupe : {{ $session->group->name }} @endif
                                </p>
                            </div>
                            <span class="inline-flex items-center gap-1.5 text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100"
                                  x-show="! needsSaving() && ! isSaving">
                                <i data-lucide="check-circle-2" class="w-3 h-3"></i>
                                Émargement Clôturé
                            </span>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
                            <div class="relative w-full sm:w-72">
                                <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute inset-y-0 left-3 my-auto pointer-events-none"></i>
                                <input x-model="search" type="text" placeholder="Rechercher un stagiaire..."
                                       :disabled="isPast"
                                       class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none disabled:bg-gray-50 disabled:cursor-not-allowed">
                            </div>

                            <div class="flex flex-wrap gap-2 items-center">
                                <button @click="setAll('present')" type="button" :disabled="isPast"
                                        class="text-[10px] font-bold uppercase tracking-wider px-3 py-2 bg-emerald-50 border border-emerald-200 text-emerald-700 hover:bg-emerald-100 rounded-lg flex items-center gap-1 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <i data-lucide="check" class="w-3 h-3"></i> Tous Présents
                                </button>
                                <button @click="setAll('absent')" type="button" :disabled="isPast"
                                        class="text-[10px] font-bold uppercase tracking-wider px-3 py-2 bg-red-50 border border-red-200 text-red-700 hover:bg-red-100 rounded-lg flex items-center gap-1 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <i data-lucide="x" class="w-3 h-3"></i> Tous Absents
                                </button>
                                <button @click="setAll('late')" type="button" :disabled="isPast"
                                        class="text-[10px] font-bold uppercase tracking-wider px-3 py-2 bg-amber-50 border border-amber-200 text-amber-700 hover:bg-amber-100 rounded-lg flex items-center gap-1 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <i data-lucide="clock" class="w-3 h-3"></i> Tous en Retard
                                </button>

                                <div class="h-6 w-px bg-gray-200 hidden sm:block mx-1" x-show="! isPast && (needsSaving() || isSaving)" x-cloak></div>

                                <button x-show="! isPast && (needsSaving() || isSaving)" x-cloak
                                        @click="save()" :disabled="isSaving" type="button"
                                        class="text-[10px] font-extrabold uppercase tracking-wider px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center gap-1.5 shadow disabled:opacity-50">
                                    <i data-lucide="check-circle-2" class="w-3 h-3"></i>
                                    <span x-show="!isSaving">
                                        <span x-show="manualModificationsCount > 0">Enregistrer (<span x-text="manualModificationsCount"></span>)</span>
                                        <span x-show="manualModificationsCount === 0">Enregistrer &amp; Quitter</span>
                                    </span>
                                    <span x-show="isSaving">Enregistrement...</span>
                                </button>

                                <button x-show="! isPast && needsSaving() && !isSaving" x-cloak
                                        @click="resetDraft()" type="button"
                                        class="text-[10px] font-bold uppercase tracking-wider px-3 py-2 bg-white border border-gray-200 text-gray-600 hover:text-gray-800 rounded-lg flex items-center gap-1">
                                    <i data-lucide="refresh-cw" class="w-3 h-3"></i> Annuler
                                </button>

                                <button x-show="! isPast && ! needsSaving() && ! isSaving && presentCount > 0" x-cloak
                                        @click="reinitialize()" type="button"
                                        class="text-[10px] font-bold uppercase tracking-wider px-3.5 py-2 bg-red-50 border border-red-200 text-red-700 hover:bg-red-100 rounded-lg flex items-center gap-1.5"
                                        title="Supprimer toutes les présences et redémarrer la séance">
                                    <i data-lucide="refresh-cw" class="w-3 h-3"></i> Réinitialiser
                                </button>
                            </div>
                        </div>
                    </div>

                    <form @submit.prevent="save()" id="registry-form">
                        @csrf
                        <div class="max-h-[480px] overflow-y-auto divide-y divide-gray-100">
                            <template x-for="student in filteredStudents" :key="student.id">
                                <div class="px-6 py-3"
                                     :class="isModified(student.id) ? 'bg-amber-50/40' : ''">
                                    <div class="flex flex-col lg:flex-row lg:items-center gap-3 lg:gap-4">
                                        <div class="flex items-center gap-3 min-w-0 flex-1">
                                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-sm shrink-0"
                                                 x-text="(student.name || '?').charAt(0).toUpperCase()"></div>
                                            <div class="min-w-0 flex-1">
                                                <div class="flex items-center gap-2">
                                                    <p class="text-sm font-bold text-gray-800 truncate" x-text="student.name"></p>
                                                    <span x-show="isModified(student.id)" x-cloak
                                                          class="inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-extrabold uppercase bg-amber-100 text-amber-700 border border-amber-200 animate-pulse">
                                                        Modifié
                                                    </span>
                                                </div>
                                                <p class="text-[10px] text-gray-500 font-mono" x-text="student.matricule"></p>
                                                <p class="text-[10px] text-gray-400 truncate" x-text="student.email"></p>
                                            </div>
                                        </div>

                                        <div>
                                            <span x-show="currentStatus(student.id) === 'present'"
                                                  class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">
                                                <i data-lucide="check-circle-2" class="w-3 h-3"></i>
                                                <span>Présent <span x-show="student.recorded_at"
                                                    x-text="'(' + formatTime(student.recorded_at) + ')'"></span></span>
                                            </span>
                                            <span x-show="currentStatus(student.id) === 'late'"
                                                  class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700">
                                                <i data-lucide="clock" class="w-3 h-3"></i> Retard
                                            </span>
                                            <span x-show="currentStatus(student.id) === 'absent' && hasRecord(student.id)"
                                                  class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-700">
                                                <i data-lucide="x" class="w-3 h-3"></i> Absent
                                            </span>
                                            <span x-show="currentStatus(student.id) === 'absent' && ! hasRecord(student.id)"
                                                  class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold border border-gray-200 bg-white text-gray-500">
                                                <i data-lucide="x" class="w-3 h-3"></i> Absent (Non validé)
                                            </span>
                                        </div>

                                        <div class="flex justify-end gap-1.5 shrink-0 flex-wrap">
                                            <button @click="setStatus(student.id, 'present')" type="button" :disabled="isPast"
                                                    :class="currentStatus(student.id) === 'present' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white border-gray-200 text-gray-500 hover:text-emerald-700 hover:bg-emerald-50'"
                                                    class="px-2.5 py-1.5 rounded text-[10px] font-bold uppercase tracking-wider border transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                                Présent
                                            </button>
                                            <button @click="setStatus(student.id, 'late')" type="button" :disabled="isPast"
                                                    :class="currentStatus(student.id) === 'late' ? 'bg-amber-500 text-white border-amber-500' : 'bg-white border-gray-200 text-gray-500 hover:text-amber-700 hover:bg-amber-50'"
                                                    class="px-2.5 py-1.5 rounded text-[10px] font-bold uppercase tracking-wider border transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                                Retard
                                            </button>
                                            <button @click="setStatus(student.id, 'absent')" type="button" :disabled="isPast"
                                                    :class="currentStatus(student.id) === 'absent' ? 'bg-red-600 text-white border-red-600' : 'bg-white border-gray-200 text-gray-500 hover:text-red-700 hover:bg-emerald-50'"
                                                    class="px-2.5 py-1.5 rounded text-[10px] font-bold uppercase tracking-wider border transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                                Absent
                                            </button>
                                            <div class="h-5 w-px bg-gray-200 mx-0.5"></div>
                                            <button @click="toggleComment(student.id)" type="button" :disabled="isPast"
                                                    :class="getDraftComment(student.id) ? 'bg-blue-50 border-blue-200 text-blue-600' : 'bg-white border-gray-200 text-gray-500 hover:text-gray-800'"
                                                    class="p-1.5 rounded border transition-colors flex items-center gap-0.5 disabled:opacity-50 disabled:cursor-not-allowed"
                                                    title="Commentaire">
                                                <i data-lucide="message-square" class="w-3 h-3"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div x-show="activeCommentId === student.id" x-cloak class="mt-2 flex items-center gap-2 pl-13">
                                        <i data-lucide="message-square" class="w-3.5 h-3.5 text-amber-500 shrink-0"></i>
                                        <input type="text"
                                               :value="getDraftComment(student.id)"
                                               @input="setDraftComment(student.id, $event.target.value)"
                                               placeholder="Écrire un commentaire..."
                                               :disabled="isPast"
                                               class="flex-1 px-2 py-1.5 text-xs border border-gray-200 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none disabled:bg-gray-50">
                                        <button @click="activeCommentId = null" type="button"
                                                class="text-[10px] font-bold uppercase text-gray-500 hover:text-gray-700">
                                            Fermer
                                        </button>
                                    </div>
                                </div>
                            </template>

                            <div x-show="filteredStudents.length === 0" class="px-6 py-12 text-center text-gray-400 text-sm">
                                Aucun stagiaire ne correspond à votre recherche.
                            </div>
                        </div>
                    </form>
                </x-ui.section-card>
            </div>
        </div>

        <div class="lg:col-span-1 flex flex-col gap-4">
            <x-ui.section-card>
                <h3 class="text-sm font-bold text-gray-800 mb-1">Statistiques d'Émargement</h3>
                <p class="text-xs text-gray-500 mb-4">Suivi de participation de la séance</p>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 shrink-0">
                        <i data-lucide="users" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <span class="text-gray-500 text-[10px] uppercase tracking-wider block">Taux de Scan Live</span>
                        <span class="text-lg font-extrabold text-gray-800 block">
                            <span x-text="presentCount"></span> / <span x-text="totalCount"></span>
                            (<span x-text="scanProgressPct"></span>%)
                        </span>
                    </div>
                </div>
            </x-ui.section-card>

            <x-ui.section-card padding="p-0">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-gray-800 flex items-center gap-1.5">
                        <i data-lucide="refresh-cw" class="w-3.5 h-3.5 text-blue-500 animate-spin"></i>
                        Scans en direct (<span x-text="liveScanFeed.length"></span>)
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Flux instantané des stagiaires qui ont scanné</p>
                </div>
                <div class="max-h-72 overflow-y-auto p-4 space-y-1.5">
                    <template x-for="feed in liveScanFeed" :key="feed.id">
                        <div class="px-3 py-2 rounded-lg border border-gray-100 bg-white flex items-center justify-between text-xs">
                            <span class="font-semibold text-gray-700 flex items-center gap-1.5">
                                <i data-lucide="user-check" class="w-3.5 h-3.5 text-emerald-500 shrink-0"></i>
                                <span x-text="feed.nom"></span>
                            </span>
                            <span class="text-gray-500 font-mono text-[10px]" x-text="feed.pointe_a || 'Présent'"></span>
                        </div>
                    </template>
                    <p x-show="liveScanFeed.length === 0" class="text-gray-400 text-xs py-4 italic text-center">
                        Aucun scan enregistré pour le moment.
                    </p>
                </div>
            </x-ui.section-card>
        </div>
    </div>

    <div x-show="isFullscreen" x-cloak
         class="fixed inset-0 z-50 bg-white flex flex-col items-center justify-center"
         @keydown.escape.window="isFullscreen = false">
        <button @click="isFullscreen = false" type="button"
                class="absolute top-4 right-4 inline-flex items-center gap-2 text-gray-500 hover:text-gray-800 bg-white border border-gray-200 px-3 py-2 rounded-lg">
            <i data-lucide="x" class="w-5 h-5"></i>
            <span class="text-xs font-bold">Quitter (Esc)</span>
        </button>
        <div class="text-center">
            <p class="text-xs font-mono text-gray-500 mb-3">
                {{ $session->module->name }} @if ($session->group)· {{ $session->group->name }}@endif
            </p>
            <div class="bg-white p-6 rounded-2xl border-4 border-blue-100 inline-block">
                <div id="qr-canvas-fullscreen" class="w-[480px] h-[480px]"></div>
            </div>
            <div x-show="textCode" x-cloak class="mt-4 bg-gray-50 border border-gray-200 rounded-xl py-2 px-6 block">
                <span class="text-xs text-gray-500 uppercase font-semibold block">Code d'émargement alternatif</span>
                <span class="text-3xl font-black tracking-widest text-indigo-700 font-mono select-all" x-text="textCode"></span>
            </div>
            <p class="text-3xl font-mono text-gray-700 mt-4">
                <span x-text="secondsLeft"></span>s
            </p>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
function qrSessionWorkspace() {
    return {
        sessionId: 0,
        currentToken: '',
        textCode: '',
        expiresAt: new Date(0),
        ttlSeconds: 30,
        secondsLeft: 30,

        isProjectorMode: true,
        isFullscreen: false,
        isSaving: false,

        started: false,
        isPast: false,

        students: [],
        draftStatuses: {},
        draftComments: {},
        activeCommentId: null,
        search: '',

        presentCount: 0,
        totalCount: 0,
        liveScanFeed: [],
        liveScansUrl: '',
        saveUrl: '',
        reinitUrl: '',
        backToListUrl: '',
        qrShowUrl: '',
        tokenUrl: '',
        csrf: '',

        _pollTimer: null,
        _tickTimer: null,

        init() {
            var cfg = this._loadConfig();
            if (!cfg) {
                console.error('[QR] No config found in #qr-config script tag');
                return;
            }
            this.sessionId = cfg.sessionId;
            this.currentToken = cfg.initialToken || '';
            this.textCode = cfg.initialTextCode || '';
            this.expiresAt = new Date((cfg.initialExpiresAt || 0) * 1000);
            this.ttlSeconds = cfg.ttlSeconds || 30;
            this.secondsLeft = this.ttlSeconds;
            this.students = cfg.students || [];
            this.presentCount = cfg.presentCount || 0;
            this.totalCount = cfg.totalCount || 0;
            this.started = !!cfg.started;
            this.isPast = !!cfg.isPast;
            this.liveScanFeed = cfg.initialFeed || [];
            this.liveScansUrl = cfg.liveScansUrl;
            this.saveUrl = cfg.saveUrl;
            this.reinitUrl = cfg.reinitUrl;
            this.backToListUrl = cfg.backToListUrl;
            this.qrShowUrl = cfg.qrShowUrl;
            this.tokenUrl = cfg.tokenUrl;
            this.csrf = this._getCsrf();

            this.students.forEach(function (s) {
                this.draftStatuses[s.id] = s.current_status || null;
                this.draftComments[s.id] = s.note || '';
            }.bind(this));

            if (cfg.presentCount > 0) {
                this.isProjectorMode = false;
            }

            if (this.started && this.currentToken) {
                this.renderQr('qr-canvas', this.currentToken);
            }

            var self = this;
            this._tickTimer = setInterval(function () { self.tick(); }, 1000);
            if (this.started) {
                this._pollTimer = setInterval(function () { self.pollLiveScans(); }, 4000);
            }
        },

        _loadConfig() {
            var el = document.getElementById('qr-config');
            if (!el) return null;
            try {
                return JSON.parse(el.textContent);
            } catch (e) {
                console.error('[QR] Failed to parse config', e);
                return null;
            }
        },

        _getCsrf() {
            var m = document.querySelector('meta[name="csrf-token"]');
            return m ? m.getAttribute('content') : '';
        },

        get filteredStudents() {
            var q = (this.search || '').trim().toLowerCase();
            if (!q) return this.students;
            return this.students.filter(function (s) {
                return ((s.name || '').toLowerCase().indexOf(q) !== -1)
                    || ((s.matricule || '').toLowerCase().indexOf(q) !== -1)
                    || ((s.email || '').toLowerCase().indexOf(q) !== -1);
            });
        },

        currentStatus(studentId) {
            return this.draftStatuses[studentId] || null;
        },

        hasRecord(studentId) {
            var s = this.students.find(function (x) { return x.id === studentId; });
            return !!(s && s.current_status);
        },

        isModified(studentId) {
            var s = this.students.find(function (x) { return x.id === studentId; });
            if (!s) return false;
            return this.draftStatuses[studentId] !== (s.current_status || null);
        },

        get manualModificationsCount() {
            var self = this;
            return this.students.filter(function (s) { return self.isModified(s.id); }).length;
        },

        needsSaving() {
            var anyStatusChange = this.manualModificationsCount > 0;
            var self = this;
            var anyCommentChange = this.students.some(function (s) {
                return (self.draftComments[s.id] || '') !== (s.note || '');
            });
            return anyStatusChange || anyCommentChange;
        },

        get scanProgressPct() {
            if (!this.totalCount) return 0;
            return Math.round((this.presentCount / this.totalCount) * 100);
        },

        showProjector() {
            this.isProjectorMode = true;
        },

        showEditor() {
            this.isProjectorMode = false;
        },

        setStatus(studentId, status) {
            if (this.isPast) return;
            this.draftStatuses[studentId] = status;
        },

        setAll(status) {
            if (this.isPast) return;
            var self = this;
            this.filteredStudents.forEach(function (s) { self.draftStatuses[s.id] = status; });
        },

        getDraftComment(studentId) {
            return this.draftComments[studentId] || '';
        },

        setDraftComment(studentId, value) {
            this.draftComments[studentId] = value;
        },

        toggleComment(studentId) {
            this.activeCommentId = (this.activeCommentId === studentId) ? null : studentId;
        },

        resetDraft() {
            var self = this;
            this.students.forEach(function (s) {
                self.draftStatuses[s.id] = s.current_status || null;
                self.draftComments[s.id] = s.note || '';
            });
            this.activeCommentId = null;
        },

        formatTime(iso) {
            try {
                return new Date(iso).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
            } catch (e) {
                return '';
            }
        },

        tick() {
            if (!this.started) return;
            var now = Date.now();
            var left = Math.max(0, Math.floor((this.expiresAt.getTime() - now) / 1000));
            this.secondsLeft = left;
            if (now >= this.expiresAt.getTime()) {
                this.rotate();
            }
        },

        get progressPct() {
            if (!this.started) return 0;
            var ttlMs = this.ttlSeconds * 1000;
            var left = Math.max(0, this.expiresAt.getTime() - Date.now());
            return Math.max(0, Math.min(100, (left / ttlMs) * 100));
        },

        rotate() {
            var self = this;
            fetch(this.tokenUrl, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': this.csrf },
                credentials: 'same-origin',
            })
            .then(function (res) { return res.ok ? res.json() : null; })
            .then(function (data) {
                if (!data || !data.token) return;
                self.currentToken = data.token;
                self.textCode = data.text_code || '';
                self.expiresAt = new Date(data.expires_at * 1000);
                self.renderQr('qr-canvas', self.currentToken);
                if (self.isFullscreen) self.renderQr('qr-canvas-fullscreen', self.currentToken);
            })
            .catch(function (e) { console.error('QR rotate failed', e); });
        },

        renderQr(elementId, payload) {
            if (!window.QRCode) return;
            var el = document.getElementById(elementId);
            if (!el) return;
            el.innerHTML = '';
            new QRCode(el, {
                text: payload,
                width: elementId === 'qr-canvas-fullscreen' ? 480 : 200,
                height: elementId === 'qr-canvas-fullscreen' ? 480 : 200,
                colorDark: '#111827',
                colorLight: '#ffffff',
            });
        },

        pollLiveScans() {
            var self = this;
            fetch(this.liveScansUrl, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': this.csrf },
                credentials: 'same-origin',
            })
            .then(function (res) { return res.ok ? res.json() : null; })
            .then(function (data) {
                if (!data) return;
                self.presentCount = data.present_count;
                self.totalCount = data.total_count;
                self.liveScanFeed = data.feed;
            })
            .catch(function () { /* silent */ });
        },

        save() {
            if (this.isPast) return;
            this.isSaving = true;
            var self = this;
            var payload = new FormData();
            payload.append('_token', this.csrf);
            this.students.forEach(function (s) {
                if (self.draftStatuses[s.id]) {
                    payload.append('attendance[' + s.id + '][status]', self.draftStatuses[s.id]);
                    var note = self.draftComments[s.id] || '';
                    if (note) payload.append('attendance[' + s.id + '][note]', note);
                }
            });

            fetch(this.saveUrl, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': this.csrf },
                body: payload,
                credentials: 'same-origin',
            })
            .then(function (res) {
                if (res.ok) {
                    window.location.href = self.backToListUrl;
                } else {
                    self.isSaving = false;
                    alert("Erreur lors de l'enregistrement de la feuille d'émargement.");
                }
            })
            .catch(function () {
                self.isSaving = false;
                alert("Erreur lors de l'enregistrement de la feuille d'émargement.");
            });
        },

        reinitialize() {
            if (!confirm('Réinitialiser la séance ? Toutes les présences seront supprimées.')) return;
            var self = this;
            fetch(this.reinitUrl, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': this.csrf, 'Accept': 'application/json' },
                credentials: 'same-origin',
            })
            .then(function (res) {
                if (res.ok || res.redirected) {
                    window.location.reload();
                } else {
                    alert('Erreur lors de la réinitialisation.');
                }
            })
            .catch(function () {
                alert('Erreur lors de la réinitialisation.');
            });
        },
    };
}
</script>
@endsection
