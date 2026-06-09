@extends('mobile.layouts.app')

@section('title', 'Mes Absences')
@section('header_title', 'Absences')

@section('content')
<div class="px-4 pt-4 pb-24">

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-2xl p-4 mb-4 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Mes Absences</h2>
            <p class="text-xs text-gray-500">{{ count($absences) }} absence(s) au total</p>
        </div>
    </div>

    @if(count($absences) > 0)
        <div class="space-y-3">
            @foreach($absences as $record)
                <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 {{ $record['is_justified'] ? 'bg-green-50' : 'bg-red-50' }} rounded-xl flex items-center justify-center flex-shrink-0">
                            <i data-lucide="{{ $record['is_justified'] ? 'check-circle' : 'alert-circle' }}" class="w-5 h-5 {{ $record['is_justified'] ? 'text-green-500' : 'text-red-500' }}"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-sm font-bold text-gray-800">{{ $record['session']['module']['name'] ?? 'Séance' }}</p>
                                <span class="text-[10px] font-bold px-2 py-1 rounded-lg uppercase shrink-0
                                    {{ $record['is_justified'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $record['is_justified'] ? 'Justifiée' : 'Non justifiée' }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ \Carbon\Carbon::parse($record['date'])->format('d/m/Y') }}
                                @if(isset($record['session']['start_time']))
                                    &middot; {{ \Carbon\Carbon::parse($record['session']['start_time'])->format('H:i') }}
                                @endif
                            </p>
                            @if(isset($record['session']['teacherProfile']['user']['name']))
                                <p class="text-[10px] text-gray-400 mt-1">Enseignant: {{ $record['session']['teacherProfile']['user']['name'] }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if(collect($absences)->where('is_justified', false)->count() > 0)
            <a href="{{ route('mobile.justifications.create') }}" class="block mt-6 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-2xl p-4 text-center font-bold text-sm shadow-md shadow-blue-200 active:scale-95 transition-all">
                Justifier une absence
            </a>
        @endif
    @else
        <div class="bg-white rounded-3xl border border-gray-100 p-12 text-center shadow-sm">
            <div class="w-16 h-16 bg-green-50 rounded-3xl flex items-center justify-center mx-auto mb-4">
                <i data-lucide="check-circle" class="w-8 h-8 text-green-500"></i>
            </div>
            <h3 class="text-sm font-bold text-gray-800 mb-1">Aucune absence</h3>
            <p class="text-xs text-gray-500">Tu es assidu, bravo !</p>
        </div>
    @endif

</div>
@endsection
