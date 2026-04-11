@php
    $notifications = \App\Models\ActivityLog::with('user')
        ->whereNotNull('user_id')
        ->latest()
        ->take(3)
        ->get();
@endphp

<header class="admin-topbar fixed top-0 right-0 z-40 transition-all duration-500 bg-white/80 dark:bg-gray-950/80 backdrop-blur-xl border-b border-gray-100 dark:border-gray-800"
    :class="{
        'xl:left-72': $store.sidebar.isExpanded,
        'xl:left-20': !$store.sidebar.isExpanded,
        'left-0': true
    }">
    <div class="px-4 md:px-8 py-3.5 flex items-center justify-between gap-2 md:gap-4">
        <div class="flex items-center gap-2 md:gap-3 flex-1 min-w-0">
            <button
                type="button"
                @click="$store.sidebar.toggleMobileOpen()"
                class="xl:hidden w-10 h-10 rounded-xl border border-gray-100 dark:border-gray-800 flex items-center justify-center text-gray-400 dark:text-gray-500 hover:text-blue-600 hover:bg-white dark:hover:bg-gray-900 hover:shadow-sm transition-all duration-300 shrink-0"
                :aria-label="$store.sidebar.isMobileOpen ? 'Hide sidebar' : 'Show sidebar'"
                :title="$store.sidebar.isMobileOpen ? 'Hide sidebar' : 'Show sidebar'"
            >
                <i class="ti text-xl" :class="$store.sidebar.isMobileOpen ? 'ti-x' : 'ti-menu-2'"></i>
            </button>
            <button
                type="button"
                @click="$store.sidebar.toggleExpanded()"
                class="hidden xl:flex w-10 h-10 rounded-xl border border-gray-100 dark:border-gray-800 items-center justify-center text-gray-400 dark:text-gray-500 hover:text-blue-600 hover:bg-white dark:hover:bg-gray-900 hover:shadow-sm transition-all duration-300 shrink-0"
            >
                <i class="ti text-xl transition-transform duration-500" :class="$store.sidebar.isExpanded ? 'ti-layout-sidebar-left-collapse' : 'ti-layout-sidebar-right-collapse rotate-180'"></i>
            </button>

            <livewire:admin.global-search />
        </div>

        <div class="flex items-center gap-2 md:gap-3" x-data="{ profileOpen: false, notifOpen: false }" @keydown.escape.window="profileOpen = false; notifOpen = false">
            <!-- Theme Toggle -->
            <button
                type="button"
                @click="$store.theme.toggle()"
                class="w-10 h-10 rounded-xl border border-gray-200 dark:border-gray-800 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:text-blue-600 transition shrink-0"
            >
                <i class="ti text-xl" :class="$store.theme.theme === 'light' ? 'ti-moon' : 'ti-sun'"></i>
            </button>

            <!-- Notification Dropdown -->
            <div class="relative">
                <button
                    type="button"
                    @click="notifOpen = !notifOpen"
                    class="relative w-10 h-10 rounded-xl border border-gray-200 dark:border-gray-800 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:text-blue-600 transition shrink-0"
                >
                    <i class="ti ti-bell text-xl"></i>
                    <span class="absolute top-2.5 right-2.5 w-2 h-2 bg-rose-500 rounded-full border border-white dark:border-gray-950"></span>
                </button>

                <div
                    x-show="notifOpen"
                    x-cloak
                    @click.outside="notifOpen = false"
                    class="absolute right-0 mt-3 w-72 rounded-2xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-2xl overflow-hidden z-[50]"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                >
                    <div class="px-5 py-3 border-b border-gray-50 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/20">
                        <span class="text-[10px] font-bold text-gray-900 dark:text-white uppercase tracking-widest">Aktivitas Terbaru</span>
                    </div>
                    <div class="divide-y divide-gray-50 dark:divide-gray-800">
                        @forelse($notifications as $log)
                            <div class="px-5 py-3.5 hover:bg-gray-50/50 dark:hover:bg-gray-800/50 transition cursor-default">
                                <p class="text-[11px] font-bold text-gray-900 dark:text-gray-100 leading-tight">{{ $log->description }}</p>
                                <p class="mt-1 text-[9px] text-gray-400 font-medium tracking-wide uppercase">{{ $log->created_at->diffForHumans() }}</p>
                            </div>
                        @empty
                            <div class="py-10 text-center">
                                <i class="ti ti-bell-off text-2xl text-gray-200 mb-2"></i>
                                <p class="text-[11px] font-medium text-gray-400">Tidak ada log aktivitas</p>
                            </div>
                        @endforelse
                        @if($notifications->count() > 0)
                            <a href="{{ route('admin.logs.index') }}" class="block py-3 text-center text-[10px] font-black text-blue-600 hover:bg-gray-50 dark:hover:bg-gray-800 transition tracking-widest uppercase bg-blue-50/10 active:scale-95 duration-200">
                                LIHAT SEMUA LOG
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Profile Dropdown -->
            <div class="relative">
                <button
                    type="button"
                    @click="profileOpen = !profileOpen"
                    class="w-10 h-10 rounded-xl border border-gray-200 dark:border-gray-800 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:text-blue-600 transition shrink-0"
                >
                    <i class="ti ti-user-circle text-xl"></i>
                </button>

                <div
                    x-show="profileOpen"
                    x-cloak
                    @click.outside="profileOpen = false"
                    class="absolute right-0 mt-3 w-56 rounded-2xl border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-2xl overflow-hidden z-[50]"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                >
                    <div class="p-2 space-y-1">
                        <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-xs font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-xl transition-all">
                            <i class="ti ti-user text-base text-gray-400"></i>
                            Profil Saya
                        </a>
                        <div class="border-t border-gray-50 dark:border-gray-800 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-xs font-bold text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-xl transition-all text-left">
                                <i class="ti ti-logout-2 text-base"></i>
                                Keluar Aplikasi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
