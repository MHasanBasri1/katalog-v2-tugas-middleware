<div x-data="{
        lastScrollY: 0,
        isTopBarHidden: false,
        _ticking: false,
        init() {
            this.lastScrollY = window.scrollY;
            window.addEventListener('scroll', () => {
                if (this._ticking) return;
                this._ticking = true;
                requestAnimationFrame(() => {
                    if (window.innerWidth < 768) {
                        this.isTopBarHidden = false;
                    } else {
                        const currentY = window.scrollY;
                        this.isTopBarHidden = currentY > 8 && currentY > this.lastScrollY;
                        this.lastScrollY = currentY;
                    }
                    this._ticking = false;
                });
            }, { passive: true });
        }
    }" class="relative">
    <div class="fixed top-0 left-0 right-0 z-[100] transition-transform duration-300"
        :class="{ 'is-top-bar-hidden': isTopBarHidden }">

        {{-- === ROW 1: TOP BAR (Desktop Only) === --}}
        <div class="hidden md:block bg-gray-50 border-b border-gray-200" style="height: 33px;">
            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex justify-between items-center text-[11px] font-bold text-gray-500 tracking-tight">
                <div class="flex items-center gap-5">
                    <a href="#" class="hover:text-primary transition-colors flex items-center gap-1.5"><i
                            class="fas fa-mobile-alt text-[10px]"></i> Download App</a>
                    <a href="#" class="hover:text-primary transition-colors flex items-center gap-1.5"><i
                            class="fas fa-headset text-[10px]"></i> Kataloque Care</a>
                </div>
                <div class="flex items-center gap-5">
                    @foreach($topMenus as $menu)
                        <a href="{{ $menu['url'] }}" class="hover:text-primary transition-colors">{{ $menu['label'] }}</a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- === ROW 2: MAIN HEADER === --}}
        {{-- === MAIN CONTENT WRAPPER (ROW 2 & 3) === --}}
        <div class="bg-white shadow-sm border-b border-gray-100">
            <header class="md:h-[64px] flex items-center">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">

                {{-- DESKTOP HEADER (Visible on md and up) --}}
                <div class="hidden md:flex items-center gap-6 w-full">
                    {{-- LOGO --}}
                    <a href="/" class="flex items-center shrink-0" aria-label="Kataloque Beranda">
                        <img src="https://www.static-src.com/frontend/static/img/logo-blibli-blue.0f340eba.svg"
                            alt="{{ $setting->shop_name ?? 'Kataloque' }} Logo" class="h-9 w-auto">
                    </a>

                    {{-- SEARCH --}}
                    <div class="flex-1 relative z-50">
                        <div class="relative w-full">
                            <input id="desktopSearchInput" wire:model.live.debounce.300ms="search"
                                wire:keydown.enter="goToSearch" type="search"
                                placeholder="Cari produk atau artikel..."
                                class="w-full bg-gray-50 border border-gray-200 focus:border-primary focus:ring-4 focus:ring-primary/5 rounded-lg outline-none transition-all text-sm placeholder:text-gray-400 font-medium h-10 px-4 pr-20"
                                aria-label="Cari">
                            
                            {{-- Clear Search Button (Desktop) --}}
                            @if($search !== '')
                                <button wire:click="clearSearch"
                                    class="absolute right-12 top-0 bottom-0 px-3 text-gray-400 hover:text-rose-500 transition-colors"
                                    aria-label="Hapus Pencarian">
                                    <i class="fas fa-times-circle"></i>
                                </button>
                            @endif

                            <button wire:click="goToSearch"
                                class="absolute right-0 top-0 bottom-0 bg-primary text-white rounded-r-lg hover:bg-primary-dark transition-all flex items-center justify-center w-11"
                                aria-label="Cari">
                                <i class="fas fa-search text-sm" aria-hidden="true"></i>
                            </button>
                        </div>

                        {{-- Search Results Dropdown --}}
                        @if($search !== '')
                            <div
                                class="absolute left-0 right-0 top-full mt-2 bg-white border border-gray-100 rounded-xl shadow-xl z-40 overflow-hidden">
                                <div class="p-2 space-y-0.5 max-h-72 overflow-y-auto">
                                    @forelse($this->searchResults as $item)
                                        <a href="{{ $item['url'] }}"
                                            class="flex items-center justify-between px-4 py-2.5 rounded-lg text-sm text-gray-700 hover:bg-primary/5 hover:text-primary transition-colors group">
                                            <span class="font-medium truncate pr-4">{{ $item['name'] }}</span>
                                            <span class="text-[9px] font-black uppercase tracking-widest px-1.5 py-0.5 rounded {{ $item['type'] === 'Produk' ? 'bg-primary/10 text-primary' : 'bg-rose-500/10 text-rose-500' }}">
                                                {{ $item['type'] }}
                                            </span>
                                        </a>
                                    @empty
                                        <div class="px-4 py-3 text-sm text-gray-500 text-center">Hasil tidak ditemukan.</div>
                                    @endforelse
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- ACTIONS --}}
                    <div class="flex items-center gap-3 shrink-0">
                        {{-- FAVORIT DROPDOWN --}}
                        <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false"
                            class="relative group">
                            <a href="{{ auth()->check() ? url('/dashboard?tab=favorit') : route('user.login') }}"
                                class="flex items-center justify-center w-10 h-10 rounded-xl text-gray-400 hover:text-rose-500 hover:bg-rose-50 transition-all duration-300 relative">
                                <i class="far fa-heart text-xl"></i>
                                @if($favoriteCount > 0)
                                    <span
                                        class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-rose-500 text-[10px] font-bold text-white shadow-sm ring-2 ring-white">
                                        {{ $favoriteCount }}
                                    </span>
                                @endif
                            </a>

                            {{-- Favorite Popup --}}
                            <div x-show="open" x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                                class="absolute right-0 top-full mt-1 w-72 bg-white border border-gray-100 rounded-2xl shadow-2xl py-3 z-[110] overflow-hidden"
                                x-cloak>

                                <div class="px-5 py-2 mb-2 border-b border-gray-50 flex items-center justify-between">
                                    <span class="text-xs font-black text-gray-800 uppercase tracking-wider">Favorit
                                        Saya</span>
                                    <a href="{{ url('/dashboard?tab=favorit') }}"
                                        class="text-[10px] font-bold text-primary hover:underline">Lihat Semua</a>
                                </div>

                                @if(auth()->check())
                                    <div class="px-2 space-y-1">
                                        @forelse($this->favoriteItems as $item)
                                            <div class="flex items-center justify-between group/fav px-5 py-2.5 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0">
                                                <a href="{{ $item['url'] }}" class="flex-1 min-w-0 pr-4">
                                                    <div class="text-[12px] font-bold text-gray-800 truncate mb-0.5 group-hover/fav:text-primary transition-colors">
                                                        {{ $item['name'] }}
                                                    </div>
                                                    <div class="text-[11px] font-black text-primary">
                                                        Rp{{ number_format($item['price'], 0, ',', '.') }}</div>
                                                </a>
                                                <button 
                                                    wire:click.stop="removeFromFavorite({{ $item['id'] }})"
                                                    class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-300 hover:text-white hover:bg-rose-500 opacity-0 group-hover/fav:opacity-100 transition-all duration-200"
                                                    title="Hapus dari favorit"
                                                >
                                                    <i class="fas fa-times text-[10px]"></i>
                                                </button>
                                            </div>
                                        @empty
                                            <div class="px-5 py-8 text-center">
                                                <div
                                                    class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                                    <i class="far fa-heart text-gray-300 text-lg"></i>
                                                </div>
                                                <p class="text-[11px] font-medium text-gray-400">Belum ada produk favorit.</p>
                                            </div>
                                        @endforelse
                                    </div>
                                @else
                                    <div class="px-5 py-6 text-center">
                                        <div
                                            class="w-12 h-12 bg-rose-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <i class="fas fa-lock text-rose-300 text-lg"></i>
                                        </div>
                                        <p class="text-[11px] font-bold text-gray-500 mb-4 px-2">Silakan masuk untuk melihat
                                            daftar produk favorit Anda.</p>
                                        <a href="{{ route('user.login') }}"
                                            class="inline-flex items-center justify-center px-4 py-2 bg-primary text-white text-[11px] font-black rounded-lg hover:bg-primary-dark transition-all shadow-md shadow-primary/20">Masuk
                                            Sekarang</a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false"
                            class="relative group">
                            <button
                                class="flex items-center justify-center w-10 h-10 rounded-xl text-gray-400 hover:text-primary hover:bg-primary/5 transition-all duration-300 relative">
                                <i class="far fa-bell text-xl"></i>
                                <span
                                    class="absolute top-2 right-2.5 flex h-2 w-2 rounded-full bg-primary ring-2 ring-white"></span>
                            </button>

                            {{-- Notification Popup --}}
                            <div x-show="open" x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                                class="absolute right-0 top-full mt-1 w-72 bg-white border border-gray-100 rounded-2xl shadow-2xl py-3 z-[110] overflow-hidden"
                                x-cloak>

                                <div class="px-5 py-2 mb-2 border-b border-gray-50 flex items-center justify-between">
                                    <span class="text-xs font-black text-gray-800 uppercase tracking-wider">Notifikasi</span>
                                    <span class="text-[10px] font-bold text-gray-400">Terbaru</span>
                                </div>

                                @if(auth()->check())
                                    <div class="px-2 space-y-1">
                                        @forelse($this->notificationItems as $item)
                                            <a href="{{ $item['url'] }}"
                                                class="block px-4 py-3 hover:bg-gray-50 rounded-xl transition-colors border-b border-gray-50 last:border-0">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span
                                                        class="text-[9px] font-black bg-primary/10 text-primary px-1.5 py-0.5 rounded uppercase">Update</span>
                                                    <span class="text-[9px] font-medium text-gray-400">{{ $item['time'] }}</span>
                                                </div>
                                                <div class="text-[12px] font-bold text-gray-800 truncate">{{ $item['name'] }}</div>
                                                <div class="text-[11px] font-medium text-gray-500">Admin baru saja memperbarui produk ini.</div>
                                            </a>
                                        @empty
                                            <div class="px-5 py-8 text-center">
                                                <p class="text-[11px] font-medium text-gray-400">Belum ada notifikasi baru.</p>
                                            </div>
                                        @endforelse
                                    </div>
                                @else
                                    <div class="px-5 py-6 text-center">
                                        <div
                                            class="w-12 h-12 bg-primary/5 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <i class="fas fa-bullhorn text-primary text-lg"></i>
                                        </div>
                                        <p class="text-[11px] font-bold text-gray-500 mb-4 px-2">Lihat update produk terbaru dan promo menarik setiap hari!</p>
                                        <a href="{{ route('katalog') }}"
                                            class="inline-flex items-center justify-center px-4 py-2 bg-primary text-white text-[11px] font-black rounded-lg hover:bg-primary-dark transition-all shadow-md shadow-primary/20">Cek Produk Baru</a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="w-px h-6 bg-gray-200 mx-1"></div>

                        {{-- USER PROFILE DROPWDOWN --}}
                        @if(auth()->check())
                            @php
                                $isAdmin = auth()->user()->hasRole('admin');
                                $panelRoute = $isAdmin ? route('admin.dashboard') : route('user.panel');
                                $userAvatar = auth()->user()->avatar_url;
                            @endphp
                            <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false"
                                class="relative">
                                <button @click="open = !open"
                                    class="flex items-center gap-3 group focus:outline-none h-10 px-2 rounded-xl hover:bg-gray-50 transition-all duration-300">
                                    <div
                                        class="w-9 h-9 rounded-full {{ $isAdmin ? 'bg-rose-500/10 border-rose-500/20' : 'bg-primary/10 border-primary/20' }} flex items-center justify-center border-2 overflow-hidden transition-all duration-300 group-hover:scale-105 group-hover:shadow-md">
                                        @if($userAvatar)
                                            <img src="{{ $userAvatar }}" alt="Profile" class="w-full h-full object-cover">
                                        @else
                                            <i
                                                class="fas {{ $isAdmin ? 'fa-user-shield text-rose-600' : 'fa-user text-primary' }} text-xs"></i>
                                        @endif
                                    </div>
                                    <div class="hidden lg:flex flex-col items-start translate-y-[1px]">
                                        <div class="flex items-center gap-1.5">
                                            <span
                                                class="text-[12px] font-black text-gray-700 group-hover:text-primary truncate max-w-[90px] transition-colors">{{ auth()->user()->username ?: auth()->user()->name }}</span>
                                            <i class="fas text-[9px] text-gray-400 group-hover:text-primary transition-all transition-colors"
                                                :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                                        </div>
                                        @if($isAdmin)
                                            <span
                                                class="text-[8px] font-black text-rose-600 uppercase tracking-widest leading-none mt-0.5">ADMIN
                                                PANEL</span>
                                        @else
                                            <span
                                                class="text-[8px] font-black text-blue-600 uppercase tracking-widest leading-none mt-0.5">MEMBER</span>
                                        @endif
                                    </div>
                                </button>

                                {{-- Dropdown Popup --}}
                                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                    x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                                    class="absolute right-0 top-full mt-1 w-56 bg-white border border-gray-100 rounded-2xl shadow-2xl py-2 z-[110] overflow-hidden"
                                    x-cloak>
                                    <div class="px-5 py-3 mb-1 border-b border-gray-50 flex flex-col gap-0.5 bg-gray-50/50">
                                        <span
                                            class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Akun
                                            Terverifikasi</span>
                                        <span
                                            class="text-[11px] font-bold text-gray-800 truncate">{{ auth()->user()->email }}</span>
                                    </div>
                                    <div class="p-1.5 space-y-0.5">
                                        <a href="{{ $panelRoute }}"
                                            class="flex items-center gap-3 px-4 py-2.5 text-[13px] font-bold text-gray-700 hover:bg-primary/5 hover:text-primary rounded-xl transition-all">
                                            <i class="fas fa-user-circle text-gray-400 w-4 pl-0.5"></i> Dashboard
                                        </a>
                                        @if($isAdmin)
                                            <a href="{{ route('admin.dashboard') }}"
                                                class="flex items-center gap-3 px-4 py-2.5 text-[13px] font-bold text-gray-700 hover:bg-rose-50 hover:text-rose-600 rounded-xl transition-all border-b border-gray-50">
                                                <i class="fas fa-shield-halved text-gray-400 w-4"></i> Panel Admin
                                            </a>
                                        @endif
                                        <div class="pt-1.5 mt-1.5 border-t border-gray-50">
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit"
                                                    class="w-full flex items-center gap-3 px-4 py-2.5 text-[13px] font-bold text-rose-600 hover:bg-rose-50 rounded-xl transition-all text-left">
                                                    <i class="fas fa-sign-out-alt w-4 text-[11px] pl-0.5"></i> Keluar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center gap-2">
                                <a href="{{ route('user.login') }}"
                                    class="h-10 px-6 text-sm font-bold text-primary border-2 border-primary rounded-xl hover:bg-primary/5 transition-all flex items-center justify-center">Masuk</a>
                                <a href="{{ route('user.register') }}"
                                    class="h-10 px-6 text-sm font-bold text-white bg-primary border-2 border-primary rounded-xl hover:bg-primary-dark transition-all flex items-center justify-center">Daftar</a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- MOBILE HEADER (Visible on mobile only) --}}
                <div class="flex md:hidden flex-col gap-2 w-full py-3">
                    {{-- COMPACT SINGLE ROW: Search | Notification --}}
                    <div class="flex items-center gap-3 w-full">
                        {{-- SEARCH (Flexible) --}}
                        <div class="flex-1 relative">
                            <input id="mobileSearchInput" wire:model.live.debounce.300ms="search"
                                wire:keydown.enter="goToSearch" type="text"
                                placeholder="Cari produk atau artikel..."
                                class="w-full bg-gray-50 border border-gray-200 focus:border-primary rounded-xl outline-none transition text-[13px] font-semibold text-gray-700 placeholder:text-gray-400 h-10 px-4 pr-16"
                                aria-label="Cari">

                            {{-- Clear Search Button --}}
                            @if($search !== '')
                                <button wire:click="clearSearch"
                                    class="absolute right-9 top-0 bottom-0 px-1.5 text-gray-400 hover:text-rose-500 transition-colors"
                                    aria-label="Hapus Pencarian">
                                    <i class="fas fa-times-circle text-[11px]"></i>
                                </button>
                            @endif

                            <button wire:click="goToSearch"
                                class="absolute right-0 top-0 bottom-0 w-9 bg-primary text-white rounded-r-xl flex items-center justify-center"
                                aria-label="Cari">
                                <i class="fas fa-search text-[10px]" aria-hidden="true"></i>
                            </button>

                            {{-- Mobile Search Dropdown --}}
                            @if($search !== '')
                                <div class="absolute left-0 right-0 top-full mt-2 bg-white border border-gray-100 rounded-xl shadow-xl z-[120] overflow-hidden">
                                    <div class="p-1 space-y-0.5 max-h-60 overflow-y-auto">
                                        @forelse($this->searchResults as $item)
                                            <a href="{{ $item['url'] }}"
                                                class="flex items-center justify-between px-3 py-2 rounded-lg text-xs text-gray-700 hover:bg-primary/5 hover:text-primary transition-colors group">
                                                <span class="font-medium truncate pr-2">{{ $item['name'] }}</span>
                                                <span class="text-[8px] font-black uppercase tracking-tight px-1 py-0.5 rounded {{ $item['type'] === 'Produk' ? 'bg-primary/10 text-primary' : 'bg-rose-500/10 text-rose-500' }}">
                                                    {{ $item['type'] }}
                                                </span>
                                            </a>
                                        @empty
                                            <div class="px-3 py-3 text-xs text-gray-500 text-center">Hasil tidak ditemukan.</div>
                                        @endforelse
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- ACTIONS --}}
                        <div class="flex items-center shrink-0">
                            {{-- Notification --}}
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" @click.away="open = false"
                                    class="w-10 h-10 flex items-center justify-center text-gray-400 border border-gray-100 rounded-xl relative focus:outline-none bg-gray-50/20"
                                    aria-label="Notifikasi">
                                    <i class="far fa-bell text-xl"></i>
                                    <span class="absolute top-3 right-3 h-1.5 w-1.5 bg-primary rounded-full ring-2 ring-white"></span>
                                </button>

                                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                    class="absolute right-0 mt-2 w-64 bg-white border border-gray-100 rounded-2xl shadow-2xl py-3 z-[110] overflow-hidden"
                                    x-cloak>
                                    <div class="px-4 py-2 mb-2 border-b border-gray-50 flex items-center justify-between">
                                        <span class="text-[10px] font-black text-gray-800 uppercase tracking-wider">Notifikasi</span>
                                    </div>
                                    <div class="px-1 space-y-0.5 max-h-64 overflow-y-auto">
                                        @forelse(collect($this->notificationItems)->take(3) as $item)
                                            <a href="{{ $item['url'] }}" class="block px-4 py-2 hover:bg-gray-50 rounded-lg text-[11px]">
                                                <div class="font-bold text-gray-800 truncate">{{ $item['name'] }}</div>
                                                <div class="text-gray-400 text-[9px]">{{ $item['time'] }}</div>
                                            </a>
                                        @empty
                                            <div class="px-4 py-4 text-center text-[10px] text-gray-400">Tidak ada info baru.</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Row 2: Trending (Optional/Compact) --}}
                    <div class="relative w-full overflow-hidden h-6">
                        <div class="swiper trending-swiper !overflow-visible">
                            <div class="swiper-wrapper !ease-linear">
                                @foreach($trendingKeywords as $item)
                                    <div class="swiper-slide !w-auto">
                                        <a href="{{ $item['url'] ?: route('katalog', ['q' => $item['keyword']]) }}"
                                            class="block text-[9px] font-bold text-gray-400 px-2.5 py-1 bg-gray-50 border border-gray-100 rounded-lg whitespace-nowrap transition-colors hover:text-primary leading-none">{{ $item['keyword'] }}</a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            </header>
 
            {{-- === ROW 3: SUB HEADER (Desktop Only) === --}}
            <div class="hidden md:block h-[38px] border-t border-gray-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center">
                    {{-- KATEGORI DROPDOWN --}}
                    <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false"
                        class="relative h-full flex items-center">
                        <button @click="open = !open"
                            class="flex items-center gap-2 pr-4 text-[13px] font-bold text-gray-600 hover:text-primary transition-all group"
                            aria-haspopup="true" :aria-expanded="open">
                            <i class="fas fa-th-large text-gray-400 group-hover:text-primary transition-colors"></i>
                            <span>Semua Kategori</span>
                            <i
                                class="fas fa-chevron-down text-[10px] opacity-40 group-hover:opacity-100 transition-all"></i>
                        </button>
    
                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-2"
                            class="absolute top-full left-0 mt-0 w-64 bg-white border border-gray-100 rounded-b-xl shadow-2xl py-3 z-[60]"
                            x-cloak>
                            <div class="px-4 pb-2 mb-2 border-b border-gray-50">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Kategori
                                    Populer</span>
                            </div>
                            @foreach(collect($categories)->take(8) as $category)
                                @if(!empty($category['slug']))
                                    <a href="{{ route('kategori.detail', $category['slug']) }}"
                                        class="flex items-center justify-between px-5 py-2.5 hover:bg-gray-50 hover:text-primary transition-colors text-[13px] text-gray-700 font-semibold group">
                                        {{ $category['name'] }}
                                        <i
                                            class="fas fa-chevron-right text-[10px] opacity-0 group-hover:opacity-100 transition-all"></i>
                                    </a>
                                @endif
                            @endforeach
                            <div class="mt-2 pt-2 border-t border-gray-50 px-2">
                                <a href="/kategori" class="flex items-center justify-center py-2 text-[12px] font-black text-primary bg-primary/5 rounded-lg hover:bg-primary/10 transition-all">Lihat Semua Kategori</a>
                            </div>
                        </div>
                    </div>
    
                    {{-- TRENDING LINKS / KEYWORDS --}}
                    <div class="flex items-center gap-4 ml-6 text-[12px] font-medium text-gray-500 overflow-hidden">
                        <span class="hidden lg:block text-gray-300">|</span>
                        @foreach($trendingKeywords as $item)
                            <a href="{{ $item['url'] ?: route('katalog', ['q' => $item['keyword']]) }}"
                                class="hover:text-primary transition-colors whitespace-nowrap">{{ $item['keyword'] }}</a>
                            @if(!$loop->last)
                                <span class="text-gray-200 font-light hidden lg:block">|</span>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

            </div>
        </div>
    </div>
</div>