@extends('layouts.dashboard')

@section('title', 'Justification Hub')
@section('page_title', 'Absence Justifications')



@section('header_actions')
<div class="flex items-center gap-3">
    <span class="inline-flex items-center gap-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-amber-50 text-amber-600 border border-amber-100">
        {{ \App\Models\Justification::where('status', 'pending')->count() }} Pending Approval
    </span>
</div>
@endsection

@section('content')
<div class="space-y-6" x-data="justificationsApp(serverJustifications)">
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <x-ui.stat-card 
            title="Pending" 
            alpineValue="pendingCount" 
            icon="clock" 
            color="amber" 
        />
        <x-ui.stat-card 
            title="Approved" 
            alpineValue="approvedCount" 
            icon="check-circle" 
            color="green" 
        />
        <x-ui.stat-card 
            title="Rejected" 
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
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-5 h-5 text-gray-400"></i>
                    </div>
                    <input x-model="searchQuery" @input="filterJustifications()" type="text" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none" placeholder="Search by student name or ID...">
                </div>
            </div>
            <div class="flex gap-2">
                <div class="relative min-w-[150px]">
                    <select x-model="filterStatus" @change="filterJustifications()" class="w-full py-2 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none bg-white text-sm">
                        <option value="all">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
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
                            <span class="text-sm text-blue-600 font-medium">View Doc</span>
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
                                    <button type="submit" class="bg-green-50 hover:bg-green-100 text-green-600 p-2 rounded-lg transition-colors" title="Approve">
                                        <i data-lucide="check" class="w-4 h-4"></i>
                                    </button>
                                </form>
                                <form :action="justification.updateUrl" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 p-2 rounded-lg transition-colors" title="Reject">
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
                        <span class="font-medium text-gray-800">Reason:</span>
                        <span x-text="justification.reason"></span>
                    </p>
                    <p class="text-xs text-gray-400 mt-1" x-text="'Submitted on ' + justification.submittedDate"></p>
                </div>
            </div>
        </template>

        <!-- Empty State -->
        <div x-show="filteredJustifications.length === 0" style="display: none;">
            <x-ui.empty-state 
                icon="inbox"
                title="No justifications found"
                subtitle="Try adjusting your search or filters to find what you're looking for."
            />
        </div>
    </div>

</div>

@php
    $serverJustifications = \App\Models\Justification::with(['studentProfile.user', 'studentProfile.group'])
        ->latest()
        ->get()
        ->map(function($j) {
            return [
                'id'            => $j->id,
                'studentName'   => $j->studentProfile->user->name ?? 'Unknown',
                'studentId'     => $j->studentProfile->student_id ?? 'N/A',
                'grade'         => $j->studentProfile->group->name ?? 'G1',
                'absenceDate'   => \Carbon\Carbon::parse($j->start_date)->format('M d, Y'),
                'documentUrl'   => \Illuminate\Support\Facades\Storage::url($j->file_path),
                'reason'        => $j->reason,
                'submittedDate' => \Carbon\Carbon::parse($j->created_at)->format('M d, Y'),
                'status'        => $j->status,
                'updateUrl'     => route('admin.justifications.update', $j->id),
            ];
        })
        ->values();
@endphp

@push('scripts')
<script>
    // Server data passed to the Alpine component (see resources/js/justifications.js)
    const serverJustifications = @json($serverJustifications);
</script>
@endpush
@endsection
