@extends('mobile.layouts.app')

@section('title', 'Mes Séances')
@section('header_title', 'Séances')

@section('content')
<div class="px-4 pt-4 pb-24">

    <h2 class="text-lg font-bold text-gray-800 mb-1">Mes Séances</h2>
    <p class="text-xs text-gray-500 mb-4">{{ count($sessions) }} séance(s)</p>

    @if(count($sessions) > 0)
        <div class="space-y-3">
            @foreach($sessions as $s)
                @php
                    $start = \Carbon\Carbon::parse($s['start_time']);
                    $end = \Carbon\Carbon::parse($s['end_time']);
                    $isPast = $end->isPast();
                    $isToday = $start->isToday();
                @endphp
                <a href="{{ route('mobile.session.detail', $s['id']) }}" class="block bg-white rounded-2xl border border-gray-100 p-4 shadow-sm active:scale-[0.98] transition-all">
                    <div class="flex items-start gap-3">
                        <div class="w-12 h-12 {{ $isToday ? 'bg-blue-50' : ($isPast ? 'bg-gray-50' : 'bg-blue-50') }} rounded-xl flex flex-col items-center justify-center flex-shrink-0">
                            <span class="text-[10px] font-bold text-gray-400 uppercase">{{ $start->format('M') }}</span>
                            <span class="text-sm font-black {{ $isToday ? 'text-blue-600' : ($isPast ? 'text-gray-400' : 'text-gray-700') }}">{{ $start->format('d') }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-gray-800">{{ $s['module']['name'] ?? 'Séance' }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ $start->format('H:i') }} — {{ $end->format('H:i') }}
                            </p>
                            <div class="flex items-center gap-2 mt-2">
                                @if(isset($s['teacherProfile']['user']['name']))
                                    <span class="text-[10px] text-gray-400 font-medium">{{ $s['teacherProfile']['user']['name'] }}</span>
                                @endif
                                @if(isset($s['room']) && $s['room'])
                                    <span class="text-[10px] text-gray-400">&middot; {{ $s['room'] }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-col items-end gap-1 shrink-0">
                            @if($isToday)
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-blue-100 text-blue-700">Aujourd'hui</span>
                            @elseif($isPast)
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">Passée</span>
                            @endif
                            <i data-lucide="chevron-right" class="w-4 h-4 text-gray-300"></i>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-3xl border border-gray-100 p-12 text-center shadow-sm">
            <div class="w-16 h-16 bg-blue-50 rounded-3xl flex items-center justify-center mx-auto mb-4">
                <i data-lucide="calendar" class="w-8 h-8 text-blue-400"></i>
            </div>
            <h3 class="text-sm font-bold text-gray-800 mb-1">Aucune séance</h3>
            <p class="text-xs text-gray-500">Aucune séance planifiée pour ton groupe.</p>
        </div>
    @endif

</div>
@endsection
