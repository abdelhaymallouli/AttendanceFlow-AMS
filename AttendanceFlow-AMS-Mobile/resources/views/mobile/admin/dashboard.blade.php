@extends('mobile.layouts.app')

@section('title', 'Admin Dashboard - Solicode AMS')
@section('header_title', 'Solicode AMS')

@section('content')
<div class="px-4 pt-4 pb-24">

    <!-- Greeting -->
    <div class="mb-5">
        <h2 class="text-xl font-bold text-gray-800">Bonjour, Admin 👋</h2>
        <p class="text-sm text-gray-500 mt-0.5">{{ $stats['date'] ?? \Carbon\Carbon::now()->format('l, j F Y') }}</p>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-2 gap-3 mb-6">
        <div class="bg-blue-600 rounded-2xl p-4 text-white shadow-lg shadow-blue-100">
            <div class="flex items-center justify-between mb-3">
                <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i data-lucide="users" class="w-4 h-4"></i>
                </div>
                <span class="text-[10px] bg-white bg-opacity-20 px-2 py-0.5 rounded-full font-bold uppercase">Total</span>
            </div>
            <p class="text-2xl font-bold">{{ number_format($stats['total_students'] ?? 0) }}</p>
            <p class="text-xs text-blue-200 mt-1 uppercase font-bold tracking-tighter">Étudiants</p>
        </div>
        
        <div class="bg-green-500 rounded-2xl p-4 text-white shadow-lg shadow-green-100">
            <div class="flex items-center justify-between mb-3">
                <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i data-lucide="trending-up" class="w-4 h-4"></i>
                </div>
                <span class="text-[10px] bg-white bg-opacity-20 px-2 py-0.5 rounded-full font-bold uppercase">Aujourd'hui</span>
            </div>
            <p class="text-2xl font-bold">{{ $stats['attendance_rate'] ?? 0 }}%</p>
            <p class="text-xs text-green-100 mt-1 uppercase font-bold tracking-tighter">Présence</p>
        </div>

        <div class="bg-amber-500 rounded-2xl p-4 text-white shadow-lg shadow-amber-100">
            <div class="flex items-center justify-between mb-3">
                <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                </div>
                <span class="text-[10px] bg-white bg-opacity-20 px-2 py-0.5 rounded-full font-bold uppercase">Alertes</span>
            </div>
            <p class="text-2xl font-bold">{{ $stats['pending_justifications'] ?? 0 }}</p>
            <p class="text-xs text-amber-100 mt-1 uppercase font-bold tracking-tighter">Justifications</p>
        </div>

        <div class="bg-indigo-500 rounded-2xl p-4 text-white shadow-lg shadow-indigo-100">
            <div class="flex items-center justify-between mb-3">
                <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i data-lucide="user-check" class="w-4 h-4"></i>
                </div>
                <span class="text-[10px] bg-white bg-opacity-20 px-2 py-0.5 rounded-full font-bold uppercase">Actifs</span>
            </div>
            <p class="text-2xl font-bold">{{ $stats['total_teachers'] ?? 0 }}</p>
            <p class="text-xs text-indigo-100 mt-1 uppercase font-bold tracking-tighter">Formateurs</p>
        </div>
    </div>

    <!-- Quick Actions Scroll -->
    <div class="mb-6">
        <h3 class="text-sm font-bold text-gray-800 mb-4 uppercase tracking-wider">Actions Rapides</h3>
        <div class="flex gap-4 overflow-x-auto pb-4 no-scrollbar">
            <a href="#" class="flex flex-col items-center gap-2 flex-shrink-0">
                <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center border border-blue-100 shadow-sm">
                    <i data-lucide="calendar-plus" class="w-7 h-7 text-blue-600"></i>
                </div>
                <span class="text-[10px] font-bold text-gray-500 uppercase">SÉANCE</span>
            </a>
            <a href="#" class="flex flex-col items-center gap-2 flex-shrink-0">
                <div class="w-16 h-16 bg-green-50 rounded-2xl flex items-center justify-center border border-green-100 shadow-sm">
                    <i data-lucide="users" class="w-7 h-7 text-green-600"></i>
                </div>
                <span class="text-[10px] font-bold text-gray-500 uppercase">STAGIAIRES</span>
            </a>
            <a href="#" class="flex flex-col items-center gap-2 flex-shrink-0">
                <div class="w-16 h-16 bg-amber-50 rounded-2xl flex items-center justify-center border border-amber-100 shadow-sm">
                    <i data-lucide="file-text" class="w-7 h-7 text-amber-600"></i>
                </div>
                <span class="text-[10px] font-bold text-gray-500 uppercase">DEMANDES</span>
            </a>
            <a href="#" class="flex flex-col items-center gap-2 flex-shrink-0">
                <div class="w-16 h-16 bg-indigo-50 rounded-2xl flex items-center justify-center border border-indigo-100 shadow-sm">
                    <i data-lucide="bar-chart-2" class="w-7 h-7 text-indigo-600"></i>
                </div>
                <span class="text-[10px] font-bold text-gray-500 uppercase">RAPPORTS</span>
            </a>
        </div>
    </div>

    <!-- Pending Requests List -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">Demandes en attente</h3>
            <a href="#" class="text-xs text-blue-600 font-bold">Tout voir</a>
        </div>
        <div class="space-y-3">
            @forelse($pendingJustifications ?? [] as $just)
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <span class="text-sm font-bold text-blue-600">{{ substr($just['student_profile']['user']['name'] ?? 'S', 0, 1) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-gray-800 truncate">{{ $just['student_profile']['user']['name'] ?? 'Étudiant' }}</p>
                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-tighter">{{ $just['reason'] ?? 'Motif non spécifié' }}</p>
                        </div>
                        <div class="flex gap-2">
                            <button class="w-8 h-8 bg-green-50 text-green-600 rounded-lg flex items-center justify-center border border-green-100 transition-all active:scale-90">
                                <i data-lucide="check" class="w-4 h-4"></i>
                            </button>
                            <button class="w-8 h-8 bg-red-50 text-red-500 rounded-lg flex items-center justify-center border border-red-100 transition-all active:scale-90">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-10 text-center text-gray-400 bg-white rounded-2xl border border-gray-100 border-dashed">
                    <p class="text-xs font-bold">Aucune demande en attente</p>
                </div>
            @endforelse
        </div>
    </div>

</div>

<!-- Overriding Bottom Nav for Admin (as per maquete) -->
@push('styles')
<style>
    .admin-nav-item.active i { color: #2563eb; }
    .admin-nav-item.active span { color: #2563eb; }
</style>
@endpush

@section('bottom_nav')
<nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 z-40 pb-2">
    <div class="flex items-center justify-around py-2">
        <a href="{{ route('mobile.admin.dashboard') }}" class="flex flex-col items-center gap-1 px-3 py-2 text-blue-600">
            <i data-lucide="home" class="w-5 h-5"></i>
            <span class="text-[10px] font-bold uppercase">Home</span>
        </a>
        <a href="#" class="flex flex-col items-center gap-1 px-3 py-2 text-gray-400">
            <i data-lucide="users" class="w-5 h-5"></i>
            <span class="text-[10px] font-bold uppercase">Sgt</span>
        </a>
        <div class="px-2">
            <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center -mt-6 shadow-lg shadow-blue-200 text-white">
                <i data-lucide="plus" class="w-6 h-6 text-white"></i>
            </div>
        </div>
        <a href="#" class="flex flex-col items-center gap-1 px-3 py-2 text-gray-400">
            <i data-lucide="file-text" class="w-5 h-5"></i>
            <span class="text-[10px] font-bold uppercase">Req</span>
        </a>
        <a href="#" class="flex flex-col items-center gap-1 px-3 py-2 text-gray-400">
            <i data-lucide="bar-chart-2" class="w-5 h-5"></i>
            <span class="text-[10px] font-bold uppercase">Rep</span>
        </a>
    </div>
</nav>
@endsection
@endsection
