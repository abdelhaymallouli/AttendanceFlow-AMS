@php
    $config = [
        'indexUrl' => url('/web/notifications'),
        'unreadUrl' => url('/web/notifications/unread'),
        'countUrl' => url('/web/notifications/unread-count'),
        'markAllUrl' => url('/web/notifications/mark-all-as-read'),
        'clearAllUrl' => url('/web/notifications/clear-all'),
        'csrf' => csrf_token(),
    ];
@endphp

<script type="application/json" id="notification-config">{!! json_encode($config, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>

<div x-data="notificationCenter()" x-init="init()" class="relative">
    {{-- Bell button --}}
    <button @click="toggleDropdown()" type="button"
            class="relative p-2 inline-flex items-center justify-center text-gray-700 hover:text-gray-900 border border-gray-200 bg-white hover:bg-gray-50 rounded-lg transition-colors"
            aria-label="Notifications">
        <i data-lucide="bell" class="w-5 h-5"></i>
        <span x-show="unreadCount > 0"
              x-cloak
              x-text="unreadCount > 99 ? '99+' : unreadCount"
              class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 inline-flex items-center justify-center text-[10px] font-extrabold text-white bg-red-500 border-2 border-white rounded-full"></span>
    </button>

    {{-- Dropdown panel --}}
    <div x-show="open" x-cloak
         @click.outside="open = false"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 -translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-1"
         class="absolute right-0 mt-2 w-96 max-w-[92vw] bg-white border border-gray-200 rounded-2xl shadow-2xl shadow-slate-900/10 z-50 overflow-hidden">

        <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between gap-2">
            <div>
                <h3 class="text-sm font-bold text-gray-800">Notifications</h3>
                <p class="text-[10px] text-gray-500 mt-0.5">
                    <span x-text="unreadCount"></span> non lue(s) sur <span x-text="items.length"></span>
                </p>
            </div>
            <div class="flex gap-1">
                <button @click="markAllAsRead()"
                        :disabled="unreadCount === 0"
                        class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors disabled:opacity-40 disabled:hover:bg-transparent">
                    Tout marquer lu
                </button>
                <button @click="clearAll()"
                        :disabled="items.length === 0"
                        class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors disabled:opacity-40 disabled:hover:bg-transparent">
                    Effacer
                </button>
            </div>
        </div>

        <div class="max-h-[420px] overflow-y-auto">
            <template x-for="n in items" :key="n.id">
                <div @click="handleClick(n)"
                     class="flex items-start gap-3 px-4 py-3 border-b border-gray-50 cursor-pointer transition-colors"
                     :class="n.is_read ? 'bg-white hover:bg-gray-50' : 'bg-blue-50/40 hover:bg-blue-50'">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0"
                         :class="iconBg(n.type)">
                        <i :data-lucide="iconFor(n)" class="w-4 h-4" :class="iconColor(n.type)"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start gap-2">
                            <p class="text-xs font-bold text-gray-800 truncate flex-1" x-text="n.title"></p>
                            <span x-show="!n.is_read" class="w-2 h-2 bg-blue-500 rounded-full mt-1 shrink-0"></span>
                        </div>
                        <p class="text-[11px] text-gray-600 mt-0.5 line-clamp-2" x-text="n.message"></p>
                        <p class="text-[9px] text-gray-400 mt-1 uppercase tracking-wider" x-text="n.created_human"></p>
                    </div>
                </div>
            </template>

            <div x-show="items.length === 0 && !loading" class="px-6 py-12 text-center">
                <i data-lucide="inbox" class="w-8 h-8 text-gray-300 mx-auto"></i>
                <p class="text-xs text-gray-400 mt-2">Aucune notification pour l'instant.</p>
            </div>
            <div x-show="loading" class="px-6 py-12 text-center text-gray-400 text-xs">
                Chargement...
            </div>
        </div>
    </div>
</div>

{{-- Toast popup for newest unread notification --}}
<div x-data="notificationPopup()"
     x-init="popupInit()"
     @new-notification.window="showPopup($event.detail)"
     class="fixed top-4 right-4 z-[100] pointer-events-none">
    <div x-show="visible" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-x-4"
         x-transition:enter-end="opacity-100 translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-x-0"
         x-transition:leave-end="opacity-0 translate-x-4"
         class="pointer-events-auto w-96 max-w-[92vw] bg-white border-2 rounded-2xl shadow-2xl overflow-hidden"
         :class="popupBorder()">
        <div class="px-4 py-3 flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                 :class="popupIconBg()">
                <i :data-lucide="popupIcon()" class="w-5 h-5" :class="popupIconColor()"></i>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2">
                    <p class="text-sm font-extrabold text-gray-800 truncate" x-text="current?.title"></p>
                    <button @click="dismiss()" class="text-gray-400 hover:text-gray-700 shrink-0">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                <p class="text-xs text-gray-600 mt-1" x-text="current?.message"></p>
                <div class="flex items-center gap-2 mt-3">
                    <button @click="openNotification()"
                            x-show="current?.redirect_url"
                            class="text-[10px] font-extrabold uppercase tracking-wider px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                        Voir
                    </button>
                    <button @click="dismiss()"
                            class="text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 text-gray-500 hover:text-gray-700 rounded-lg transition-colors">
                        Ignorer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
