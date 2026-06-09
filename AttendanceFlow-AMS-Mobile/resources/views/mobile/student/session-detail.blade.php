@extends('mobile.layouts.app')

@section('title', 'Détail Séance')
@section('header_title', 'Séance')

@section('content')
<div class="px-4 pt-4 pb-24">

    @if($session)
        @php
            $start = \Carbon\Carbon::parse($session['start_time']);
            $end = \Carbon\Carbon::parse($session['end_time']);
        @endphp

        <!-- Header -->
        <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-3xl p-5 mb-6 text-white shadow-lg shadow-blue-100">
            <h2 class="text-lg font-bold mb-1">{{ $session['module']['name'] ?? 'Séance' }}</h2>
            <p class="text-blue-200 text-xs font-medium">{{ $start->format('l d M Y') }}</p>
            <div class="flex items-center gap-4 mt-4">
                <div class="flex items-center gap-2">
                    <i data-lucide="clock" class="w-4 h-4 text-blue-200"></i>
                    <span class="text-sm font-medium">{{ $start->format('H:i') }} — {{ $end->format('H:i') }}</span>
                </div>
                @if(isset($session['room']) && $session['room'])
                    <div class="flex items-center gap-2">
                        <i data-lucide="map-pin" class="w-4 h-4 text-blue-200"></i>
                        <span class="text-sm font-medium">{{ $session['room'] }}</span>
                    </div>
                @endif
            </div>
            @if(isset($session['teacherProfile']['user']['name']))
                <div class="flex items-center gap-2 mt-3">
                    <i data-lucide="user" class="w-4 h-4 text-blue-200"></i>
                    <span class="text-sm font-medium">{{ $session['teacherProfile']['user']['name'] }}</span>
                </div>
            @endif
        </div>

        <!-- QR Scan Button -->
        <a href="{{ route('mobile.scan') }}" class="block bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-2xl p-4 mb-6 shadow-md shadow-blue-200 active:scale-95 transition-all">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                    <i data-lucide="qr-code" class="w-5 h-5"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-bold">Scanner le QR</p>
                    <p class="text-[10px] text-blue-100">Valider ta présence</p>
                </div>
                <i data-lucide="chevron-right" class="w-5 h-5 opacity-80"></i>
            </div>
        </a>
    @else
        <div class="bg-white rounded-3xl border border-gray-100 p-12 text-center shadow-sm">
            <p class="text-sm text-gray-500">Séance introuvable.</p>
        </div>
    @endif

</div>
@endsection
