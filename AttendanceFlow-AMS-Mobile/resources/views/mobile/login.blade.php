<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Connexion - AttendanceFlow AMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        * { -webkit-tap-highlight-color: transparent; }
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col items-center justify-center p-6 bg-gradient-to-br from-indigo-50 to-blue-50">

    <div class="w-full max-w-sm bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-xl shadow-indigo-100/40 relative overflow-hidden">
        
        <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-600/5 rounded-full -mr-16 -mt-16"></div>
        
        <div class="text-center mb-8 relative z-10">
            <div class="w-16 h-16 bg-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-indigo-200">
                <i data-lucide="shield-check" class="w-8 h-8 text-white"></i>
            </div>
            <h1 class="text-2xl font-black text-gray-800 tracking-tight">AttendanceFlow</h1>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mt-1">Espace Étudiant</p>
        </div>

        @if ($errors->any())
            <div class="p-4 mb-5 text-xs text-red-800 rounded-2xl bg-red-50 font-bold border border-red-100">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('mobile.login.post') }}" method="POST" class="space-y-5 relative z-10">
            @csrf
            
            <div class="space-y-1">
                <label for="email" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Adresse Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="mail" class="w-4 h-4 text-gray-400"></i>
                    </div>
                    <input type="email" name="email" id="email" 
                           class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition-all text-sm font-semibold text-gray-800"
                           placeholder="nom.prenom@solicode.co" required>
                </div>
            </div>

            <div class="space-y-1">
                <label for="password" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Mot de passe</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i data-lucide="lock" class="w-4 h-4 text-gray-400"></i>
                    </div>
                    <input type="password" name="password" id="password" 
                           class="w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition-all text-sm font-semibold text-gray-800"
                           placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" 
                    class="w-full bg-slate-900 hover:bg-indigo-600 text-white font-black py-4 px-6 rounded-2xl transition-all duration-300 flex items-center justify-center gap-3 shadow-lg shadow-slate-200 hover:shadow-indigo-500/20 active:scale-95 text-sm uppercase tracking-wider">
                <span>SE CONNECTER</span>
                <i data-lucide="log-in" class="w-4 h-4"></i>
            </button>
        </form>
    </div>

    <div class="mt-8 text-center">
        <p class="text-[10px] uppercase font-bold text-gray-400 tracking-widest">AttendanceFlow AMS Mobile</p>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
