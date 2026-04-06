<div class="space-y-1">
    <a href="{{ route('teacher.dashboard') }}"
        class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('teacher.dashboard') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg font-medium transition-colors">
        <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
        <span>My Dashboard</span>
    </a>
    <a href="#"
        class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
        <i data-lucide="clipboard-check" class="w-5 h-5"></i>
        <span>Attendance Entry</span>
        <span class="ml-auto bg-green-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">LIVE</span>
    </a>
    <a href="#"
        class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
        <i data-lucide="calendar" class="w-5 h-5"></i>
        <span>My Schedule</span>
    </a>
    <a href="#"
        class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
        <i data-lucide="users" class="w-5 h-5"></i>
        <span>My Students</span>
    </a>
</div>
