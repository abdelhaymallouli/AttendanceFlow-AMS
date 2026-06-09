@extends('mobile.layouts.app')

@section('title', 'Mes Justifications')
@section('header_title', 'Justifications')

@section('content')
<div class="px-4 pt-4 pb-24">

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-2xl p-4 mb-4 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Mes Justifications</h2>
            <p class="text-xs text-gray-500">{{ count($justifications) }} justification(s)</p>
        </div>
        <a href="{{ route('mobile.justifications.create') }}" class="bg-blue-600 text-white text-xs font-bold px-4 py-2 rounded-xl active:scale-95 transition-all">
            + Nouvelle
        </a>
    </div>

    @if(count($justifications) > 0)
        <div class="space-y-3">
            @foreach($justifications as $j)
                @php
                    $statusColors = [
                        'pending' => 'bg-amber-100 text-amber-700',
                        'approved' => 'bg-green-100 text-green-700',
                        'rejected' => 'bg-red-100 text-red-700',
                    ];
                    $statusLabels = [
                        'pending' => 'En attente',
                        'approved' => 'Approuvée',
                        'rejected' => 'Refusée',
                    ];
                    $statusIcons = [
                        'pending' => 'clock',
                        'approved' => 'check-circle',
                        'rejected' => 'x-circle',
                    ];
                    $s = $j['status'] ?? 'pending';
                @endphp
                <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 {{ $statusColors[$s] ?? 'bg-gray-100' }} rounded-xl flex items-center justify-center flex-shrink-0">
                            <i data-lucide="{{ $statusIcons[$s] ?? 'file-text' }}" class="w-5 h-5"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-sm font-bold text-gray-800">Justification</p>
                                <span class="text-[10px] font-bold px-2 py-1 rounded-lg uppercase shrink-0 {{ $statusColors[$s] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $statusLabels[$s] ?? $s }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-600 mt-1 line-clamp-2">{{ $j['reason'] ?? '' }}</p>
                            <div class="flex items-center gap-3 mt-2">
                                @if(isset($j['start_date']))
                                    <p class="text-[10px] text-gray-400">
                                        {{ \Carbon\Carbon::parse($j['start_date'])->format('d/m/Y') }}
                                        @if(isset($j['end_date']) && $j['end_date'] !== $j['start_date'])
                                            — {{ \Carbon\Carbon::parse($j['end_date'])->format('d/m/Y') }}
                                        @endif
                                    </p>
                                @endif
                                @if(isset($j['submitted_at']))
                                    <p class="text-[10px] text-gray-400">Soumise {{ \Carbon\Carbon::parse($j['submitted_at'])->diffForHumans() }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-3xl border border-gray-100 p-12 text-center shadow-sm">
            <div class="w-16 h-16 bg-blue-50 rounded-3xl flex items-center justify-center mx-auto mb-4">
                <i data-lucide="file-text" class="w-8 h-8 text-blue-400"></i>
            </div>
            <h3 class="text-sm font-bold text-gray-800 mb-1">Aucune justification</h3>
            <p class="text-xs text-gray-500 mb-4">Tu n'as pas encore soumis de justificatif.</p>
            <a href="{{ route('mobile.justifications.create') }}" class="inline-block bg-blue-600 text-white text-xs font-bold px-5 py-2.5 rounded-xl active:scale-95 transition-all">
                Créer une justification
            </a>
        </div>
    @endif

</div>
@endsection
