<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SoliCode AMS | Revolutionary Attendance Management</title>
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-slate-50 text-slate-800 antialiased transition-colors duration-300" x-data="{ isScrolled: false }" @scroll.window="isScrolled = (window.pageYOffset > 20)">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 transition-all duration-300" :class="isScrolled ? 'glass-panel py-3 shadow-md' : 'bg-transparent py-5'">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-2 cursor-pointer">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-500 to-accent-600 flex items-center justify-center text-white shadow-lg shadow-brand-500/30">
                        <i class="bi bi-fingerprint text-xl"></i>
                    </div>
                    <span class="font-bold text-xl tracking-tight leading-none">solicode<span class="text-brand-500">AMS</span></span>
                </div>
                
                <div class="hidden md:flex space-x-8">
                    <a href="#features" class="font-bold text-[11px] uppercase tracking-widest text-slate-600 hover:text-brand-500 transition-colors">Features</a>
                    <a href="#how" class="font-bold text-[11px] uppercase tracking-widest text-slate-600 hover:text-brand-500 transition-colors">How it works</a>
                    <a href="#roles" class="font-bold text-[11px] uppercase tracking-widest text-slate-600 hover:text-brand-500 transition-colors">Roles</a>
                </div>

                <div class="flex items-center gap-4">
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-3 font-black uppercase tracking-widest text-xs text-white transition-all bg-slate-900 rounded-2xl hover:bg-brand-600 hover:shadow-xl hover:-translate-y-0.5 shadow-lg shadow-slate-200">
                        <i class="bi bi-box-arrow-in-right mr-2"></i> Access Portal
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <main class="relative hero-bg overflow-hidden min-h-screen flex items-center pt-20">
        <!-- Floating Animated Blobs -->
        <div class="absolute top-0 -left-4 w-72 h-72 bg-brand-400 rounded-full mix-blend-multiply filter blur-2xl opacity-20 animate-blob"></div>
        <div class="absolute top-0 -right-4 w-72 h-72 bg-accent-500 rounded-full mix-blend-multiply filter blur-2xl opacity-20 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-400 rounded-full mix-blend-multiply filter blur-2xl opacity-20 animate-blob animation-delay-4000"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-8">
                <!-- Text Content -->
                <div class="w-full lg:w-1/2 text-center lg:text-left animate-fade-in-up">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full glass-panel text-[10px] font-black uppercase tracking-widest text-brand-600 mb-8 border border-brand-100 uppercase">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-500"></span>
                        </span>
                        SoliCode AMS 2026 Ready
                    </div>
                    
                    <h1 class="text-6xl lg:text-8xl font-black tracking-tighter mb-8 leading-[0.9]">
                        Attendance <br class="hidden lg:block"/>
                        <span class="gradient-text">Redefined.</span>
                    </h1>
                    
                    <p class="text-xl lg:text-2xl text-slate-600 mb-12 max-w-2xl mx-auto lg:mx-0 font-bold leading-relaxed opacity-80">
                        A real-time, dynamic ecosystem seamlessly connecting Teachers, Students, and Admin. Zero physical sheets. 100% digital clarity.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-5 justify-center lg:justify-start">
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-10 py-5 text-sm font-black uppercase tracking-widest text-white transition-all duration-300 bg-gradient-to-r from-brand-500 to-brand-600 border border-transparent rounded-[2rem] hover:shadow-2xl hover:shadow-brand-500/30 hover:-translate-y-1">
                            Access Portal <i class="bi bi-arrow-right ml-3 text-lg"></i>
                        </a>
                        <a href="#how" class="inline-flex items-center justify-center px-10 py-5 text-sm font-black uppercase tracking-widest text-slate-700 transition-all duration-300 bg-white border border-slate-200 hover:bg-slate-50 rounded-[2rem] hover:shadow-md hover:-translate-y-1">
                            <i class="bi bi-play-circle mr-2 text-lg"></i> See it in action
                        </a>
                    </div>
                </div>

                <!-- 3D/Floating Dashboard Preview -->
                <div class="w-full lg:w-1/2 mt-12 lg:mt-0 relative" style="animation: float 6s ease-in-out infinite;">
                    <div class="relative rounded-[3rem] bg-white/50 p-2 backdrop-blur-xl border border-white/20 shadow-2xl overflow-hidden min-h-[450px] flex items-center justify-center group">
                        <div class="absolute inset-0 bg-gradient-to-tr from-brand-500/10 to-accent-500/10 rounded-[3rem]"></div>
                        <i class="bi bi-fingerprint text-[180px] text-brand-600/10 group-hover:scale-110 transition-transform duration-1000"></i>
                        
                        <!-- Floating Data Cards -->
                        <div class="absolute -left-6 top-10 glass-panel rounded-2xl p-5 shadow-2xl flex items-center gap-4 animate-fade-in-up" style="animation-delay: 0.3s;">
                            <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center"><i class="bi bi-check2-all text-2xl"></i></div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Session Data</p>
                                <p class="text-sm font-black text-slate-800">Pointage Validated</p>
                            </div>
                        </div>
                        
                        <div class="absolute -right-6 bottom-16 glass-panel rounded-2xl p-5 shadow-2xl flex items-center gap-4 animate-fade-in-up" style="animation-delay: 0.6s;">
                            <div class="w-14 h-14 rounded-2xl bg-brand-100 text-brand-600 flex items-center justify-center"><i class="bi bi-file-earmark-check text-2xl"></i></div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Justification</p>
                                <p class="text-sm font-black text-slate-800">Approved Proof</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Features Section -->
    <section id="features" class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-20 animate-fade-in-up">
                <h2 class="text-brand-600 font-black uppercase tracking-[0.2em] text-[10px] mb-4">Core Engine</h2>
                <h3 class="text-4xl md:text-5xl font-black mb-6 tracking-tight">Eliminate friction. <br/> Gain Insight.</h3>
                <p class="text-lg text-slate-500 font-bold opacity-80">Our system is heavily optimized for fast rendering, mobile interaction, and immediate synchronization with the central administration.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-10">
                <!-- Feature 1 -->
                <div class="group p-10 rounded-[2.5rem] bg-slate-50 border border-slate-100 hover:shadow-2xl hover:shadow-slate-200 hover:bg-white transition-all duration-500 relative overflow-hidden active:scale-95">
                    <div class="w-16 h-16 rounded-2xl bg-brand-100 text-brand-600 flex items-center justify-center mb-8 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-500 shadow-lg shadow-brand-500/10">
                        <i class="bi bi-phone-vibrate text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-black mb-4 tracking-tight">Mobile-First "Flash" Entry</h4>
                    <p class="text-slate-500 font-bold leading-relaxed opacity-80">Teachers can take attendance on their smartphones in under 30 seconds. Tap, submit, done.</p>
                </div>
                <!-- Feature 2 -->
                <div class="group p-10 rounded-[2.5rem] bg-slate-50 border border-slate-100 hover:shadow-2xl hover:shadow-slate-200 hover:bg-white transition-all duration-500 relative overflow-hidden active:scale-95">
                    <div class="w-16 h-16 rounded-2xl bg-accent-100 text-accent-600 flex items-center justify-center mb-8 group-hover:scale-110 group-hover:-rotate-3 transition-transform duration-500 shadow-lg shadow-accent-500/10">
                        <i class="bi bi-calendar-event text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-black mb-4 tracking-tight">Dynamic Sessions</h4>
                    <p class="text-slate-500 font-bold leading-relaxed opacity-80">Configure specific time blocks (09:00-11:00, 11:00-14:00) per group and per module.</p>
                </div>
                <!-- Feature 3 -->
                <div class="group p-10 rounded-[2.5rem] bg-slate-50 border border-slate-100 hover:shadow-2xl hover:shadow-slate-200 hover:bg-white transition-all duration-500 relative overflow-hidden active:scale-95">
                    <div class="w-16 h-16 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center mb-8 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-500 shadow-lg shadow-emerald-500/10">
                        <i class="bi bi-file-earmark-medical text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-black mb-4 tracking-tight">Digital Justifications Hub</h4>
                    <p class="text-slate-500 font-bold leading-relaxed opacity-80">Students upload medical notes dynamically. Admins approve them in one click.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-100 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-8">
            <div class="flex items-center gap-3">
                <i class="bi bi-fingerprint text-brand-500 text-3xl"></i>
                <span class="font-black text-2xl tracking-tighter text-slate-800">solicode<span class="text-brand-500">AMS</span></span>
            </div>
            <p class="text-slate-400 font-black text-[10px] uppercase tracking-widest opacity-60 transition-opacity hover:opacity-100">© 2026 SoliCode Project. Crafted with precision for Sprint 1.</p>
            <div class="flex space-x-6">
                <a href="#" class="text-slate-300 hover:text-slate-600 transition-all hover:scale-110"><i class="bi bi-github text-xl"></i></a>
                <a href="#" class="text-slate-300 hover:text-slate-600 transition-all hover:scale-110"><i class="bi bi-twitter-x text-xl"></i></a>
            </div>
        </div>
    </footer>

</body>
</html>
