@extends('layouts.dashboard')

@section('title', 'Demandes de modification')
@section('page_title', 'Demandes de l\'emploi du temps')

@section('content')
<div class="space-y-6">

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 text-sm font-medium flex items-center gap-2">
            <i data-lucide="check-circle-2" class="w-4 h-4"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- FILTERS -->
    <x-ui.section-card padding="p-3">
        <div class="flex items-center gap-2 flex-wrap">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-500 mr-2">Filtrer :</span>
            @foreach(['pending' => 'En attente', 'approved' => 'Approuvées', 'rejected' => 'Rejetées', 'all' => 'Toutes'] as $key => $label)
                <a href="{{ route('admin.timetable.requests', ['status' => $key]) }}"
                   class="inline-flex items-center gap-1 text-xs font-bold uppercase tracking-wider px-3 py-1.5 rounded-lg transition-colors
                       {{ $status === $key ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-100 border border-gray-200' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </x-ui.section-card>

    @if($requests->isEmpty())
        <x-ui.section-card>
            <x-ui.empty-state
                icon="inbox"
                title="Aucune demande"
                description="Les enseignants n'ont pas soumis de demandes pour ce filtre."
            />
        </x-ui.section-card>
    @else
        <div class="space-y-3">
            @foreach($requests as $req)
                <x-ui.section-card padding="p-5">
                    <div class="flex items-start justify-between gap-4 flex-wrap">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded
                                    {{ $req->action === 'create' ? 'bg-emerald-50 text-emerald-700' : ($req->action === 'update' ? 'bg-blue-50 text-blue-700' : 'bg-red-50 text-red-700') }}">
                                    <i data-lucide="{{ $req->action === 'create' ? 'plus-circle' : ($req->action === 'update' ? 'edit-3' : 'trash-2') }}" class="w-3 h-3"></i>
                                    {{ $req->action === 'create' ? 'Création' : ($req->action === 'update' ? 'Modification' : 'Suppression') }}
                                </span>
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded
                                    {{ $req->status === 'pending' ? 'bg-amber-50 text-amber-700' : ($req->status === 'approved' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700') }}">
                                    {{ $req->status === 'pending' ? 'En attente' : ($req->status === 'approved' ? 'Approuvée' : 'Rejetée') }}
                                </span>
                            </div>

                            <p class="text-sm font-bold text-gray-800 mt-2">
                                {{ $req->session->module->name ?? \App\Models\Module::find($req->proposed_data['module_id'] ?? null)?->name ?? '—' }}
                                @if($req->session?->group)
                                    · Groupe {{ $req->session->group->name }}
                                @elseif($req->proposed_data['group_id'] ?? null)
                                    · Groupe {{ \App\Models\Group::find($req->proposed_data['group_id'])->name ?? '' }}
                                @endif
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                Demandée par <span class="font-semibold">{{ $req->teacherProfile->user->name }}</span>
                                · {{ $req->created_at->translatedFormat('d/m/Y à H:i') }}
                            </p>

                            @if($req->action !== 'delete')
                                <div class="mt-3 bg-gray-50 rounded-lg p-3 text-xs">
                                    <p class="font-bold text-gray-700 mb-1">Séance proposée :</p>
                                    <ul class="text-gray-600 space-y-0.5">
                                        <li>📅 {{ \Carbon\Carbon::parse($req->proposed_data['date'] ?? $req->proposed_data['start_time'])->translatedFormat('l d F Y') }}</li>
                                        <li>🕐 {{ \Carbon\Carbon::parse($req->proposed_data['start_time'])->format('H:i') }} – {{ \Carbon\Carbon::parse($req->proposed_data['end_time'])->format('H:i') }} ({{ $req->proposed_data['duration_hours'] }}h)</li>
                                        <li>📚 {{ ucfirst($req->proposed_data['type']) }}</li>
                                        @if(!empty($req->proposed_data['room']))
                                            <li>🚪 {{ $req->proposed_data['room'] }}</li>
                                        @endif
                                    </ul>
                                </div>
                            @endif

                            @if($req->reason)
                                <div class="mt-3 bg-blue-50 border border-blue-100 rounded-lg p-3 text-xs text-blue-900">
                                    <p class="font-bold mb-0.5">Motif de l'enseignant :</p>
                                    <p class="italic">{{ $req->reason }}</p>
                                </div>
                            @endif

                            @if($req->admin_note)
                                <div class="mt-3 bg-gray-50 border border-gray-200 rounded-lg p-3 text-xs text-gray-700">
                                    <p class="font-bold mb-0.5">Note admin :</p>
                                    <p>{{ $req->admin_note }}</p>
                                    <p class="text-[10px] text-gray-400 mt-1">Par {{ $req->reviewer?->name ?? '—' }} · {{ $req->reviewed_at?->translatedFormat('d/m/Y à H:i') }}</p>
                                </div>
                            @endif
                        </div>

                        @if($req->status === 'pending')
                            <div class="flex flex-col gap-2 w-full md:w-auto">
                                <form method="POST" action="{{ route('admin.timetable.requests.approve', $req->id) }}">
                                    @csrf
                                    <input type="hidden" name="admin_note" value="">
                                    <button type="submit"
                                            onclick="return confirm('Approuver et appliquer cette demande ?')"
                                            class="w-full inline-flex items-center justify-center gap-1.5 text-xs font-bold uppercase tracking-wider px-4 py-2 rounded-xl text-white bg-emerald-600 hover:bg-emerald-700 transition-colors shadow-sm">
                                        <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                        Approuver
                                    </button>
                                </form>
                                <button type="button"
                                        onclick="document.getElementById('reject-form-{{ $req->id }}').classList.toggle('hidden')"
                                        class="inline-flex items-center justify-center gap-1.5 text-xs font-bold uppercase tracking-wider px-4 py-2 rounded-xl text-white bg-red-600 hover:bg-red-700 transition-colors shadow-sm">
                                    <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                    Rejeter
                                </button>
                                <form id="reject-form-{{ $req->id }}" method="POST" action="{{ route('admin.timetable.requests.reject', $req->id) }}" class="hidden space-y-2">
                                    @csrf
                                    <textarea name="admin_note" required placeholder="Motif du rejet (obligatoire)…"
                                              class="w-full text-xs p-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-red-500 outline-none" rows="2"></textarea>
                                    <button type="submit"
                                            onclick="return confirm('Confirmer le rejet ?')"
                                            class="w-full inline-flex items-center justify-center gap-1.5 text-xs font-bold uppercase tracking-wider px-3 py-2 rounded-lg text-white bg-red-700 hover:bg-red-800">
                                        Confirmer le rejet
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </x-ui.section-card>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $requests->links() }}
        </div>
    @endif

</div>
@endsection
