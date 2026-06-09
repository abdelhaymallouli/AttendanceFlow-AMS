<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AttendanceFlow')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        * { -webkit-tap-highlight-color: transparent; }
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        .content-area { padding-bottom: 100px; }
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen text-gray-900 overflow-x-hidden">

    <!-- Top Bar -->
    <div class="bg-white border-b border-gray-100 sticky top-0 z-40">
        <div class="flex items-center justify-between px-4 py-3">
            <div>
                <h1 class="text-sm font-bold text-gray-800">@yield('header_title', 'AttendanceFlow')</h1>
                <p class="text-[10px] text-gray-400 font-medium">AttendanceFlow AMS</p>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center">
                    <span class="text-xs font-bold text-blue-600">{{ substr(session('mobile_user.name', 'U'), 0, 1) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="content-area">
        @yield('content')
    </main>

    <!-- Bottom Navigation — Student -->
    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 z-40" style="padding-bottom: env(safe-area-inset-bottom, 8px)">
        <div class="flex items-center justify-around py-2 px-2">
            <a href="{{ route('mobile.home') }}"
               class="flex flex-col items-center gap-0.5 px-3 py-2 {{ request()->routeIs('mobile.home') ? 'text-blue-600' : 'text-gray-400' }}">
                <i data-lucide="home" class="w-5 h-5"></i>
                <span class="text-[10px] font-bold">Accueil</span>
            </a>

            <a href="{{ route('mobile.absences') }}"
               class="flex flex-col items-center gap-0.5 px-3 py-2 {{ request()->routeIs('mobile.absences') ? 'text-blue-600' : 'text-gray-400' }}">
                <i data-lucide="calendar-x" class="w-5 h-5"></i>
                <span class="text-[10px] font-bold">Absences</span>
            </a>

            <a href="{{ route('mobile.scan') }}"
               class="flex flex-col items-center gap-0.5 px-3 py-2 -mt-4">
                <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-200 active:scale-90 transition-transform {{ request()->routeIs('mobile.scan') ? 'ring-2 ring-blue-300' : '' }}">
                    <i data-lucide="qr-code" class="w-6 h-6 text-white"></i>
                </div>
                <span class="text-[10px] font-bold text-gray-400 {{ request()->routeIs('mobile.scan') ? 'text-blue-600' : '' }}">Scanner</span>
            </a>

            <a href="{{ route('mobile.justifications') }}"
               class="flex flex-col items-center gap-0.5 px-3 py-2 {{ request()->routeIs('mobile.justifications*') ? 'text-blue-600' : 'text-gray-400' }}">
                <i data-lucide="file-text" class="w-5 h-5"></i>
                <span class="text-[10px] font-bold">Justif.</span>
            </a>

            <a href="{{ route('mobile.sessions') }}"
               class="flex flex-col items-center gap-0.5 px-3 py-2 {{ request()->routeIs('mobile.sessions*') ? 'text-blue-600' : 'text-gray-400' }}">
                <i data-lucide="calendar" class="w-5 h-5"></i>
                <span class="text-[10px] font-bold">Séances</span>
            </a>
        </div>
    </nav>

    <script>
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>
</html>
