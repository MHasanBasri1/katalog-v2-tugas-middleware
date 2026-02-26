<header class="sticky top-0 z-30 bg-white/90 dark:bg-gray-950/90 backdrop-blur border-b border-gray-200 dark:border-gray-800">
    <div class="px-4 md:px-8 py-4 flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <button
                type="button"
                @click="$store.sidebar.toggleMobileOpen()"
                class="xl:hidden w-10 h-10 rounded-xl border border-gray-200 dark:border-gray-800 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:text-blue-600"
            >
                <i class="ti ti-menu-2 text-xl"></i>
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

        <div class="flex items-center gap-3">
            <button
                type="button"
                @click="$store.theme.toggle()"
                class="w-10 h-10 rounded-xl border border-gray-200 dark:border-gray-800 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:text-blue-600 transition"
            >
                <i class="ti text-xl" :class="$store.theme.theme === 'light' ? 'ti-moon' : 'ti-sun'"></i>
            </button>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 shadow-md shadow-blue-600/20 transition"
                >
                    <i class="ti ti-logout-2 text-base"></i>
                    Logout
                </button>
            </form>
        </div>
    </div>
</header>
