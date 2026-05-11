<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Solicode AMS - Portails</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        * { -webkit-tap-highlight-color: transparent; }
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col items-center justify-center p-6 bg-gradient-to-br from-blue-50 to-indigo-50">

    <div class="mb-10 text-center">
        <div class="w-20 h-20 bg-blue-600 rounded-3xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-blue-200">
            <i data-lucide="school" class="w-10 h-10 text-white"></i>
        </div>
        <h1 class="text-2xl font-bold text-gray-800">Solicode AMS</h1>
        <p class="text-gray-500 text-sm mt-1">Plateforme de Pointage Mobile</p>
    </div>

    <div class="w-full max-w-sm space-y-4">
        
        <!-- Admin Portal Choice -->
        <a href="{{ route('mobile.admin.dashboard') }}" class="group block w-full bg-white rounded-3xl p-5 border border-gray-100 shadow-sm hover:border-blue-300 hover:shadow-md transition-all active:scale-[0.98]">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center flex-shrink-0 text-white shadow-md shadow-blue-200 group-hover:scale-105 transition-transform">
                    <i data-lucide="shield" class="w-6 h-6"></i>
                </div>
                <div class="flex-1">
                    <h2 class="text-lg font-bold text-gray-800">Portail Admin</h2>
                    <p class="text-xs text-gray-500">Supervision et Stats</p>
                </div>
                <i data-lucide="chevron-right" class="w-5 h-5 text-gray-300"></i>
            </div>
        </a>

        <!-- Teacher Portal Choice -->
        <a href="{{ route('mobile.sessions') }}" class="group block w-full bg-white rounded-3xl p-5 border border-gray-100 shadow-sm hover:border-green-300 hover:shadow-md transition-all active:scale-[0.98]">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center flex-shrink-0 text-white shadow-md shadow-green-200 group-hover:scale-105 transition-transform">
                    <i data-lucide="users" class="w-6 h-6"></i>
                </div>
                <div class="flex-1">
                    <h2 class="text-lg font-bold text-gray-800">Portail Formateur</h2>
                    <p class="text-xs text-gray-500">Pointage et Séances</p>
                </div>
                <i data-lucide="chevron-right" class="w-5 h-5 text-gray-300"></i>
            </div>
        </a>

        <!-- Student Portal Choice -->
        <a href="{{ route('mobile.student.dashboard') }}" class="group block w-full bg-white rounded-3xl p-5 border border-gray-100 shadow-sm hover:border-indigo-300 hover:shadow-md transition-all active:scale-[0.98]">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-violet-600 rounded-2xl flex items-center justify-center flex-shrink-0 text-white shadow-md shadow-indigo-200 group-hover:scale-105 transition-transform">
                    <i data-lucide="graduation-cap" class="w-6 h-6"></i>
                </div>
                <div class="flex-1">
                    <h2 class="text-lg font-bold text-gray-800">Portail Stagiaire</h2>
                    <p class="text-xs text-gray-500">Absences et Justifications</p>
                </div>
                <i data-lucide="chevron-right" class="w-5 h-5 text-gray-300"></i>
            </div>
        </a>

    </div>

    <div class="mt-auto pt-10 pb-4">
        <p class="text-[10px] uppercase font-bold text-gray-400 tracking-widest">Version Démonstration</p>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
