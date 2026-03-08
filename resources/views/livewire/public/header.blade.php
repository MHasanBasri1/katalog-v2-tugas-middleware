<div
    x-data="{
        mobileMenu: false,
        mobileSearch: false,
        lastScrollY: 0,
        isDesktopMenuHidden: false,
        init() {
            this.lastScrollY = window.scrollY;
            window.addEventListener('scroll', () => {
                if (window.innerWidth < 768) {
                    this.isDesktopMenuHidden = false;
                    this.lastScrollY = window.scrollY;
                    return;
                }

                const currentY = window.scrollY;
                if (currentY <= 8) {
                    this.isDesktopMenuHidden = false;
                } else {
                    this.isDesktopMenuHidden = currentY > this.lastScrollY;
                }
                this.lastScrollY = currentY;
            }, { passive: true });
        }
    }"
    class="relative"
>
    <!-- Main Header Container -->
    <header class="fixed top-0 left-0 right-0 bg-white/80 backdrop-blur-xl border-b border-white/40 z-[100] transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between gap-3 sm:gap-6">
        <a href="{{ route('home') }}" class="flex items-center gap-2 md:gap-2.5 text-2xl font-black text-gray-900 tracking-tight">
            <div class="w-8 h-8 md:w-10 md:h-10 rounded-xl bg-gradient-to-br from-primary to-primary-dark text-white flex items-center justify-center shadow-md shadow-primary/30">
                <i class="fas fa-cube text-sm md:text-lg"></i>
            </div>
            <span>Kataloque</span>
        </a>

        <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative hidden md:block z-[60]">
            <button @click="open = !open" class="flex items-center gap-2.5 px-4 py-2 rounded-full bg-gray-50 hover:bg-gray-100 text-gray-700 hover:text-primary font-bold transition-all duration-300">
                <i class="fas fa-layer-group text-xs"></i>
                Kategori
                <i class="fas fa-chevron-down text-[10px] transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2" class="absolute top-full left-0 mt-3 w-56 bg-white border border-gray-100 rounded-2xl shadow-xl py-2 z-50">
                @foreach($categories as $category)
                    <a href="{{ route('kategori.detail', $category['slug']) }}" class="block px-4 py-2.5 mx-2 rounded-xl hover:bg-primary/5 hover:text-primary transition-colors text-sm text-gray-700 font-medium">{{ $category['name'] }}</a>
                @endforeach
                <div class="h-px bg-gray-50 my-2 mx-4"></div>
                <a href="{{ route('kategori') }}" class="block px-4 py-2.5 mx-2 rounded-xl hover:bg-primary/5 text-sm font-bold text-primary transition-colors flex items-center justify-between group">
                    Explore kategori
                    <i class="fas fa-arrow-right text-xs opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0 transition-all"></i>
                </a>
            </div>
        </div>

        <div class="hidden md:block flex-1 max-w-2xl relative z-[55]" wire:click.stop>
            <input wire:model.live.debounce.300ms="search" wire:keydown.enter="goToSearch" type="search" placeholder="Cari produk apa saja..." class="w-full bg-gray-50/70 hover:bg-gray-50 border border-gray-200 focus:border-primary focus:ring-4 focus:ring-primary/10 focus:bg-white rounded-full py-2.5 pl-5 pr-12 outline-none transition-all duration-300 text-sm placeholder:text-gray-400">
            <button wire:click="goToSearch" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary transition-colors duration-300" title="Cari">
                <i class="fas fa-search"></i>
            </button>

            @if($search !== '')
                <div class="absolute left-0 right-0 mt-3 bg-white border border-gray-100 rounded-2xl shadow-xl z-40 overflow-hidden">
                    <div class="p-2 space-y-1">
                        @forelse($this->searchResults as $item)
                            <a href="{{ $item['url'] }}" class="block px-4 py-2.5 rounded-xl text-sm text-gray-700 hover:bg-primary/5 hover:text-primary font-medium transition-colors">{{ $item['name'] }}</a>
                        @empty
                            <div class="px-4 py-3 text-sm text-gray-500 text-center">Produk tidak ditemukan.</div>
                        @endforelse
                    </div>
                </div>
            @endif
        </div>

        <div class="flex items-center gap-3 sm:gap-4 relative z-50">
            <!-- Mobile Search Toggle -->
            <button
                @click="mobileSearch = !mobileSearch; if (mobileSearch) { $nextTick(() => $refs.mobileSearchInput.focus()) }"
                class="flex md:hidden relative w-10 h-10 items-center justify-center rounded-full bg-gray-50 text-gray-900 border border-gray-100 shadow-sm transition-all active:scale-95 z-50"
                title="Cari"
            >
                <i class="fas fa-search text-base text-gray-600"></i>
            </button>
            <a href="{{ auth()->check() ? route('user.panel', ['tab' => 'favorit']) : route('user.login') }}" class="hidden md:flex relative w-10 h-10 items-center justify-center rounded-full hover:bg-rose-50 group transition-colors" title="Favorit">
                <i class="far fa-heart text-lg text-gray-600 group-hover:text-rose-500 transition-colors"></i>
            </a>
            <div x-data="{ notif: false }" @mouseenter="notif = true" @mouseleave="notif = false" class="relative">
                <button wire:click="clearNotifications" @click="notif = !notif" class="hidden md:flex relative w-10 h-10 items-center justify-center rounded-full hover:bg-gray-50 transition-colors" title="Notifikasi">
                    <i class="far fa-bell text-lg text-gray-600 hover:text-primary"></i>
                    @if($notificationCount > 0)
                        <span class="icon-dot blink bg-red-500 border-2 border-white w-3 h-3 rounded-full absolute top-2 right-2"></span>
                    @endif
                </button>
                <div x-show="notif" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2" class="absolute top-full right-0 mt-3 w-72 sm:w-80 bg-white border border-gray-100 rounded-2xl shadow-2xl py-3 z-[100] overflow-hidden">
                    <div class="px-5 border-b border-gray-100 pb-2 mb-2">
                        <h4 class="text-sm font-black text-gray-800">Pembaruan & Diskon</h4>
                    </div>
                    <div class="max-h-64 overflow-y-auto px-2 space-y-1">
                        <a href="{{ route('katalog') }}" class="flex items-start gap-3 p-3 hover:bg-primary/5 rounded-xl transition-colors cursor-pointer block border border-transparent hover:border-primary/10">
                            <div class="w-10 h-10 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fas fa-bullhorn text-sm"></i>
                            </div>
                            <div>
                                <h5 class="text-sm font-bold text-gray-800 mb-0.5">Promo Spesial Hari Ini!</h5>
                                <p class="text-xs text-gray-500 line-clamp-2">Sekarang waktunya! Dapatkan diskon hingga 50% untuk kategori pilihan.</p>
                                <span class="text-[10px] text-gray-400 mt-1 block font-medium">Beberapa menit yang lalu</span>
                            </div>
                        </a>
                        <a href="{{ route('katalog') }}" class="flex items-start gap-3 p-3 hover:bg-primary/5 rounded-xl transition-colors cursor-pointer block border border-transparent hover:border-primary/10">
                            <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fas fa-box-open text-sm"></i>
                            </div>
                            <div>
                                <h5 class="text-sm font-bold text-gray-800 mb-0.5">Produk Baru Rilis</h5>
                                <p class="text-xs text-gray-500 line-clamp-2">Cek koleksi terbaru kami minggu ini. Jangan sampai kehabisan stok model idamanmu.</p>
                                <span class="text-[10px] text-gray-400 mt-1 block font-medium">1 jam yang lalu</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Hamburger Button -->
            <button 
                @click="mobileMenu = true; mobileSearch = false" 
                class="flex md:hidden relative w-10 h-10 items-center justify-center rounded-full bg-gray-50 text-gray-900 border border-gray-100 shadow-sm transition-all active:scale-95 z-50" 
                title="Menu"
            >
                <i class="fas fa-bars-staggered text-lg"></i>
            </button>
            <div class="w-px h-6 bg-gray-200 mx-1 sm:mx-2 hidden md:block"></div>
            @auth
                <div class="hidden lg:flex items-center gap-2">
                    @if(auth()->user()->hasRole('admin'))
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2.5 bg-primary text-white px-6 py-2.5 rounded-full font-bold text-sm leading-none hover:bg-primary-dark hover:-translate-y-0.5 hover:shadow-lg hover:shadow-primary/20 transition-all duration-300">
                            <i class="fas fa-shield-alt text-xs"></i>
                            <span>Dashboard Admin</span>
                        </a>
                    @else
                        <a href="{{ auth()->user()->hasVerifiedEmail() ? route('user.panel') : route('verification.notice') }}" class="inline-flex items-center gap-2.5 bg-primary text-white px-6 py-2.5 rounded-full font-bold text-sm leading-none hover:bg-primary-dark hover:-translate-y-0.5 hover:shadow-lg hover:shadow-primary/20 transition-all duration-300">
                            <i class="fas {{ auth()->user()->hasVerifiedEmail() ? 'fa-user-circle' : 'fa-envelope-open-text' }} text-xs"></i>
                            <span>{{ auth()->user()->hasVerifiedEmail() ? 'Profil Saya' : 'Verifikasi Email' }}</span>
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2.5 bg-gray-50 text-gray-700 border border-gray-200 px-5 py-2.5 rounded-full font-bold text-sm leading-none hover:bg-gray-100 hover:-translate-y-0.5 hover:shadow-md transition-all duration-300">
                            <i class="fas fa-sign-out-alt text-xs"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            @else
                <div class="hidden lg:flex items-center gap-2.5">
                    <a href="{{ route('user.register') }}" class="inline-flex items-center gap-2 bg-white text-gray-700 border border-gray-200 px-6 py-2.5 rounded-full font-bold text-sm leading-none hover:bg-gray-50 hover:text-primary hover:-translate-y-0.5 hover:shadow-sm transition-all duration-300">
                        <i class="fas fa-user-plus text-xs"></i>
                        <span>Daftar</span>
                    </a>
                    <a href="{{ route('user.login') }}" class="inline-flex items-center gap-2 bg-primary text-white px-6 py-2.5 rounded-full font-bold text-sm leading-none hover:bg-primary-dark hover:-translate-y-0.5 hover:shadow-lg hover:shadow-primary/20 transition-all duration-300">
                        <i class="fas fa-right-to-bracket text-xs"></i>
                        <span>Masuk</span>
                    </a>
                </div>
            @endauth
        </div>
    </div>

    <div x-cloak x-show="mobileSearch" class="md:hidden border-t border-gray-100 px-4 py-3 bg-white">
        <div class="relative">
            <input
                x-ref="mobileSearchInput"
                wire:model.live.debounce.300ms="search"
                wire:keydown.enter="goToSearch"
                type="text"
                placeholder="Search any products"
                class="w-full bg-gray-50 border border-gray-200 focus:border-primary focus:bg-white rounded-full py-2.5 pl-5 pr-20 outline-none transition text-sm"
            >
            <button wire:click="goToSearch" class="absolute right-10 top-1/2 -translate-y-1/2 text-gray-500 hover:text-primary" title="Cari">
                <i class="fas fa-search"></i>
            </button>
            <button @click="mobileSearch = false" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary" title="Tutup Pencarian">
                <i class="fas fa-times"></i>
            </button>
        </div>
        @if($search !== '')
            <div class="mt-2 bg-white border border-gray-100 rounded-2xl shadow-lg overflow-hidden">
                @forelse($this->searchResults as $item)
                    <a href="{{ $item['url'] }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">{{ $item['name'] }}</a>
                @empty
                    <div class="px-4 py-3 text-sm text-gray-500">Produk tidak ditemukan.</div>
                @endforelse
            </div>
        @endif
    </div>

    <div
        :class="isDesktopMenuHidden
            ? 'md:max-h-0 md:py-0 md:opacity-0 md:pointer-events-none md:border-transparent'
            : 'md:max-h-24 md:py-3.5 md:opacity-100'"
        class="hidden md:flex justify-start max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 overflow-x-auto items-center gap-3 lg:gap-5 text-sm font-bold text-gray-600 border-t border-gray-100/50 hide-scrollbar transition-all duration-300 relative z-40 bg-white/40"
    >
        @foreach($menus as $menu)
            <a href="{{ $menu['url'] }}" class="{{ $menu['active'] ? 'text-primary' : 'text-gray-500 hover:text-primary' }} px-3 py-2 whitespace-nowrap flex items-center gap-2 group transition-all duration-300">
                <i class="fas {{ $menu['icon'] }} {{ $menu['active'] ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }} transition-colors"></i>
                <span class="font-bold tracking-wide">
                    {{ $menu['label'] }}
                </span>
            </a>
        @endforeach
    </header>

    <!-- Mobile Sidebar Menu -->
    <div 
        x-cloak 
        x-show="mobileMenu" 
        class="fixed inset-0 z-[1000] md:hidden"
        role="dialog"
        aria-modal="true"
    >
        <!-- Backdrop Overlay -->
        <div 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="mobileMenu = false" 
            class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"
        ></div>

        <!-- Sidebar Panel -->
        <aside 
            x-transition:enter="transition ease-out duration-300 transform" 
            x-transition:enter-start="-translate-x-full" 
            x-transition:enter-end="translate-x-0" 
            x-transition:leave="transition ease-in duration-200 transform" 
            x-transition:leave-start="translate-x-0" 
            x-transition:leave-end="-translate-x-full" 
            class="fixed inset-y-0 left-0 w-[85%] max-w-[320px] bg-white shadow-2xl flex flex-col z-[1001] h-full"
        >
            <!-- Sidebar Header -->
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-white shrink-0">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 text-xl font-black text-gray-900 tracking-tight">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-primary to-primary-dark text-white flex items-center justify-center shadow-md">
                        <i class="fas fa-cube text-xs"></i>
                    </div>
                    <span>Kataloque</span>
                </a>
                <button @click="mobileMenu = false" class="w-9 h-9 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 hover:text-rose-500 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Sidebar Search -->
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                <div class="relative">
                    <input 
                        wire:model.live.debounce.300ms="search" 
                        wire:keydown.enter="goToSearch"
                        type="search" 
                        placeholder="Cari produk apa saja..." 
                        class="w-full bg-white border border-gray-200 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-2xl py-2.5 pl-10 pr-4 text-xs font-semibold outline-none transition-all shadow-sm"
                    >
                    <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fas fa-search text-[11px]"></i>
                    </div>
                </div>
            </div>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto overscroll-contain bg-white">
                <div class="py-4">
                    <!-- Main Navigation -->
                    <div class="px-4 mb-6">
                        <h4 class="px-4 py-2 text-[10px] font-black uppercase text-gray-400 tracking-widest mb-2">Menu Utama</h4>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($menus as $menu)
                                <a href="{{ $menu['url'] }}" class="p-3 rounded-2xl flex flex-col items-center justify-center text-center gap-2 transition-all {{ $menu['active'] ? 'bg-primary text-white font-bold shadow-lg shadow-primary/20' : 'bg-gray-50 text-gray-600 hover:bg-gray-100' }}">
                                    <i class="fas {{ $menu['icon'] }} text-base {{ $menu['active'] ? 'text-white' : 'text-primary/60' }}"></i>
                                    <span class="text-[11px] font-bold leading-tight">{{ $menu['label'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Categories Section -->
                    <div class="px-4 mb-6">
                        <h4 class="px-4 py-2 text-[10px] font-black uppercase text-gray-400 tracking-widest mb-2">Kategori Pilihan</h4>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach(array_slice($categories, 0, 7) as $category)
                                <a href="{{ route('kategori.detail', $category['slug']) }}" class="p-3 rounded-2xl bg-gray-50 flex flex-col items-center justify-center text-center gap-2 text-gray-600 hover:bg-primary/5 hover:text-primary transition-all group">
                                    <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center border border-gray-100 group-hover:border-primary/20 transition-all">
                                        <i class="fas {{ $category['icon'] ?? 'fa-tag' }} text-xs {{ $category['text_color'] ?? 'text-gray-400' }} group-hover:text-primary"></i>
                                    </div>
                                    <span class="text-[11px] font-bold leading-tight line-clamp-1">{{ $category['name'] }}</span>
                                </a>
                            @endforeach
                            
                            <a href="{{ route('kategori') }}" class="p-3 rounded-2xl bg-primary/5 text-primary flex flex-col items-center justify-center text-center gap-2 font-black transition-all hover:bg-primary hover:text-white">
                                <i class="fas fa-th-large text-base"></i>
                                <span class="text-[11px]">Semua</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fixed Footer Actions -->
            <div class="p-4 border-t border-gray-100 bg-white shrink-0">
                @auth
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center overflow-hidden border border-gray-100 shrink-0">
                                @if(auth()->user()->avatar)
                                    <img src="{{ auth()->user()->avatar }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-primary/10 text-primary flex items-center justify-center font-bold">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-[11px] font-black text-gray-900 truncate leading-tight">{{ auth()->user()->name }}</h4>
                                <p class="text-[9px] text-gray-400 truncate">{{ auth()->user()->email }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            @if(auth()->user()->hasRole('admin'))
                                <a href="{{ route('admin.dashboard') }}" class="w-10 h-10 flex items-center justify-center bg-gray-900 text-white rounded-xl shadow-sm" title="Dashboard Admin">
                                    <i class="fas fa-shield-alt text-xs"></i>
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-10 h-10 flex items-center justify-center bg-rose-50 text-rose-500 rounded-xl border border-rose-100" title="Keluar Akun">
                                    <i class="fas fa-sign-out-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('user.login') }}" class="flex items-center justify-center bg-primary text-white py-3.5 rounded-xl font-black text-xs shadow-lg shadow-primary/20">
                            Masuk
                        </a>
                        <a href="{{ route('user.register') }}" class="flex items-center justify-center bg-white text-gray-700 border border-gray-200 py-3.5 rounded-xl font-bold text-xs">
                            Daftar
                        </a>
                    </div>
                @endauth
            </div>
        </aside>
    </div>
</div>
