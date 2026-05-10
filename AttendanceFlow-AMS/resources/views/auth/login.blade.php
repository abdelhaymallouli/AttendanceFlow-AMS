<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - solicode AMS</title>
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@800&display=swap" rel="stylesheet">
</head>

<body class="h-full">

    <div class="min-h-screen flex items-center justify-center p-4 bg-gray-50 hero-bg">
        <div class="w-full max-w-md animate-fade-in-up">

            <!-- Logo & Header -->
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-600 rounded-[2rem] mb-6 shadow-xl shadow-blue-500/20">
                    <i data-lucide="school" class="text-white w-10 h-10"></i>
                </div>
                <h1 class="text-4xl font-black text-gray-800 mb-2 tracking-tight">solicode <span class="text-blue-600 font-extrabold uppercase">AMS</span></h1>
                <p class="text-gray-500 font-bold uppercase tracking-[0.2em] text-[10px] opacity-70">Attendance Management System</p>
            </div>

            <!-- Login Card -->
            <div class="bg-white border border-gray-200 rounded-[2.5rem] shadow-2xl shadow-slate-200/50 p-8 lg:p-12 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-600 to-indigo-600"></div>
                
                <h2 class="text-2xl font-black text-gray-800 mb-8 tracking-tight">Access Portal</h2>

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    @if ($errors->any())
                    <div class="p-4 mb-4 text-sm text-red-800 rounded-2xl bg-red-50 font-bold border border-red-100">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div>
                        <label for="email" class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1 opacity-70">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                <i data-lucide="mail" class="w-5 h-5 text-gray-400"></i>
                            </div>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                class="w-full pl-14 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all font-bold text-gray-800"
                                placeholder="name@solicode.com" required autofocus>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2 ml-1">
                            <label for="password" class="block text-[11px] font-black text-gray-400 uppercase tracking-widest opacity-70">Password</label>
                            <a href="#" class="text-[11px] font-black text-blue-600 hover:text-blue-700 uppercase tracking-widest">Forgot?</a>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                <i data-lucide="lock" class="w-5 h-5 text-gray-400"></i>
                            </div>
                            <input type="password" name="password" id="password"
                                class="w-full pl-14 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none transition-all font-bold text-gray-800"
                                placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="flex items-center py-2">
                        <label class="flex items-center cursor-pointer group">
                            <input type="checkbox" name="remember"
                                class="w-5 h-5 text-blue-600 border-gray-100 rounded-lg focus:ring-blue-500 transition-all cursor-pointer">
                            <span class="ml-3 text-sm font-bold text-gray-400 group-hover:text-gray-600 transition-colors">Keep me signed in</span>
                        </label>
                    </div>

                    <button type="submit"
                        class="w-full bg-slate-900 hover:bg-blue-600 text-white font-black py-5 px-6 rounded-2xl transition-all duration-300 flex items-center justify-center shadow-xl shadow-slate-200 hover:shadow-blue-500/20 hover:-translate-y-1 active:scale-95">
                        <span>Sign In</span>
                        <i data-lucide="arrow-right" class="w-5 h-5 ml-3"></i>
                    </button>
                </form>

                <!-- Quick Help (Demo credentials) -->
                <div class="mt-12 p-6 bg-gray-50/50 rounded-[2rem] border border-dashed border-gray-200">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <i data-lucide="help-circle" class="w-3 h-3"></i> Try Demo Access
                    </p>
                    <div class="grid grid-cols-1 gap-2 text-[10px] font-black text-gray-500">
                        <p class="flex justify-between items-center"><span class="opacity-50 uppercase tracking-tighter">Admin:</span> admin@ams.com</p>
                        <p class="flex justify-between items-center"><span class="opacity-50 uppercase tracking-tighter">Teacher:</span> imane@ams.com</p>
                        <p class="flex justify-between items-center"><span class="opacity-50 uppercase tracking-tighter">Student:</span> student1@ams.com</p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-12 pb-8">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] opacity-40">&copy; 2026 Solicode Attendance Control</p>
            </div>
        </div>
    </div>

</body>
</html>
