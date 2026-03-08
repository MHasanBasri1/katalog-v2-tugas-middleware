<aside
    class="fixed top-0 left-0 z-50 h-screen flex flex-col bg-white/95 dark:bg-gray-950/95 backdrop-blur border-r border-gray-200 dark:border-gray-800 transition-all duration-300"
    :class="{
        'w-72': $store.sidebar.isExpanded,
        'w-20': !$store.sidebar.isExpanded,
        '-translate-x-full xl:translate-x-0': !$store.sidebar.isMobileOpen,
        'translate-x-0': $store.sidebar.isMobileOpen
    }"
>
    <div class="h-20 px-4 flex items-center border-b border-gray-200 dark:border-gray-800">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 overflow-hidden">
            <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-primary to-primary-dark text-white flex items-center justify-center shadow-lg shadow-primary/25">
                <i class="fas fa-cube text-xl"></i>
            </div>
            <div x-show="$store.sidebar.isExpanded" x-cloak>
                <p class="text-lg font-black text-gray-900 dark:text-white leading-none tracking-tight">Kataloque</p>
                <p class="text-[10px] uppercase font-black text-gray-400 dark:text-gray-500 tracking-widest mt-1">Admin Panel</p>
            </div>
        </a>
        <button
            type="button"
            @click="$store.sidebar.toggleMobileOpen()"
            class="xl:hidden ml-auto w-10 h-10 rounded-xl border border-gray-200 dark:border-gray-800 inline-flex items-center justify-center text-gray-500 dark:text-gray-400 hover:text-blue-600"
            aria-label="Hide sidebar"
            title="Hide sidebar"
        >
            <i class="ti ti-x text-xl"></i>
        </button>
    </div>

    @php
        $canDashboard = auth()->user()?->can('dashboard.view');
        $canCatalog = auth()->user()?->can('categories.manage') || auth()->user()?->can('products.manage');
        $canContent = auth()->user()?->can('blogs.manage') || auth()->user()?->can('banners.manage') || auth()->user()?->can('static_pages.manage');
        $canUsers = auth()->user()?->can('users.manage');
        $canSystem = auth()->user()?->can('settings.manage');

        $isCatalogActive = request()->routeIs('admin.kategori.*') || request()->routeIs('admin.produk.*');
        $isContentActive = request()->routeIs('admin.blog.*') || request()->routeIs('admin.blog-kategori.*') || request()->routeIs('admin.banner.*') || request()->routeIs('admin.halaman-statis.*');
        $isUsersActive = request()->routeIs('admin.user.*');
        $isSystemActive = request()->routeIs('admin.setting.*');
    @endphp

    <nav
        class="px-3 py-4 space-y-1 overflow-y-auto flex-1 min-h-0"
        x-data="{
            openCatalog: @js($isCatalogActive),
            openContent: @js($isContentActive),
            openUsers: @js($isUsersActive),
            openSystem: @js($isSystemActive),
            toggleGroup(groupName) {
                if (! $store.sidebar.isExpanded) {
                    $store.sidebar.toggleExpanded();
                    return;
                }

                this[groupName] = !this[groupName];
            }
        }"
    >
        @if ($canDashboard)
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
        @endif

        @if ($canCatalog)
            <button
                type="button"
                @click="toggleGroup('openCatalog')"
                class="w-full flex items-center rounded-xl px-3 py-3 text-sm font-semibold transition"
                :class="$store.sidebar.isExpanded ? '' : 'justify-center'"
                @class([
                    'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-300' => $isCatalogActive,
                    'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-900' => ! $isCatalogActive,
                ])
            >
                <i class="ti ti-stack text-xl"></i>
                <span x-show="$store.sidebar.isExpanded" x-cloak class="ml-3">Katalog</span>
                <i x-show="$store.sidebar.isExpanded" x-cloak class="ti ti-chevron-down ml-auto transition-transform" :class="openCatalog ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="$store.sidebar.isExpanded && openCatalog" class="space-y-1 pl-3 pr-1">
                @can('categories.manage')
                    <a href="{{ route('admin.kategori.index') }}"
                       class="flex items-center rounded-lg px-3 py-2.5 text-sm font-medium transition"
                       @class([
                           'bg-blue-600 text-white shadow-sm shadow-blue-600/20' => request()->routeIs('admin.kategori.*'),
                           'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-900' => !request()->routeIs('admin.kategori.*'),
                       ])>
                        <i class="ti ti-category text-lg"></i>
                        <span class="ml-2.5">Kategori</span>
                    </a>
                @endcan
                @can('products.manage')
                    <a href="{{ route('admin.produk.index') }}"
                       class="flex items-center rounded-lg px-3 py-2.5 text-sm font-medium transition"
                       @class([
                           'bg-blue-600 text-white shadow-sm shadow-blue-600/20' => request()->routeIs('admin.produk.*'),
                           'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-900' => !request()->routeIs('admin.produk.*'),
                       ])>
                        <i class="ti ti-package text-lg"></i>
                        <span class="ml-2.5">Produk</span>
                    </a>
                @endcan
            </div>
        @endif

        @if ($canContent)
            <button
                type="button"
                @click="toggleGroup('openContent')"
                class="w-full flex items-center rounded-xl px-3 py-3 text-sm font-semibold transition"
                :class="$store.sidebar.isExpanded ? '' : 'justify-center'"
                @class([
                    'bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-300' => $isContentActive,
                    'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-900' => ! $isContentActive,
                ])
            >
                <i class="ti ti-article text-xl"></i>
                <span x-show="$store.sidebar.isExpanded" x-cloak class="ml-3">Konten</span>
                <i x-show="$store.sidebar.isExpanded" x-cloak class="ti ti-chevron-down ml-auto transition-transform" :class="openContent ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="$store.sidebar.isExpanded && openContent" class="space-y-1 pl-3 pr-1">
                @can('blogs.manage')
                    <a href="{{ route('admin.blog.index') }}"
                       class="flex items-center rounded-lg px-3 py-2.5 text-sm font-medium transition"
                       @class([
                           'bg-blue-600 text-white shadow-sm shadow-blue-600/20' => request()->routeIs('admin.blog.*'),
                           'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-900' => !request()->routeIs('admin.blog.*'),
                       ])>
                        <i class="ti ti-news text-lg"></i>
                        <span class="ml-2.5">Artikel Blog</span>
                    </a>
                    <a href="{{ route('admin.blog-kategori.index') }}"
                       class="flex items-center rounded-lg px-3 py-2.5 text-sm font-medium transition"
                       @class([
                           'bg-blue-600 text-white shadow-sm shadow-blue-600/20' => request()->routeIs('admin.blog-kategori.*'),
                           'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-900' => !request()->routeIs('admin.blog-kategori.*'),
                       ])>
                        <i class="ti ti-tags text-lg"></i>
                        <span class="ml-2.5">Kategori Blog</span>
                    </a>
                @endcan
                @can('banners.manage')
                    <a href="{{ route('admin.banner.index') }}"
                       class="flex items-center rounded-lg px-3 py-2.5 text-sm font-medium transition"
                       @class([
                           'bg-blue-600 text-white shadow-sm shadow-blue-600/20' => request()->routeIs('admin.banner.*'),
                           'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-900' => !request()->routeIs('admin.banner.*'),
                       ])>
                        <i class="ti ti-photo text-lg"></i>
                        <span class="ml-2.5">Banner</span>
                    </a>
                @endcan
                @can('static_pages.manage')
                    <a href="{{ route('admin.halaman-statis.index') }}"
                       class="flex items-center rounded-lg px-3 py-2.5 text-sm font-medium transition"
                       @class([
                           'bg-blue-600 text-white shadow-sm shadow-blue-600/20' => request()->routeIs('admin.halaman-statis.*'),
                           'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-900' => !request()->routeIs('admin.halaman-statis.*'),
                       ])>
                        <i class="ti ti-file-text text-lg"></i>
                        <span class="ml-2.5">Halaman</span>
                    </a>
                @endcan
            </div>
        @endif

        @if ($canUsers)
            <a href="{{ route('admin.user.index') }}"
               class="flex items-center rounded-xl px-3 py-3 text-sm font-semibold transition"
               :class="$store.sidebar.isExpanded ? '' : 'justify-center'"
               @class([
                   'bg-blue-600 text-white shadow-md shadow-blue-600/20' => request()->routeIs('admin.user.*'),
                   'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-900' => !request()->routeIs('admin.user.*'),
               ])>
                <i class="ti ti-users text-xl"></i>
                <span x-show="$store.sidebar.isExpanded" x-cloak class="ml-3">Pengguna</span>
            </a>
        @endif

        @if ($canSystem)
            <a href="{{ route('admin.setting.index') }}"
               class="flex items-center rounded-xl px-3 py-3 text-sm font-semibold transition"
               :class="$store.sidebar.isExpanded ? '' : 'justify-center'"
               @class([
                   'bg-blue-600 text-white shadow-md shadow-blue-600/20' => request()->routeIs('admin.setting.*'),
                   'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-900' => !request()->routeIs('admin.setting.*'),
               ])>
                <i class="ti ti-settings text-xl"></i>
                <span x-show="$store.sidebar.isExpanded" x-cloak class="ml-3">Pengaturan</span>
            </a>
        @endif
    </nav>

    <div class="p-3 border-t border-gray-200 dark:border-gray-800 bg-white/95 dark:bg-gray-950/95">
        <div class="flex items-center gap-2" :class="$store.sidebar.isExpanded ? 'justify-between' : 'justify-center'">
            <a
                href="{{ route('admin.profile.edit') }}"
                x-show="$store.sidebar.isExpanded"
                x-cloak
                class="flex items-center min-w-0 rounded-xl px-2 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-900 transition"
                :class="$store.sidebar.isExpanded ? 'gap-2 flex-1' : 'justify-center w-10 h-10'"
            >
                <i class="ti ti-user-circle text-xl shrink-0"></i>
                <span x-show="$store.sidebar.isExpanded" x-cloak class="text-sm font-semibold truncate">{{ auth()->user()?->name }}</span>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="w-10 h-10 rounded-xl text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 inline-flex items-center justify-center transition"
                >
                    <i class="ti ti-logout-2 text-xl"></i>
                </button>
            </form>
        </div>
    </div>
</aside>
