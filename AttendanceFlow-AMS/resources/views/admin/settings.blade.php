@extends('layouts.dashboard')

@section('title', 'Configuration QR & Sécurité')
@section('page_title', 'Configuration QR & Sécurité')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 text-sm font-medium flex items-center gap-2">
            <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600"></i>
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Sidebar Panel: Info -->
            <div class="space-y-4">
                <div class="bg-gradient-to-br from-blue-600 to-indigo-700 text-white rounded-2xl p-6 shadow-md">
                    <h3 class="text-lg font-bold font-outfit mb-2">Paramètres de Validation</h3>
                    <p class="text-xs opacity-90 leading-relaxed">
                        Configurez la barrière GPS et les réseaux Wi-Fi autorisés. Ces valeurs contrôlent directement la validation automatique des scans étudiants.
                    </p>
                    <div class="mt-6 border-t border-white/20 pt-4 space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                                <i data-lucide="map-pin" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <span class="text-[10px] text-white/75 block uppercase tracking-wider">Geofencing</span>
                                <span class="text-xs font-semibold">Haversine GPS Actif</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                                <i data-lucide="wifi" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <span class="text-[10px] text-white/75 block uppercase tracking-wider">IP / Réseau local</span>
                                <span class="text-xs font-semibold">Strict CIDR Match</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Fields Card -->
            <div class="md:col-span-2 space-y-6">
                
                <!-- GPS Section -->
                <x-ui.section-card>
                    <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                            <i data-lucide="navigation" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-800">Géolocalisation du Campus</h3>
                            <p class="text-xs text-gray-500">Coordonnées géographiques de l'établissement</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Latitude</label>
                            <input type="number" name="latitude" step="any" required
                                   value="{{ old('latitude', $location->latitude) }}"
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            @error('latitude') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Longitude</label>
                            <input type="number" name="longitude" step="any" required
                                   value="{{ old('longitude', $location->longitude) }}"
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            @error('longitude') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Rayon autorisé (Mètres)</label>
                            <div class="relative">
                                <input type="number" name="radius_meters" min="1" max="10000" required
                                       value="{{ old('radius_meters', $location->radius_meters) }}"
                                       class="w-full pl-3 pr-10 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                <span class="absolute inset-y-0 right-3 flex items-center text-xs text-gray-400 font-bold pointer-events-none">m</span>
                            </div>
                            @error('radius_meters') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            <p class="text-[10px] text-gray-500 mt-1">Les étudiants hors de ce rayon ne pourront pas émarger.</p>
                        </div>
                    </div>
                </x-ui.section-card>

                <!-- Network Section -->
                <x-ui.section-card>
                    <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <i data-lucide="wifi" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-800">Sous-réseaux Autorisés</h3>
                            <p class="text-xs text-gray-500">Adresses IP de confiance (Wi-Fi de l'école)</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1.5">Plages IP (Format CIDR, une par ligne)</label>
                            <textarea name="allowed_subnets" rows="5" placeholder="e.g. 192.168.10.0/24&#10;10.190.0.0/16"
                                      class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg font-mono focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">{{ old('allowed_subnets', implode("\n", $location->allowed_subnets ?? [])) }}</textarea>
                            @error('allowed_subnets') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            <p class="text-[10px] text-gray-500 mt-1">Exemple : <code>192.168.1.0/24</code> autorise toutes les IP de 192.168.1.1 à 192.168.1.254.</p>
                        </div>
                    </div>
                </x-ui.section-card>

                <!-- Actions -->
                <div class="flex justify-end gap-3">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-extrabold uppercase tracking-wider text-xs px-6 py-3 rounded-xl shadow transition-colors">
                        Enregistrer les configurations
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection
