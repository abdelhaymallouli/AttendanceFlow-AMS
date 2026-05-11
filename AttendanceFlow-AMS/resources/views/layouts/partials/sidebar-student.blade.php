<div class="space-y-1">
    <a href="{{ route('student.dashboard') }}"
        class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('student.dashboard') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg font-medium transition-colors">
        <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
        <span>My Portfolio</span>
    </a>
    <a href="{{ route('student.justifications.index') }}"
        class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('student.justifications.*') ? 'text-blue-600 bg-blue-50' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg transition-colors">
        <i data-lucide="upload-cloud" class="w-5 h-5"></i>
        <span>Upload Proof</span>
        <span class="ml-auto bg-amber-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">!</span>
    </a>
    <a href="#"
        class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
        <i data-lucide="bar-chart-2" class="w-5 h-5"></i>
        <span>My Attendance</span>
    </a>
</div>
