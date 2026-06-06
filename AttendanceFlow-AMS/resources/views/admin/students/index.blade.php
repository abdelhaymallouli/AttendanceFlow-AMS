@extends('layouts.dashboard')

@section('title', 'Student Management')
@section('page_title', 'Student Directory')

@section('header_actions')
<div class="flex items-center gap-2">
    <a href="{{ route('admin.export.students') }}"
       class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-4 rounded-lg transition-colors flex items-center text-sm shadow-sm">
        <i data-lucide="users" class="w-4 h-4 mr-2"></i> Export Students (Excel)
    </a>

    <a href="" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors flex items-center text-sm shadow-sm">
        <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Add Student
    </a>
</div>
@endsection

@section('content')
<div class="space-y-6">

    <!-- FILTERS -->
    <x-ui.section-card padding="p-4">
        <form method="GET" action="{{ route('admin.students.index') }}"
              class="flex flex-col md:flex-row gap-4">

            <!-- SEARCH -->
            <div class="flex-1">
                <x-preline-search
                    name="search"
                    :value="request('search')"
                    icon="search"
                    placeholder="Rechercher par nom, email ou ID..."
                />
            </div>

            <!-- GROUP FILTER -->
            <div class="w-full md:w-[240px]">
                <x-preline-select
                    name="group_id"
                    :value="request('group_id')"
                    :options="$groups->mapWithKeys(fn($g) => [(string) $g->id => $g->name])->toArray()"
                    onChange="this.form.submit()"
                    icon="users"
                    blankText="Tous les groupes"
                />
            </div>

        </form>
    </x-ui.section-card>

    <!-- TABLE -->
    <x-ui.section-card :overflow="true" padding="none">

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">

                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-xs text-gray-500 uppercase">Student</th>
                        <th class="px-6 py-4 text-xs text-gray-500 uppercase">Group</th>
                        <th class="px-6 py-4 text-xs text-gray-500 uppercase">Attendance</th>
                        <th class="px-6 py-4 text-xs text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-4 text-right text-xs text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">

                    @forelse($students as $student)

                        <tr class="hover:bg-gray-50">

                            <!-- STUDENT -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-bold text-blue-600">
                                            {{ substr($student->user->name, 0, 1) }}
                                        </span>
                                    </div>

                                    <div>
                                        <p class="text-sm font-medium text-gray-800">
                                            {{ $student->user->name }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $student->student_id }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <!-- GROUP -->
                            <td class="px-6 py-4">
                                <x-ui.badge type="info">
                                    {{ $student->group->name ?? 'No Group' }}
                                </x-ui.badge>
                            </td>

                            <!-- ATTENDANCE (STATIC FOR NOW) -->
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-24 bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="h-2 bg-green-500 rounded-full" style="width: 94%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600">94%</span>
                                </div>
                            </td>

                            <!-- STATUS -->
                            <td class="px-6 py-4">
                                <x-ui.badge type="success" text="Good Standing" />
                            </td>

                            <!-- ACTIONS -->
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-3">
                                    <button class="text-blue-600 text-sm">View</button>
                                    <button class="text-gray-600 text-sm">Edit</button>
                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                No students found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>
        </div>

    </x-ui.section-card>

    <!-- PAGINATION -->
    <div class="mt-4">
        {{ $students->withQueryString()->links() }}
    </div>

</div>
@endsection