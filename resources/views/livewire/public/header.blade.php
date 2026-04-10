<div
    x-data="{
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
    }"
    class="relative"
>
    <div class="fixed top-0 left-0 right-0 z-[100] transition-transform duration-300" :class="isTopBarHidden ? '-translate-y-[33px]' : 'translate-y-0'">

        {{-- === ROW 1: TOP BAR (Desktop Only) === --}}
        <div class="hidden md:block bg-gray-50 border-b border-gray-200" style="height: 33px;">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex justify-between items-center text-[11px] font-bold text-gray-500 tracking-tight">
                <div class="flex items-center gap-5">
                    <a href="#" class="hover:text-primary transition-colors flex items-center gap-1.5"><i class="fas fa-mobile-alt text-[10px]"></i> Download App</a>
                    <a href="#" class="hover:text-primary transition-colors flex items-center gap-1.5"><i class="fas fa-headset text-[10px]"></i> Kataloque Care</a>
                </div>
                <div class="flex items-center gap-5">
                    <a href="#" class="hover:text-primary transition-colors">Tentang Kami</a>
                    <a href="{{ url('/blog') }}" class="hover:text-primary transition-colors">Blog & Edukasi</a>
                    <a href="#" class="hover:text-primary transition-colors">Cara Order</a>
                </div>
            </div>
        </div>

        {{-- === ROW 2: MAIN HEADER === --}}
        <header class="bg-white py-2 md:h-[72px] flex items-center border-b border-gray-100 md:border-none">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                
                {{-- DESKTOP HEADER (Visible on md and up) --}}
                <div class="hidden md:flex items-center gap-6 w-full">
                    {{-- LOGO --}}
                    <a href="{{ route('home') }}" class="flex items-center shrink-0" aria-label="Kataloque Beranda">
                        <img src="https://www.static-src.com/frontend/static/img/logo-blibli-blue.0f340eba.svg" alt="Logo" class="h-9 w-auto">
                    </a>

                    {{-- SEARCH --}}
                    <div class="flex-1 relative z-50">
                        <div class="relative w-full">
                            <input id="desktopSearchInput" wire:model.live.debounce.300ms="search" wire:keydown.enter="goToSearch" type="search" placeholder="Cari brand, produk, atau seller"
                                class="w-full bg-gray-50 border border-gray-200 focus:border-primary focus:ring-4 focus:ring-primary/5 rounded-lg outline-none transition-all text-sm placeholder:text-gray-400 font-medium h-10 px-4 pr-12" aria-label="Cari Produk">
                            <button wire:click="goToSearch" class="absolute right-0 top-0 bottom-0 bg-primary text-white rounded-r-lg hover:bg-primary-dark transition-all flex items-center justify-center w-11" aria-label="Cari">
                                <i class="fas fa-search text-sm" aria-hidden="true"></i>
                            </button>
                        </div>

                        {{-- Search Results Dropdown --}}
                        @if($search !== '')
                            <div class="absolute left-0 right-0 top-full mt-2 bg-white border border-gray-100 rounded-xl shadow-xl z-40 overflow-hidden">
                                <div class="p-2 space-y-0.5 max-h-72 overflow-y-auto">
                                    @forelse($this->searchResults as $item)
                                        <a href="{{ $item['url'] }}" class="block px-4 py-2.5 rounded-lg text-sm text-gray-700 hover:bg-primary/5 hover:text-primary font-medium transition-colors">{{ $item['name'] }}</a>
                                    @empty
                                        <div class="px-4 py-3 text-sm text-gray-500 text-center">Produk tidak ditemukan.</div>
                                    @endforelse
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- ACTIONS --}}
                    <div class="flex items-center gap-4 shrink-0">
                        <a href="{{ auth()->check() ? url('/profil-saya?tab=favorit') : route('user.login') }}" class="text-gray-400 hover:text-primary transition-colors">
                            <i class="far fa-heart text-xl"></i>
                        </a>
                        <div class="w-px h-6 bg-gray-200"></div>
                        @if(auth()->check())
                            @php
                                $isAdmin = auth()->user()->hasRole('admin');
                                $panelRoute = $isAdmin ? route('admin.dashboard') : route('user.panel');
                            @endphp
                            <a href="{{ $panelRoute }}" class="flex items-center gap-2 group">
                                <div class="w-8 h-8 rounded-full {{ $isAdmin ? 'bg-rose-500/10 border-rose-500/20' : 'bg-primary/10 border-primary/20' }} flex items-center justify-center border overflow-hidden">
                                    <i class="fas {{ $isAdmin ? 'fa-user-shield text-rose-600' : 'fa-user text-primary' }} text-xs"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[12px] font-black text-gray-700 group-hover:text-primary truncate max-w-[80px]">{{ auth()->user()->username ?? auth()->user()->name }}</span>
                                    @if($isAdmin)
                                        <span class="text-[9px] font-bold text-rose-600 uppercase tracking-tighter">Admin Panel</span>
                                    @endif
                                </div>
                            </a>
                        @else
                            <a href="{{ route('user.login') }}" class="px-8 py-2.5 text-sm font-bold text-primary border-2 border-primary rounded-xl hover:bg-primary/5 transition-all flex items-center justify-center">Masuk</a>
                            <a href="{{ route('user.register') }}" class="px-8 py-2.5 text-sm font-bold text-white bg-primary border-2 border-primary rounded-xl hover:bg-primary-dark transition-all flex items-center justify-center">Daftar</a>
                        @endif
                    </div>
                </div>

                {{-- MOBILE HEADER (Visible on mobile only) --}}
                <div class="flex md:hidden flex-col gap-1.5 w-full">
                    {{-- Row 1: Search & Login --}}
                    <div class="flex items-center gap-2 w-full">
                        <div class="relative flex-1">
                            <input id="mobileSearchInput" wire:model.live.debounce.300ms="search" wire:keydown.enter="goToSearch" type="text" placeholder="Cari brand, produk, atau seller"
                                class="w-full bg-gray-50 border border-gray-200 focus:border-primary rounded-lg outline-none transition text-[13px] font-semibold text-gray-700 placeholder:text-gray-400 h-9 px-3 pr-10" aria-label="Cari Produk">
                            <button wire:click="goToSearch" class="absolute right-0 top-0 bottom-0 w-9 bg-primary text-white rounded-r-lg flex items-center justify-center" aria-label="Cari">
                                <i class="fas fa-search text-[11px]" aria-hidden="true"></i>
                            </button>
                        </div>
                        
                        @if(!auth()->check())
                            <a href="{{ route('user.login') }}" class="px-5 h-9 flex items-center justify-center text-[11px] font-black text-white bg-primary border-2 border-primary rounded-lg whitespace-nowrap gap-1.5 shadow-lg shadow-primary/20">
                                <i class="fas fa-sign-in-alt text-[10px]"></i>
                                <span>Masuk</span>
                            </a>
                        @else
                            @php
                                $isAdmin = auth()->user()->hasRole('admin');
                                $panelRoute = $isAdmin ? route('admin.dashboard') : route('user.panel');
                            @endphp
                            <a href="{{ $panelRoute }}" class="w-9 h-9 flex items-center justify-center {{ $isAdmin ? 'text-rose-600 border-rose-500/20 bg-rose-50' : 'text-primary border-primary/20 bg-primary/5' }} border-2 rounded-lg shrink-0">
                                <i class="fas {{ $isAdmin ? 'fa-user-shield' : 'fa-user-circle' }} text-lg"></i>
                            </a>
                        @endif
                    </div>

                    {{-- Row 2: Trending --}}
                    <div class="relative w-full overflow-hidden h-7">
                        <div class="swiper trending-swiper !overflow-visible">
                            <div class="swiper-wrapper !ease-linear">
                                @php
                                    $trendingKeywords = ['iPhone 15 Pro', 'Samsung S24 Ultra', 'MacBook Pro M3', 'Sony WH-1000XM5', 'Logitech G Pro', 'iPad Pro M2', 'Sony Alpha A7', 'DJI Mini 4 Pro'];
                                @endphp
                                @foreach($trendingKeywords as $keyword)
                                    <div class="swiper-slide !w-auto">
                                        <a href="{{ route('katalog', ['q' => $keyword]) }}" class="block text-[10px] font-bold text-gray-500 px-2.5 py-1 bg-gray-50 border border-gray-100 rounded-md whitespace-nowrap transition-colors hover:text-primary">{{ $keyword }}</a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </header>

        {{-- === ROW 3: SUB HEADER (Desktop Only) === --}}
        <div class="hidden md:block bg-white border-b border-gray-100" style="height: 42px;">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center">
                
                {{-- KATEGORI DROPDOWN --}}
                <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative h-full flex items-center">
                    <button @click="open = !open" class="flex items-center gap-2 pr-4 text-[13px] font-bold text-gray-600 hover:text-primary transition-all group" aria-haspopup="true" :aria-expanded="open">
                        <i class="fas fa-th-large text-gray-400 group-hover:text-primary transition-colors"></i>
                        <span>Semua Kategori</span>
                        <i class="fas fa-chevron-down text-[10px] opacity-40 group-hover:opacity-100 transition-all"></i>
                    </button>

                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2" class="absolute top-full left-0 mt-0 w-64 bg-white border border-gray-100 rounded-b-xl shadow-2xl py-3 z-[60]" x-cloak>
                        <div class="px-4 pb-2 mb-2 border-b border-gray-50">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Kategori Populer</span>
                        </div>
                        @foreach(collect($categories)->take(8) as $category)
                            @if(!empty($category['slug']))
                                <a href="{{ route('kategori.detail', $category['slug']) }}" class="flex items-center justify-between px-5 py-2.5 hover:bg-gray-50 hover:text-primary transition-colors text-[13px] text-gray-700 font-semibold group">
                                    {{ $category['name'] }}
                                    <i class="fas fa-chevron-right text-[10px] opacity-0 group-hover:opacity-100 transition-all"></i>
                                </a>
                            @endif
                        @endforeach
                        <div class="mt-2 pt-2 border-t border-gray-50 px-2">
                            <a href="{{ route('kategori') }}" class="flex items-center justify-center py-2 text-[12px] font-black text-primary bg-primary/5 rounded-lg hover:bg-primary/10 transition-all">Lihat Semua Kategori</a>
                        </div>
                    </div>
                </div>

                {{-- TRENDING LINKS / KEYWORDS --}}
                <div class="flex items-center gap-4 ml-6 text-[12px] font-medium text-gray-500 overflow-hidden">
                    <span class="hidden lg:block text-gray-300">|</span>
                    @php
                        $trending = ['iPhone 15 Pro', 'Samsung S24 Ultra', 'MacBook Pro M3', 'Logitech G Pro', 'Sony WH-1000XM5', 'iPad Pro M2'];
                    @endphp
                    @foreach($trending as $link)
                        <a href="{{ route('katalog') }}?q={{ urlencode($link) }}" class="hover:text-primary transition-colors whitespace-nowrap">{{ $link }}</a>
                        @if(!$loop->last)
                            <span class="text-gray-200 font-light hidden lg:block">|</span>
                        @endif
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</div>
