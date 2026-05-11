<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Solicode AMS Mobile')</title>
    
    <!-- Design System (Strict Maquete-Mobile Alignment) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        * { -webkit-tap-highlight-color: transparent; }
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        .content-area { padding-bottom: 100px; }
        
        /* Attendance Status Colors */
        .student-row.present { background-color: #f0fdf4; border-color: #86efac; }
        .student-row.absent { background-color: #fff1f2; border-color: #fca5a5; }
        .student-row.late { background-color: #fffbeb; border-color: #fcd34d; }
        
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen text-gray-900 overflow-x-hidden">

    <!-- Top Bar -->
    <div class="bg-white border-b border-gray-100 sticky top-0 z-40">
        <div class="flex items-center justify-between px-4 py-3">
            <div>
                <h1 class="text-sm font-bold text-gray-800">@yield('header_title', 'Teacher Portal')</h1>
                <p class="text-xs text-gray-500">Solicode AMS</p>
            </div>
            <div class="flex items-center gap-2">
                <button class="w-9 h-9 flex items-center justify-center text-gray-500 rounded-xl hover:bg-gray-100 relative">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-amber-500 rounded-full"></span>
                </button>
                <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center">
                    <span class="text-xs font-bold text-blue-600">MK</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="content-area">
        @yield('content')
    </main>

    <!-- Bottom Navigation -->
    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 z-40" style="padding-bottom: env(safe-area-inset-bottom, 8px)">
        <div class="flex items-center justify-around py-2">
            <a href="{{ route('mobile.sessions') }}" 
               class="flex flex-col items-center gap-1 px-4 py-2 {{ request()->routeIs('mobile.sessions') ? 'text-blue-600' : 'text-gray-400' }}">
                <i data-lucide="home" class="w-5 h-5"></i>
                <span class="text-xs font-medium">Accueil</span>
            </a>
            
            <div class="px-4 py-2">
                <a href="{{ route('mobile.sessions') }}" 
                   class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center -mt-8 shadow-lg shadow-blue-200 text-white transition-transform active:scale-90">
                    <i data-lucide="plus" class="w-6 h-6"></i>
                </a>
            </div>

            <a href="#" class="flex flex-col items-center gap-1 px-4 py-2 text-gray-400">
                <i data-lucide="file-text" class="w-5 h-5"></i>
                <span class="text-xs font-medium">Alertes</span>
            </a>
        </div>
    </nav>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>
</html>