<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Tableau de bord') - solicode AMS</title>
    
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
                        <button type="submit" class="text-gray-400 hover:text-gray-600 transition-colors" title="Déconnexion">
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
                        <p class="text-sm text-gray-500 hidden sm:block truncate opacity-80">Bon retour, {{ Auth::user()->name }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4 shrink-0">
                    @yield('header_actions')
                    
                    <div class="hidden md:flex items-center space-x-2 text-sm font-bold text-gray-600">
                        <i data-lucide="calendar" class="w-4 h-4 text-blue-600"></i>
                        <span>{{ now()->format('l, d F Y') }}</span>
                    </div>
                    <x-notification-dropdown />
                    <button class="w-9 h-9 bg-blue-100 rounded-full flex items-center justify-center hover:bg-blue-200 transition-colors">
                        <span class="text-sm font-bold text-blue-600">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </button>
                </div>
            </header>

            <!-- Page Content (Mockup Style) -->
            <main class="flex-1 overflow-y-auto p-4 lg:p-6 bg-gray-50">
                <div class="max-w-7xl mx-auto">
                    @if(session('success'))
                        <div x-data="{ show: true }" x-show="show" class="mb-6 bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 flex items-center justify-between shadow-sm">
                            <div class="flex items-center">
                                <div class="bg-green-100 p-2 rounded-lg mr-3">
                                    <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
                                </div>
                                <p class="text-sm font-medium">{{ session('success') }}</p>
                            </div>
                            <button @click="show = false" class="text-green-600 hover:text-green-800 transition-colors p-1 hover:bg-green-100 rounded-md">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div x-data="{ show: true }" x-show="show" class="mb-6 bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 flex items-center justify-between shadow-sm">
                            <div class="flex items-center">
                                <div class="bg-red-100 p-2 rounded-lg mr-3">
                                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-600"></i>
                                </div>
                                <p class="text-sm font-medium">{{ session('error') }}</p>
                            </div>
                            <button @click="show = false" class="text-red-600 hover:text-red-800 transition-colors p-1 hover:bg-red-100 rounded-md">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')

    <script>
    function notificationCenter() {
        return {
            open: false,
            items: [],
            unreadCount: 0,
            loading: false,
            seenIds: [],
            cfg: null,
            _pollTimer: null,

            init() {
                var el = document.getElementById('notification-config');
                this.cfg = el ? JSON.parse(el.textContent) : null;
                this.seenIds = this.items.map(function (n) { return n.id; });
                var self = this;
                this.refresh().then(function () {
                    self.seenIds = self.items.map(function (n) { return n.id; });
                });
                this._pollTimer = setInterval(function () { self.refresh(true); }, 20000);
            },

            toggleDropdown() {
                this.open = !this.open;
                if (this.open) this.refresh();
            },

            refresh(checkOnly) {
                var self = this;
                if (! this.cfg) return Promise.resolve();
                if (checkOnly) this.loading = false;
                else this.loading = true;
                return fetch(this.cfg.indexUrl, {
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': this.cfg.csrf },
                    credentials: 'same-origin',
                })
                .then(function (r) { return r.ok ? r.json() : null; })
                .then(function (data) {
                    self.loading = false;
                    if (! data) return;
                    if (checkOnly) {
                        var newOnes = (data.items || []).filter(function (n) {
                            return n.is_read === false && self.seenIds.indexOf(n.id) === -1;
                        });
                        if (newOnes.length > 0) {
                            var newest = newOnes[0];
                            window.dispatchEvent(new CustomEvent('new-notification', { detail: newest }));
                        }
                    }
                    self.items = data.items || [];
                    self.unreadCount = data.unread_count || 0;
                    self.seenIds = self.items.map(function (n) { return n.id; });
                })
                .catch(function () { self.loading = false; });
            },

            markAllAsRead() {
                if (! this.cfg) return;
                var self = this;
                fetch(this.cfg.markAllUrl, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': this.cfg.csrf },
                    credentials: 'same-origin',
                })
                .then(function (r) { return r.json(); })
                .then(function () { return self.refresh(); });
            },

            clearAll() {
                if (! this.cfg) return;
                if (! confirm('Effacer toutes les notifications ?')) return;
                var self = this;
                fetch(this.cfg.clearAllUrl, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': this.cfg.csrf },
                    credentials: 'same-origin',
                })
                .then(function (r) { return r.json(); })
                .then(function () { return self.refresh(); });
            },

            handleClick(n) {
                if (! this.cfg) return;
                if (! n.is_read) {
                    var self = this;
                    fetch(this.cfg.indexUrl + '/' + n.id + '/read', {
                        method: 'POST',
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': this.cfg.csrf },
                        credentials: 'same-origin',
                    })
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        n.is_read = true;
                        self.unreadCount = Math.max(0, self.unreadCount - 1);
                        if (data && data.redirect_url) {
                            window.location.href = data.redirect_url;
                        } else {
                            self.refresh();
                        }
                    })
                    .catch(function () {});
                } else if (n.redirect_url) {
                    window.location.href = n.redirect_url;
                }
            },

            iconFor(n) {
                if (n.category === 'attendance.absent') return 'user-x';
                if (n.category === 'attendance.present') return 'user-check';
                if (n.category === 'attendance.teacher_absent') return 'user-x';
                if (n.category === 'justification.approved') return 'check-circle-2';
                if (n.category === 'justification.rejected') return 'x-circle';
                if (n.category === 'justification.submitted') return 'file-text';
                if (n.type === 'success') return 'check-circle-2';
                if (n.type === 'danger') return 'alert-circle';
                if (n.type === 'warning') return 'alert-triangle';
                return 'info';
            },

            iconBg(type) {
                if (type === 'success') return 'bg-emerald-50 border border-emerald-100';
                if (type === 'danger') return 'bg-red-50 border border-red-100';
                if (type === 'warning') return 'bg-amber-50 border border-amber-100';
                return 'bg-blue-50 border border-blue-100';
            },

            iconColor(type) {
                if (type === 'success') return 'text-emerald-600';
                if (type === 'danger') return 'text-red-600';
                if (type === 'warning') return 'text-amber-600';
                return 'text-blue-600';
            },
        };
    }

    function notificationPopup() {
        return {
            visible: false,
            current: null,
            _autoCloseTimer: null,
            _audioCtx: null,
            _soundEnabled: (function () {
                try { return localStorage.getItem('notif_sound') !== 'off'; } catch (e) { return true; }
            })(),

            _playSound() {
                if (! this._soundEnabled) return;
                try {
                    if (! this._audioCtx) {
                        var Ctx = window.AudioContext || window.webkitAudioContext;
                        if (! Ctx) return;
                        this._audioCtx = new Ctx();
                    }
                    var ctx = this._audioCtx;
                    var now = ctx.currentTime;
                    var o1 = ctx.createOscillator();
                    var o2 = ctx.createOscillator();
                    var g = ctx.createGain();
                    o1.type = 'sine';
                    o2.type = 'sine';
                    o1.frequency.setValueAtTime(880, now);
                    o2.frequency.setValueAtTime(1320, now);
                    g.gain.setValueAtTime(0, now);
                    g.gain.linearRampToValueAtTime(0.12, now + 0.01);
                    g.gain.exponentialRampToValueAtTime(0.001, now + 0.5);
                    o1.connect(g); o2.connect(g); g.connect(ctx.destination);
                    o1.start(now); o2.start(now);
                    o1.stop(now + 0.5); o2.stop(now + 0.5);
                } catch (e) { /* silent */ }
            },

            popupInit() { /* event-driven, nothing to do on init */ },

            showPopup(n) {
                if (! n) return;
                this.current = n;
                this.visible = true;
                this._playSound();
                var self = this;
                clearTimeout(this._autoCloseTimer);
                this._autoCloseTimer = setTimeout(function () { self.dismiss(); }, 10000);
                if (window.lucide && window.lucide.createIcons) {
                    setTimeout(function () { window.lucide.createIcons(); }, 0);
                }
            },

            toggleSound() {
                this._soundEnabled = ! this._soundEnabled;
                try { localStorage.setItem('notif_sound', this._soundEnabled ? 'on' : 'off'); } catch (e) {}
            },

            dismiss() {
                this.visible = false;
                clearTimeout(this._autoCloseTimer);
                this.current = null;
            },

            openNotification() {
                if (! this.current) return;
                var url = this.current.redirect_url;
                this.dismiss();
                if (url) window.location.href = url;
            },

            popupIcon() {
                if (! this.current) return 'info';
                if (this.current.category === 'attendance.absent') return 'user-x';
                if (this.current.category === 'attendance.present') return 'user-check';
                if (this.current.category === 'attendance.teacher_absent') return 'user-x';
                if (this.current.category === 'justification.approved') return 'check-circle-2';
                if (this.current.category === 'justification.rejected') return 'x-circle';
                if (this.current.category === 'justification.submitted') return 'file-text';
                if (this.current.type === 'success') return 'check-circle-2';
                if (this.current.type === 'danger') return 'alert-circle';
                if (this.current.type === 'warning') return 'alert-triangle';
                return 'info';
            },

            popupIconBg() {
                if (! this.current) return 'bg-blue-50 border border-blue-100';
                if (this.current.type === 'success') return 'bg-emerald-50 border border-emerald-100';
                if (this.current.type === 'danger') return 'bg-red-50 border border-red-100';
                if (this.current.type === 'warning') return 'bg-amber-50 border border-amber-100';
                return 'bg-blue-50 border border-blue-100';
            },

            popupIconColor() {
                if (! this.current) return 'text-blue-600';
                if (this.current.type === 'success') return 'text-emerald-600';
                if (this.current.type === 'danger') return 'text-red-600';
                if (this.current.type === 'warning') return 'text-amber-600';
                return 'text-blue-600';
            },

            popupBorder() {
                if (! this.current) return 'border-blue-200';
                if (this.current.type === 'success') return 'border-emerald-200';
                if (this.current.type === 'danger') return 'border-red-200';
                if (this.current.type === 'warning') return 'border-amber-200';
                return 'border-blue-200';
            },
        };
    }
    </script>
</body>

</html>
