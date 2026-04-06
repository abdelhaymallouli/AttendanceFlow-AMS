<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Dashboard') - solicode AMS</title>
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Fonts from Mockup -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@600;800&display=swap" rel="stylesheet">
    
    @yield('styles')
</head>

<body class="h-full overflow-hidden" x-data="{ sidebarOpen: false }">

    <!-- Mobile Header (Mockup Style) -->
    <header class="lg:hidden bg-white border-b border-gray-200 px-4 py-3 flex items-center justify-between sticky top-0 z-40">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                <i data-lucide="school" class="w-4 h-4 text-white"></i>
            </div>
            <span class="font-bold text-gray-800">solicode AMS</span>
        </div>
        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-600">
            <i data-lucide="menu" class="w-6 h-6"></i>
        </button>
    </header>

    <!-- Mobile Sidebar (Mockup Style) -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-x-full" 
         x-transition:enter-end="opacity-100 translate-x-0"
         x-transition:leave="transition ease-in duration-150" 
         x-transition:leave-start="opacity-100 translate-x-0"
         x-transition:leave-end="opacity-0 -translate-x-full" 
         class="fixed inset-0 z-50 lg:hidden"
         style="display: none;" x-cloak>
        <div class="absolute inset-0 bg-black/50" @click="sidebarOpen = false"></div>
        <div class="absolute left-0 top-0 h-full w-64 bg-white border-r border-gray-200">
            <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                        <i data-lucide="school" class="w-5 h-5 text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-gray-800 leading-tight">solicode AMS</h1>
                        <p class="text-xs text-gray-500 capitalize">{{ Auth::user()->getRoleNames()->first() }}</p>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="text-gray-500">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <nav class="p-4 space-y-1">
                @include('layouts.partials.sidebar-' . (Auth::user()->getRoleNames()->first() ?? 'student'))
            </nav>
        </div>
    </div>

    <div class="flex h-screen overflow-hidden">

        <!-- Desktop Sidebar (Mockup Style) -->
        <aside class="hidden lg:flex flex-col w-64 bg-white border-r border-gray-200 h-full shrink-0">
            <!-- Logo -->
            <div class="flex items-center h-16 px-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                        <i data-lucide="school" class="w-5 h-5 text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-gray-800 leading-tight">solicode AMS</h1>
                        <p class="text-xs text-gray-500 capitalize">{{ Auth::user()->getRoleNames()->first() }}</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                @include('layouts.partials.sidebar-' . (Auth::user()->getRoleNames()->first() ?? 'student'))
            </nav>

            <!-- User Profile -->
            <div class="border-t border-gray-200 p-4 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center font-bold text-blue-600">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-gray-600 transition-colors" title="Logout">
                            <i data-lucide="log-out" class="w-5 h-5"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content (Mockup Style) -->
        <div class="flex-1 flex flex-col overflow-hidden min-w-0">

            <!-- Top Bar -->
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 lg:px-6 shrink-0">
                <div class="flex items-center space-x-4 min-w-0">
                    <button @click="sidebarOpen = true" class="lg:hidden text-gray-500">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                    <div class="min-w-0">
                        <h2 class="text-xl font-bold text-gray-800 truncate">@yield('page_title')</h2>
                        <p class="text-sm text-gray-500 hidden sm:block truncate opacity-80">Welcome back, {{ Auth::user()->name }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4 shrink-0">
                    @yield('header_actions')
                    
                    <div class="hidden md:flex items-center space-x-2 text-sm font-bold text-gray-600">
                        <i data-lucide="calendar" class="w-4 h-4 text-blue-600"></i>
                        <span>{{ now()->format('l, d F Y') }}</span>
                    </div>
                    <button class="relative p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>
                </div>
            </header>

            <!-- Page Content (Mockup Style) -->
            <main class="flex-1 overflow-y-auto p-4 lg:p-6 bg-gray-50">
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @yield('scripts')
</body>

</html>
