<aside
    class="fixed top-0 left-0 z-[200] h-screen flex flex-col bg-white dark:bg-gray-950 border-r border-gray-200 dark:border-gray-800 transition-all duration-500 ease-in-out shadow-xl shadow-gray-200/50 dark:shadow-none"
    :class="{
        'w-72': $store.sidebar.isSidebarForceExpanded,
        'w-20': !$store.sidebar.isSidebarForceExpanded,
        '-translate-x-full xl:translate-x-0': !$store.sidebar.isMobileOpen,
        'translate-x-0': $store.sidebar.isMobileOpen
    }"
>
    <div class="h-20 px-6 flex items-center border-b border-gray-100 dark:border-gray-800/50">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 overflow-hidden">
            <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-primary to-primary-dark text-white flex items-center justify-center shadow-lg shadow-primary/25">
                <i class="fas fa-shopping-basket text-xl"></i>
            </div>
            <div x-show="$store.sidebar.isSidebarForceExpanded" x-cloak>
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
        $canCatalog = auth()->user()?->can('categories.manage') || auth()->user()?->can('products.manage') || auth()->user()?->can('vouchers.manage');
        $canContent = auth()->user()?->can('blogs.manage') || auth()->user()?->can('banners.manage') || auth()->user()?->can('static_pages.manage');
        $canUsers = auth()->user()?->can('users.manage');
        $canSystem = auth()->user()?->can('settings.manage');

        $isCatalogActive = request()->routeIs('admin.kategori.*') || request()->routeIs('admin.produk.*') || request()->routeIs('admin.voucher.*');
        $isContentActive = request()->routeIs('admin.blog.*') || request()->routeIs('admin.blog-kategori.*') || request()->routeIs('admin.banner.*') || request()->routeIs('admin.halaman-statis.*');
        $isUsersActive = request()->routeIs('admin.user.*');
        $isSystemActive = request()->routeIs('admin.setting.*');
    @endphp

    <nav
        class="px-4 py-4 space-y-1.5 overflow-y-auto flex-1 min-h-0 custom-scrollbar"
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
        <div x-show="$store.sidebar.isSidebarForceExpanded" x-cloak class="px-3 pt-2 pb-1 text-[10px] font-extrabold text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em]">Utama</div>
        @if ($canDashboard)
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center rounded-2xl px-3 py-2.5 text-sm font-bold transition-all duration-300 group relative overflow-hidden"
               :class="$store.sidebar.isSidebarForceExpanded ? '' : 'justify-center'"
               @class([
                   'bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-lg shadow-blue-500/30' => request()->routeIs('admin.dashboard'),
                   'text-gray-500 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-900 hover:shadow-sm hover:text-blue-600 dark:hover:text-blue-400 border border-transparent hover:border-gray-100 dark:hover:border-gray-800' => !request()->routeIs('admin.dashboard'),
               ])>
                @if(request()->routeIs('admin.dashboard'))
                    <div class="absolute left-0 inset-y-2 w-1 bg-blue-400 rounded-r-full shadow-[0_0_10px_rgba(96,165,250,0.8)]"></div>
                @endif
                <i class="ti ti-layout-dashboard text-xl group-hover:scale-110 transition-transform"></i>
                <span x-show="$store.sidebar.isSidebarForceExpanded" x-cloak class="ml-3">Dashboard</span>
            </a>

            <a href="{{ route('admin.statistics') }}"
               class="flex items-center rounded-2xl px-3 py-2.5 text-sm font-bold transition-all duration-300 group relative overflow-hidden"
               :class="$store.sidebar.isSidebarForceExpanded ? '' : 'justify-center'"
               @class([
                   'bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-lg shadow-blue-500/30' => request()->routeIs('admin.statistics'),
                   'text-gray-500 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-900 hover:shadow-sm hover:text-blue-600 dark:hover:text-blue-400 border border-transparent hover:border-gray-100 dark:hover:border-gray-800' => !request()->routeIs('admin.statistics'),
               ])>
                @if(request()->routeIs('admin.statistics'))
                    <div class="absolute left-0 inset-y-2 w-1 bg-blue-400 rounded-r-full shadow-[0_0_10px_rgba(96,165,250,0.8)]"></div>
                @endif
                <i class="ti ti-chart-bar text-xl group-hover:scale-110 transition-transform"></i>
                <span x-show="$store.sidebar.isSidebarForceExpanded" x-cloak class="ml-3">Statistik</span>
            </a>
        @endif

        @php
            $isContentManagementActive = $isCatalogActive || $isContentActive;
        @endphp

        <div x-show="$store.sidebar.isSidebarForceExpanded" x-cloak class="px-3 pt-4 pb-1 text-[10px] font-extrabold text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em]">Manajemen Konten</div>
        
        {{-- INVENTORI GROUP --}}
        <button
            type="button"
            @click="toggleGroup('openCatalog')"
            class="w-full flex items-center rounded-2xl px-3 py-2.5 text-sm font-bold transition-all duration-300 group relative overflow-hidden"
            :class="$store.sidebar.isSidebarForceExpanded ? '' : 'justify-center'"
            @class([
                'bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-lg shadow-blue-500/30' => $isCatalogActive,
                'text-gray-500 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-900 hover:shadow-sm hover:text-blue-600 dark:hover:text-blue-400 border border-transparent hover:border-gray-100 dark:hover:border-gray-800' => ! $isCatalogActive,
            ])
        >
            @if($isCatalogActive)
                <div class="absolute left-0 inset-y-2 w-1 bg-blue-400 rounded-r-full shadow-[0_0_10px_rgba(96,165,250,0.8)]"></div>
            @endif
            <i class="ti ti-box text-xl group-hover:scale-110 transition-transform"></i>
            <span x-show="$store.sidebar.isSidebarForceExpanded" x-cloak class="ml-3">Inventori</span>
            <i x-show="$store.sidebar.isSidebarForceExpanded" x-cloak class="ti ti-chevron-down ml-auto text-[10px] opacity-50 transition-transform" :class="openCatalog ? 'rotate-180' : ''"></i>
        </button>
        <div x-show="$store.sidebar.isSidebarForceExpanded && openCatalog" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="space-y-1 pr-1 mt-0.5 ml-7 border-l border-gray-100 dark:border-gray-800">
            @can('categories.manage')
                <a href="{{ route('admin.kategori.index') }}"
                   class="flex items-center rounded-xl px-4 py-2 text-xs font-bold transition-all duration-200 relative overflow-hidden group"
                   @class([
                       'bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-md shadow-blue-500/20' => request()->routeIs('admin.kategori.*'),
                       'text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-white dark:hover:bg-gray-800' => !request()->routeIs('admin.kategori.*'),
                   ])>
                    @if(request()->routeIs('admin.kategori.*'))
                        <div class="absolute left-0 inset-y-1.5 w-0.5 bg-blue-300/80 rounded-r-full"></div>
                    @endif
                    <span>Kategori</span>
                </a>
            @endcan
            @can('products.manage')
                <a href="{{ route('admin.produk.index') }}"
                   class="flex items-center rounded-xl px-4 py-2 text-xs font-bold transition-all duration-200 relative overflow-hidden group"
                   @class([
                       'bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-md shadow-blue-500/20' => request()->routeIs('admin.produk.*'),
                       'text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-white dark:hover:bg-gray-800' => !request()->routeIs('admin.produk.*'),
                   ])>
                    @if(request()->routeIs('admin.produk.*'))
                        <div class="absolute left-0 inset-y-1.5 w-0.5 bg-blue-300/80 rounded-r-full"></div>
                    @endif
                    <span>Produk</span>
                </a>
            @endcan
            @can('vouchers.manage')
                <a href="{{ route('admin.voucher.index') }}"
                   class="flex items-center rounded-xl px-4 py-2 text-xs font-bold transition-all duration-200 relative overflow-hidden group"
                   @class([
                       'bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-md shadow-blue-500/20' => request()->routeIs('admin.voucher.*'),
                       'text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-white dark:hover:bg-gray-800' => !request()->routeIs('admin.voucher.*'),
                   ])>
                    @if(request()->routeIs('admin.voucher.*'))
                        <div class="absolute left-0 inset-y-1.5 w-0.5 bg-blue-300/80 rounded-r-full"></div>
                    @endif
                    <span>Voucher</span>
                </a>
            @endcan
        </div>

        {{-- PUBLIKASI GROUP --}}
        <button
            type="button"
            @click="toggleGroup('openContent')"
            class="w-full flex items-center rounded-2xl px-3 py-2.5 text-sm font-bold transition-all duration-300 group relative overflow-hidden mt-1"
            :class="$store.sidebar.isSidebarForceExpanded ? '' : 'justify-center'"
            @class([
                'bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-lg shadow-blue-500/30' => $isContentActive,
                'text-gray-500 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-900 hover:shadow-sm hover:text-blue-600 dark:hover:text-blue-400 border border-transparent hover:border-gray-100 dark:hover:border-gray-800' => ! $isContentActive,
            ])
        >
            @if($isContentActive)
                <div class="absolute left-0 inset-y-2 w-1 bg-blue-400 rounded-r-full shadow-[0_0_10px_rgba(96,165,250,0.8)]"></div>
            @endif
            <i class="ti ti-news text-xl group-hover:scale-110 transition-transform"></i>
            <span x-show="$store.sidebar.isSidebarForceExpanded" x-cloak class="ml-3">Publikasi</span>
            <i x-show="$store.sidebar.isSidebarForceExpanded" x-cloak class="ti ti-chevron-down ml-auto text-[10px] opacity-50 transition-transform" :class="openContent ? 'rotate-180' : ''"></i>
        </button>
        <div x-show="$store.sidebar.isSidebarForceExpanded && openContent" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="space-y-1 pr-1 mt-0.5 ml-7 border-l border-gray-100 dark:border-gray-800">
            @can('blogs.manage')
                <a href="{{ route('admin.blog.index') }}"
                   class="flex items-center rounded-xl px-4 py-2 text-xs font-bold transition-all duration-200 relative overflow-hidden group"
                   @class([
                       'bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-md shadow-blue-500/20' => request()->routeIs('admin.blog.*'),
                       'text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-white dark:hover:bg-gray-800' => !request()->routeIs('admin.blog.*'),
                   ])>
                    @if(request()->routeIs('admin.blog.*'))
                        <div class="absolute left-0 inset-y-1.5 w-0.5 bg-blue-300/80 rounded-r-full"></div>
                    @endif
                    <span>Artikel Blog</span>
                </a>
                <a href="{{ route('admin.blog-kategori.index') }}"
                   class="flex items-center rounded-xl px-4 py-2 text-xs font-bold transition-all duration-200 relative overflow-hidden group"
                   @class([
                       'bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-md shadow-blue-500/20' => request()->routeIs('admin.blog-kategori.*'),
                       'text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-white dark:hover:bg-gray-800' => !request()->routeIs('admin.blog-kategori.*'),
                   ])>
                    @if(request()->routeIs('admin.blog-kategori.*'))
                        <div class="absolute left-0 inset-y-1.5 w-0.5 bg-blue-300/80 rounded-r-full"></div>
                    @endif
                    <span>Kategori Blog</span>
                </a>
            @endcan
            @can('banners.manage')
                <a href="{{ route('admin.banner.index') }}"
                   class="flex items-center rounded-xl px-4 py-2 text-xs font-bold transition-all duration-200 relative overflow-hidden group"
                   @class([
                       'bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-md shadow-blue-500/20' => request()->routeIs('admin.banner.*'),
                       'text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-white dark:hover:bg-gray-800' => !request()->routeIs('admin.banner.*'),
                   ])>
                    @if(request()->routeIs('admin.banner.*'))
                        <div class="absolute left-0 inset-y-1.5 w-0.5 bg-blue-300/80 rounded-r-full"></div>
                    @endif
                    <span>Banner</span>
                </a>
            @endcan
            @can('static_pages.manage')
                <a href="{{ route('admin.halaman-statis.index') }}"
                   class="flex items-center rounded-xl px-4 py-2 text-xs font-bold transition-all duration-200 relative overflow-hidden group"
                   @class([
                       'bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-md shadow-blue-500/20' => request()->routeIs('admin.halaman-statis.*'),
                       'text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-white dark:hover:bg-gray-800' => !request()->routeIs('admin.halaman-statis.*'),
                   ])>
                    @if(request()->routeIs('admin.halaman-statis.*'))
                        <div class="absolute left-0 inset-y-1.5 w-0.5 bg-blue-300/80 rounded-r-full"></div>
                    @endif
                    <span>Halaman</span>
                </a>
            @endcan
        </div>

        @if ($canUsers || $canSystem)
            <div x-show="$store.sidebar.isSidebarForceExpanded" x-cloak class="px-3 pt-4 pb-1 text-[10px] font-extrabold text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em]">Sistem</div>
        @endif

        @if ($canUsers)
            <a href="{{ route('admin.user.index') }}"
               class="flex items-center rounded-2xl px-3 py-2.5 text-sm font-bold transition-all duration-300 group relative overflow-hidden"
               :class="$store.sidebar.isSidebarForceExpanded ? '' : 'justify-center'"
               @class([
                   'bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-lg shadow-blue-500/30' => request()->routeIs('admin.user.*'),
                   'text-gray-500 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-900 hover:shadow-sm hover:text-blue-600 dark:hover:text-blue-400 border border-transparent hover:border-gray-100 dark:hover:border-gray-800' => !request()->routeIs('admin.user.*'),
               ])>
                @if(request()->routeIs('admin.user.*'))
                    <div class="absolute left-0 inset-y-2 w-1 bg-blue-400 rounded-r-full shadow-[0_0_10px_rgba(96,165,250,0.8)]"></div>
                @endif
                <i class="ti ti-users text-xl group-hover:scale-110 transition-transform"></i>
                <span x-show="$store.sidebar.isSidebarForceExpanded" x-cloak class="ml-3">Pengguna</span>
            </a>
        @endif

        @if ($canSystem)
            <a href="{{ route('admin.setting.index') }}"
               class="flex items-center rounded-2xl px-3 py-2.5 text-sm font-bold transition-all duration-300 group relative overflow-hidden"
               :class="$store.sidebar.isSidebarForceExpanded ? '' : 'justify-center'"
               @class([
                   'bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-lg shadow-blue-500/30' => $isSystemActive,
                   'text-gray-500 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-900 hover:shadow-sm hover:text-blue-600 dark:hover:text-blue-400 border border-transparent hover:border-gray-100 dark:hover:border-gray-800' => ! $isSystemActive,
               ])>
                @if($isSystemActive)
                    <div class="absolute left-0 inset-y-2 w-1 bg-blue-400 rounded-r-full shadow-[0_0_10px_rgba(96,165,250,0.8)]"></div>
                @endif
                <i class="ti ti-settings text-xl group-hover:scale-110 transition-transform"></i>
                <span x-show="$store.sidebar.isSidebarForceExpanded" x-cloak class="ml-3">Pengaturan</span>
            </a>
        @endif

        @php
            $isDeveloper = auth()->user()?->hasRole('developer');
        @endphp

        @if ($isDeveloper)
            <div x-show="$store.sidebar.isSidebarForceExpanded" x-cloak class="px-3 pt-4 pb-1 text-[10px] font-extrabold text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em]">Developer Tools</div>
            <a href="{{ route('user.panel') }}"
               class="flex items-center rounded-2xl px-3 py-2.5 text-sm font-bold transition-all duration-300 group relative overflow-hidden mt-1 bg-[#2563EB]/10 dark:bg-[#2563EB]/20 text-[#2563EB] dark:text-[#2563EB] hover:bg-[#2563EB]/20 dark:hover:bg-[#2563EB]/30 hover:shadow-sm border border-[#2563EB]/30 dark:border-[#2563EB]/50"
               :class="$store.sidebar.isSidebarForceExpanded ? '' : 'justify-center'"
               title="Lihat Tampilan Member">
                <i class="ti ti-external-link text-xl group-hover:scale-110 transition-transform"></i>
                <span x-show="$store.sidebar.isSidebarForceExpanded" x-cloak class="ml-3">Tampilan Member</span>
            </a>
        @endif
    </nav>

    <div class="p-4 border-t border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-950">
        <div class="flex items-center gap-2" :class="$store.sidebar.isSidebarForceExpanded ? 'justify-between' : 'justify-center'">
            <a
                href="{{ route('admin.profile.edit') }}"
                x-show="$store.sidebar.isSidebarForceExpanded"
                x-cloak
                class="flex items-center min-w-0 rounded-2xl px-2.5 py-2.5 text-gray-700 dark:text-gray-200 hover:bg-white dark:hover:bg-gray-800 hover:shadow-sm border border-transparent hover:border-gray-100 dark:hover:border-gray-700 transition-all duration-300"
                :class="$store.sidebar.isSidebarForceExpanded ? 'gap-3 flex-1' : 'justify-center w-12 h-12'"
            >
                <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0">
                    <i class="ti ti-user text-lg"></i>
                </div>
                <div class="flex flex-col min-w-0" x-show="$store.sidebar.isSidebarForceExpanded" x-cloak>
                    <span class="text-xs font-bold truncate leading-tight">{{ auth()->user()?->name }}</span>
                    <span class="text-[10px] text-gray-400 font-medium truncate uppercase tracking-tighter">Administrator</span>
                </div>
            </a>

            <div class="flex items-center gap-1">
                <!-- Logout (Desktop: Always, Mobile: Specific Button) -->
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button
                        type="submit"
                        class="group w-11 h-11 rounded-2xl text-gray-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 inline-flex items-center justify-center transition-all duration-300 border border-transparent hover:border-rose-100 dark:hover:border-rose-900/10"
                        title="Logout"
                    >
                        <i class="ti ti-logout-2 text-xl group-hover:rotate-12 transition-transform"></i>
                    </button>
                </form>

                <!-- Theme Toggle (Only on Mobile) -->
                <button
                    type="button"
                    @click="$store.theme.toggle()"
                    class="xl:hidden w-11 h-11 rounded-2xl text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 inline-flex items-center justify-center transition-all duration-300 border border-transparent hover:border-blue-100 dark:hover:border-blue-900/10"
                    title="Toggle Theme"
                >
                    <i class="ti text-xl transition-all duration-300" :class="$store.theme.theme === 'light' ? 'ti-moon' : 'ti-sun'"></i>
                </button>
            </div>
        </div>
    </div>
</aside>
