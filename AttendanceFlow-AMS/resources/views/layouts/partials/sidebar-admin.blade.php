<div class="space-y-1">
    <a href="{{ route('admin.dashboard') }}"
        class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('admin.dashboard') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg font-medium transition-colors">
        <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('admin.attendance.index') }}"
        class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('admin.attendance.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg transition-colors">
        <i data-lucide="clipboard-check" class="w-5 h-5"></i>
        <span>Attendance Entry</span>
        <span class="ml-auto bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">HOT</span>
    </a>
    <a href="{{ route('admin.reports.index') }}"
        class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('admin.reports.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg transition-colors">
        <i data-lucide="bar-chart-2" class="w-5 h-5"></i>
        <span>Reports</span>
    </a>
    <a href="{{ route('admin.students.index') }}"
        class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('admin.students.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg transition-colors">
        <i data-lucide="users" class="w-5 h-5"></i>
        <span>Students</span>
    </a>
    <a href="{{ route('admin.sessions.index') }}" 
       class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.sessions.*') ? 'text-blue-600 bg-blue-50 font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
        <i data-lucide="calendar" class="w-5 h-5"></i>
        <span>Sessions</span>
    </a>
    <a href="{{ route('admin.justifications.index') }}"
        class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('admin.justifications.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg transition-colors relative">
        <i data-lucide="file-text" class="w-5 h-5"></i>
        <span>Justifications</span>
        <span class="ml-auto bg-amber-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">7</span>
    </a>
</div>
