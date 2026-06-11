<div class="space-y-1">
    <a href="{{ route('teacher.dashboard') }}"
        class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('teacher.dashboard') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg font-medium transition-colors">
        <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
        <span>Mon espace</span>
    </a>
    <a href="{{ route('teacher.timetable.index') }}"
        class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('teacher.timetable.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg font-medium transition-colors">
        <i data-lucide="calendar-range" class="w-5 h-5"></i>
        <span>Emploi du temps</span>
    </a>
    <a href="{{ route('teacher.attendance.index') }}"
        class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('teacher.attendance.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg font-medium transition-colors">
        <i data-lucide="clipboard-check" class="w-5 h-5"></i>
        <span>Présences</span>
        <span class="ml-auto bg-emerald-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">EN DIRECT</span>
    </a>
    <a href="{{ route('teacher.students.index') }}"
        class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('teacher.students.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg transition-colors">
        <i data-lucide="users" class="w-5 h-5"></i>
        <span>Mes Étudiants</span>
    </a>
    <a href="{{ route('teacher.timetable.create-request') }}"
        class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('teacher.timetable.create-request') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg transition-colors">
        <i data-lucide="plus-circle" class="w-5 h-5"></i>
        <span>Nouvelle demande</span>
    </a>
</div>
