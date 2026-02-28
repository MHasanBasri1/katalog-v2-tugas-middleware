<header class="admin-topbar sticky top-0 z-10 bg-white/90 dark:bg-gray-950/90 backdrop-blur border-b border-gray-200 dark:border-gray-800">
    <div class="px-4 md:px-8 py-4 flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <button
                type="button"
                @click="$store.sidebar.toggleMobileOpen()"
                class="xl:hidden w-10 h-10 rounded-xl border border-gray-200 dark:border-gray-800 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:text-blue-600"
                :aria-label="$store.sidebar.isMobileOpen ? 'Hide sidebar' : 'Show sidebar'"
                :title="$store.sidebar.isMobileOpen ? 'Hide sidebar' : 'Show sidebar'"
            >
                <i class="ti text-xl" :class="$store.sidebar.isMobileOpen ? 'ti-x' : 'ti-menu-2'"></i>
            </button>
            <button
                type="button"
                @click="$store.sidebar.toggleExpanded()"
                class="hidden xl:flex w-10 h-10 rounded-xl border border-gray-200 dark:border-gray-800 items-center justify-center text-gray-500 dark:text-gray-400 hover:text-blue-600"
            >
                <i class="ti text-xl" :class="$store.sidebar.isExpanded ? 'ti-layout-sidebar-left-collapse' : 'ti-layout-sidebar-right-collapse'"></i>
            </button>
            <div>
                <h1 class="text-lg md:text-xl font-bold text-gray-900 dark:text-white">@yield('header', 'Dashboard')</h1>
                <p class="text-xs text-gray-500 dark:text-gray-400">VISTORA Admin Management</p>
            </div>
        </div>

        <div class="flex items-center gap-3" x-data="{ profileOpen: false }" @keydown.escape.window="profileOpen = false">
            <button
                type="button"
                @click="$store.theme.toggle()"
                class="w-10 h-10 rounded-xl border border-gray-200 dark:border-gray-800 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:text-blue-600 transition"
            >
                <i class="ti text-xl" :class="$store.theme.theme === 'light' ? 'ti-moon' : 'ti-sun'"></i>
            </button>

            <div class="relative">
                <button
                    type="button"
                    @click="profileOpen = !profileOpen"
                    class="w-10 h-10 rounded-xl border border-gray-200 dark:border-gray-800 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:text-blue-600 transition"
                >
                    <i class="ti ti-user-circle text-xl"></i>
                </button>

                <div
                    x-show="profileOpen"
                    x-cloak
                    @click.outside="profileOpen = false"
                    class="absolute right-0 mt-2 w-56 rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-xl overflow-hidden z-30"
                >
                    <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-2 px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800">
                        <i class="ti ti-user"></i>
                        Profil Saya
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2 px-4 py-3 text-sm font-medium text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20">
                            <i class="ti ti-logout-2"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
