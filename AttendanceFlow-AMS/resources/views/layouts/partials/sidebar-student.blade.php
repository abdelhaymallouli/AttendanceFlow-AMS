<div class="space-y-1">
    <a href="{{ route('student.dashboard') }}"
        class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('student.dashboard') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg font-medium transition-colors">
        <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
        <span>Mon espace</span>
    </a>
    <a href="{{ route('student.timetable.index') }}"
        class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('student.timetable.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg font-medium transition-colors">
        <i data-lucide="calendar-range" class="w-5 h-5"></i>
        <span>Emploi du temps</span>
    </a>
    <a href="{{ route('student.justifications.index') }}"
        class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('student.justifications.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg transition-colors">
        <i data-lucide="upload-cloud" class="w-5 h-5"></i>
        <span>Justificatifs</span>
    </a>
    <a href="{{ route('student.qr.scan') }}"
        class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('student.qr.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg transition-colors">
        <i data-lucide="qr-code" class="w-5 h-5"></i>
        <span>Scanner un QR</span>
    </a>
</div>
