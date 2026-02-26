<aside
    class="fixed top-0 left-0 z-50 h-screen bg-white/95 dark:bg-gray-950/95 backdrop-blur border-r border-gray-200 dark:border-gray-800 transition-all duration-300"
    :class="{
        'w-72': $store.sidebar.isExpanded,
        'w-20': !$store.sidebar.isExpanded,
        '-translate-x-full xl:translate-x-0': !$store.sidebar.isMobileOpen,
        'translate-x-0': $store.sidebar.isMobileOpen
    }"
>
    <div class="h-20 px-4 flex items-center">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 overflow-hidden">
            <div class="w-11 h-11 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-lg shadow-blue-600/25">
                <i class="ti ti-compass text-2xl"></i>
            </div>
            <div x-show="$store.sidebar.isExpanded" x-cloak>
                <p class="text-lg font-black text-blue-600 italic leading-none">VISTORA</p>
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">Admin Panel</p>
            </div>
        </a>
    </div>

    <nav class="px-3 py-4 space-y-1">
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center rounded-xl px-3 py-3 text-sm font-semibold transition"
           :class="$store.sidebar.isExpanded ? '' : 'justify-center'"
           @class([
               'bg-blue-600 text-white shadow-md shadow-blue-600/20' => request()->routeIs('admin.dashboard'),
               'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-900' => !request()->routeIs('admin.dashboard'),
           ])>
            <i class="ti ti-layout-dashboard text-xl"></i>
            <span x-show="$store.sidebar.isExpanded" x-cloak class="ml-3">Dashboard</span>
        </a>

        <a href="{{ route('admin.kategori.index') }}"
           class="flex items-center rounded-xl px-3 py-3 text-sm font-semibold transition"
           :class="$store.sidebar.isExpanded ? '' : 'justify-center'"
           @class([
               'bg-blue-600 text-white shadow-md shadow-blue-600/20' => request()->routeIs('admin.kategori.*'),
               'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-900' => !request()->routeIs('admin.kategori.*'),
           ])>
            <i class="ti ti-category text-xl"></i>
            <span x-show="$store.sidebar.isExpanded" x-cloak class="ml-3">Kategori</span>
        </a>

        <a href="{{ route('admin.produk.index') }}"
           class="flex items-center rounded-xl px-3 py-3 text-sm font-semibold transition"
           :class="$store.sidebar.isExpanded ? '' : 'justify-center'"
           @class([
               'bg-blue-600 text-white shadow-md shadow-blue-600/20' => request()->routeIs('admin.produk.*'),
               'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-900' => !request()->routeIs('admin.produk.*'),
           ])>
            <i class="ti ti-package text-xl"></i>
            <span x-show="$store.sidebar.isExpanded" x-cloak class="ml-3">Produk</span>
        </a>

        <a href="{{ route('admin.marketplace-link.index') }}"
           class="flex items-center rounded-xl px-3 py-3 text-sm font-semibold transition"
           :class="$store.sidebar.isExpanded ? '' : 'justify-center'"
           @class([
               'bg-blue-600 text-white shadow-md shadow-blue-600/20' => request()->routeIs('admin.marketplace-link.*'),
               'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-900' => !request()->routeIs('admin.marketplace-link.*'),
           ])>
            <i class="ti ti-link text-xl"></i>
            <span x-show="$store.sidebar.isExpanded" x-cloak class="ml-3">Marketplace</span>
        </a>

        <a href="{{ route('admin.setting.index') }}"
           class="flex items-center rounded-xl px-3 py-3 text-sm font-semibold transition"
           :class="$store.sidebar.isExpanded ? '' : 'justify-center'"
           @class([
               'bg-blue-600 text-white shadow-md shadow-blue-600/20' => request()->routeIs('admin.setting.*'),
               'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-900' => !request()->routeIs('admin.setting.*'),
           ])>
            <i class="ti ti-settings text-xl"></i>
            <span x-show="$store.sidebar.isExpanded" x-cloak class="ml-3">Setting</span>
        </a>
    </nav>

    <div class="absolute bottom-0 left-0 right-0 p-3 border-t border-gray-200 dark:border-gray-800">
        <button
            type="button"
            @click="$store.theme.toggle()"
            class="w-full flex items-center rounded-xl px-3 py-2.5 text-sm font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-900 transition"
            :class="$store.sidebar.isExpanded ? '' : 'justify-center'"
        >
            <i class="ti text-xl" :class="$store.theme.theme === 'light' ? 'ti-moon' : 'ti-sun'"></i>
            <span x-show="$store.sidebar.isExpanded" x-cloak class="ml-3">
                <span x-text="$store.theme.theme === 'light' ? 'Dark Mode' : 'Light Mode'"></span>
            </span>
        </button>
    </div>
</aside>
