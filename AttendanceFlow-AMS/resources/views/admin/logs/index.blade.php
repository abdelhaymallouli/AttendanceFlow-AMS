@extends('layouts.dashboard')

@section('title', 'Journaux d\'émargement')
@section('page_title', 'Journaux d\'émargement')

@section('content')
<div class="space-y-6">

    <!-- Filters Card -->
    <x-ui.section-card padding="p-4">
        <form method="GET" action="{{ route('admin.logs.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-center">
            
            <div class="relative">
                <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute inset-y-0 left-3 my-auto pointer-events-none"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher par nom, matricule..."
                       class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            </div>

            <div>
                <select name="method" onchange="this.form.submit()"
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    <option value="">Méthode d'émargement</option>
                    <option value="qr" {{ request('method') === 'qr' ? 'selected' : '' }}>QR Code / Mobile</option>
                    <option value="manual" {{ request('method') === 'manual' ? 'selected' : '' }}>Manuel (Enseignant)</option>
                    <option value="offline" {{ request('method') === 'offline' ? 'selected' : '' }}>Sync Offline</option>
                </select>
            </div>

            <div>
                <select name="status" onchange="this.form.submit()"
                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    <option value="">Statut</option>
                    <option value="present" {{ request('status') === 'present' ? 'selected' : '' }}>Présent</option>
                    <option value="late" {{ request('status') === 'late' ? 'selected' : '' }}>Retard</option>
                    <option value="absent_unexcused" {{ request('status') === 'absent_unexcused' ? 'selected' : '' }}>Absent non justifié</option>
                    <option value="absent_excused" {{ request('status') === 'absent_excused' ? 'selected' : '' }}>Absent justifié</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold uppercase tracking-wider transition-colors">
                    Filtrer
                </button>
                <a href="{{ route('admin.logs.index') }}" class="py-2 px-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-bold uppercase tracking-wider transition-colors flex items-center justify-center">
                    <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                </a>
            </div>

        </form>
    </x-ui.section-card>

    <!-- Table Card -->
    <x-ui.section-card padding="p-0">
        @if($logs->isEmpty())
            <x-ui.empty-state
                icon="shield-alert"
                title="Aucun journal d'émargement trouvé"
                description="Ajustez vos filtres ou attendez que les stagiaires commencent à émarger."
            />
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-[10px] font-bold uppercase tracking-wider text-gray-500">
                            <th class="text-left px-4 py-3">Stagiaire</th>
                            <th class="text-left px-4 py-3">Séance</th>
                            <th class="text-left px-4 py-3">Date & Heure</th>
                            <th class="text-left px-4 py-3">Réseau / IP</th>
                            <th class="text-left px-4 py-3">GPS / Dist.</th>
                            <th class="text-left px-4 py-3">Appareil</th>
                            <th class="text-center px-3 py-3">Score</th>
                            <th class="text-center px-3 py-3">Validation</th>
                            <th class="text-right px-4 py-3">Résultat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @foreach($logs as $log)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold text-xs">
                                            {{ substr($log->studentProfile->user->name ?? '?', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-800">{{ $log->studentProfile->user->name ?? 'Inconnu' }}</p>
                                            <p class="text-[10px] text-gray-400 font-mono">{{ $log->studentProfile->matricule ?? '—' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-gray-800">{{ $log->session->module->name ?? 'Module' }}</p>
                                    <p class="text-[10px] text-gray-400 uppercase">{{ $log->session->type ?? 'CM' }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-xs text-gray-600">{{ $log->updated_at->format('d/m/Y') }}</p>
                                    <p class="text-[10px] text-gray-400 font-mono">{{ $log->updated_at->format('H:i:s') }}</p>
                                </td>
                                <td class="px-4 py-3 font-mono text-xs">
                                    @if($log->wifi_ip)
                                        <span class="text-gray-700">{{ $log->wifi_ip }}</span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($log->latitude && $log->longitude)
                                        <span class="text-xs text-gray-700 font-mono block">{{ round($log->latitude, 4) }}, {{ round($log->longitude, 4) }}</span>
                                        <span class="inline-flex items-center gap-0.5 text-[9px] text-indigo-600 font-bold">
                                            <i data-lucide="map-pin" class="w-2.5 h-2.5"></i>
                                            {{ $log->distance_meters }}m du campus
                                        </span>
                                    @else
                                        <span class="text-gray-400 font-mono text-xs">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 max-w-[150px] truncate" title="{{ $log->deviceFingerprint->user_agent ?? 'Non renseigné' }}">
                                    <span class="text-xs text-gray-600 font-mono">
                                        {{ $log->deviceFingerprint ? Str::limit($log->deviceFingerprint->user_agent, 20) : '—' }}
                                    </span>
                                </td>
                                <td class="px-3 py-3 text-center font-bold">
                                    @if($log->validation_score !== null)
                                        <span class="text-xs {{ $log->validation_score >= 70 ? 'text-emerald-600' : 'text-amber-600' }}">
                                            {{ $log->validation_score }}/100
                                        </span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-center">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                        @if($log->check_in_method === 'qr') bg-indigo-50 border border-indigo-200 text-indigo-700
                                        @elseif($log->check_in_method === 'manual') bg-gray-100 border border-gray-200 text-gray-600
                                        @else bg-amber-50 border border-amber-200 text-amber-700
                                        @endif">
                                        {{ $log->check_in_method }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold
                                        @if($log->status === 'present') bg-emerald-50 text-emerald-700
                                        @elseif($log->status === 'late') bg-amber-50 text-amber-700
                                        @else bg-red-50 text-red-700
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $log->status)) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $logs->links() }}
            </div>
        @endif
    </x-ui.section-card>

</div>
@endsection
