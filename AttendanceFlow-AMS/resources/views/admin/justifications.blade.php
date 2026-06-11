@extends('layouts.dashboard')

@section('title', 'Gestion des justifications')
@section('page_title', 'Justifications d\'absence')

@section('header_actions')
<div class="flex items-center gap-3">
    <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-amber-50 text-amber-600 border border-amber-100">
        {{ $pendingCount ?? 0 }} En attente
    </span>
</div>
@endsection

@section('content')
<div class="space-y-6" x-data="justificationsApp(serverJustifications)">
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <x-ui.stat-card 
            title="En attente" 
            alpineValue="pendingCount" 
            icon="clock" 
            color="amber" 
        />
        <x-ui.stat-card 
            title="Accepté" 
            alpineValue="approvedCount" 
            icon="check-circle" 
            color="green" 
        />
        <x-ui.stat-card 
            title="Refusé" 
            alpineValue="rejectedCount" 
            icon="x-circle" 
            color="red" 
        />
        <x-ui.stat-card 
            title="Total" 
            alpineValue="justifications.length" 
            icon="file-text" 
            color="blue" 
        />
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <x-preline-search
                    name="search"
                    icon="search"
                    placeholder="Rechercher par nom ou ID..."
                    onChange="searchQuery = this.value; filterJustifications()"
                />
            </div>
            <div class="flex gap-2 w-full md:w-[200px]">
                <select name="filterStatus" onchange="filterStatus = this.value; filterJustifications()" class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    <option value="all">Tous les statuts</option>
                    <option value="pending">En attente</option>
                    <option value="approved">Accepté</option>
                    <option value="rejected">Refusé</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Justifications List -->
    <div class="space-y-4">
        <template x-for="justification in filteredJustifications" :key="justification.id">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 transition-all hover:shadow-md">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <!-- Student Info -->
                    <div class="flex items-center space-x-4 w-full lg:w-1/4">
                        <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-blue-600 font-bold" x-text="justification.studentName.charAt(0)"></span>
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-semibold text-gray-800 truncate" x-text="justification.studentName"></h4>
                            <p class="text-sm text-gray-500 truncate" x-text="justification.studentId + ' • ' + justification.grade"></p>
                        </div>
                    </div>

                    <!-- Absence Info -->
                    <div class="flex items-center space-x-4 text-sm w-full lg:w-1/4">
                        <div class="flex items-center space-x-2">
                            <i data-lucide="calendar" class="w-4 h-4 text-gray-400"></i>
                            <span class="text-gray-600" x-text="justification.absenceDate"></span>
                        </div>
                    </div>

                    <!-- Document -->
                    <div class="flex items-center space-x-3 w-full lg:w-1/5">
                        <a :href="justification.documentUrl" target="_blank" class="flex items-center space-x-2 px-3 py-2 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors border border-gray-100">
                            <i data-lucide="file-text" class="w-4 h-4 text-gray-500"></i>
                            <span class="text-sm text-blue-600 font-medium">Voir le doc</span>
                        </a>
                    </div>

                    <!-- Status & Actions -->
                    <div class="flex items-center justify-between lg:justify-end space-x-3 w-full lg:w-auto">
                        <x-ui.badge alpineType="justification.status" alpineText="justification.status.charAt(0).toUpperCase() + justification.status.slice(1)" />
                        
                        <template x-if="justification.status === 'pending'">
                            <div class="flex space-x-2">
                                <form :action="justification.updateUrl" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="bg-green-50 hover:bg-green-100 text-green-600 p-2 rounded-lg transition-colors" title="Approuver">
                                        <i data-lucide="check" class="w-4 h-4"></i>
                                    </button>
                                </form>
                                <form :action="justification.updateUrl" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 p-2 rounded-lg transition-colors" title="Refuser">
                                        <i data-lucide="x" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Reason -->
                <div class="mt-4 pt-4 border-t border-gray-50">
                    <p class="text-sm text-gray-600">
                        <span class="font-medium text-gray-800">Motif :</span>
                        <span x-text="justification.reason"></span>
                    </p>
                    <p class="text-xs text-gray-400 mt-1" x-text="'Soumis le ' + justification.submittedDate"></p>
                </div>
            </div>
        </template>

        <!-- Empty State -->
        <div x-show="filteredJustifications.length === 0" style="display: none;">
            <x-ui.empty-state 
                icon="inbox"
                title="Aucune justification trouvée"
                subtitle="Essayez d'ajuster vos filtres pour trouver ce que vous cherchez."
            />
        </div>
    </div>

</div>

@push('scripts')
<script>
    const serverJustifications = @json($justifications);
</script>
@endpush
@endsection