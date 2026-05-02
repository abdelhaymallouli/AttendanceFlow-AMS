@extends('layouts.dashboard')

@section('title', 'Student Management')
@section('page_title', 'Student Directory')

@section('header_actions')
<button class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors flex items-center text-sm shadow-sm">
    <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Add Student
</button>
@endsection

@section('content')
<div class="space-y-6" x-data="{
    searchQuery: '',
    filterStudents() {
        const query = this.searchQuery.toLowerCase();
        document.querySelectorAll('.student-row').forEach(row => {
            const name = row.getAttribute('data-name').toLowerCase();
            const matricule = row.getAttribute('data-matricule').toLowerCase();
            if (name.includes(query) || matricule.includes(query)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
}">
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <x-ui.stat-card 
            title="Total Students" 
            :value="\App\Models\StudentProfile::count()" 
            icon="users" 
            color="blue" 
        />
        <x-ui.stat-card 
            title="Avg Attendance" 
            value="94%" 
            icon="trending-up" 
            color="green" 
        />
        <x-ui.stat-card 
            title="At Risk" 
            value="12" 
            icon="alert-circle" 
            color="amber" 
        />
        <x-ui.stat-card 
            title="Perfect Attendance" 
            value="156" 
            icon="award" 
            color="purple" 
        />
    </div>

    <!-- Filters -->
    <x-ui.section-card padding="p-4" class="mb-6">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-5 h-5 text-gray-400"></i>
                    </div>
                    <input x-model="searchQuery" @input="filterStudents()" type="text"
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none"
                        placeholder="Search by name or ID...">
                </div>
            </div>
            <div class="flex gap-2">
                <select class="py-2 px-3 border border-gray-300 rounded-lg text-sm text-gray-700 bg-white focus:ring-2 focus:ring-blue-500 outline-none cursor-pointer">
                    <option value="">All Groups</option>
                    @foreach(\App\Models\Group::all() as $group)
                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </x-ui.section-card>

    <!-- Student Table -->
    <x-ui.section-card :overflow="true" padding="none">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                        <th class="px-6 py-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Group</th>
                        <th class="px-6 py-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Attendance</th>
                        <th class="px-6 py-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Manage</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse(\App\Models\StudentProfile::with(['user', 'group'])->latest()->get() as $student)
                    <tr class="student-row hover:bg-gray-50 transition-colors" data-name="{{ $student->user->name }}" data-matricule="{{ $student->student_id }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                                    <span class="text-sm font-medium text-blue-600">{{ substr($student->user->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">{{ $student->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $student->student_id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <x-ui.badge type="info" :text="$student->group->name ?? 'G1'" />
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-24 bg-gray-200 rounded-full h-2 mr-2">
                                    <div class="h-2 rounded-full bg-green-500" style="width: 94%"></div>
                                </div>
                                <span class="text-sm text-gray-600">94%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <x-ui.badge type="success" text="Good Standing" />
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-3">
                                <button class="text-blue-600 hover:text-indigo-900 text-sm font-medium transition-colors" title="View Profile">
                                    View
                                </button>
                                <button class="text-gray-600 hover:text-gray-900 text-sm font-medium transition-colors" title="Edit Profile">
                                    Edit
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <x-ui.empty-state 
                                icon="users-x" 
                                title="The student directory is empty" 
                                class="border-0 shadow-none"
                            />
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.section-card>
</div>
@endsection
