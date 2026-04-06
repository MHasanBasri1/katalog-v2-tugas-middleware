<div
    x-data="{
        lastScrollY: 0,
        isTopBarHidden: false,
        init() {
            this.lastScrollY = window.scrollY;
            window.addEventListener('scroll', () => {
                if (window.innerWidth < 768) {
                    this.isTopBarHidden = false;
                    this.lastScrollY = window.scrollY;
                    return;
                }
                const currentY = window.scrollY;
                this.isTopBarHidden = currentY > 8 && currentY > this.lastScrollY;
                this.lastScrollY = currentY;
            }, { passive: true });
        }
    }"
    class="relative"
>
    <div class="fixed top-0 left-0 right-0 z-[100] transition-transform duration-300" :class="isTopBarHidden ? '-translate-y-[33px]' : 'translate-y-0'">

        {{-- === TOP BAR (Desktop Only) === --}}
        <div class="hidden md:block bg-gray-50 border-b border-gray-200" style="height: 33px;">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex justify-between items-center text-[12px] font-semibold text-gray-500 tracking-tight">
                <div class="flex items-center gap-5">
                    <a href="#" class="hover:text-primary transition-colors flex items-center gap-1.5 whitespace-nowrap"><i class="fas fa-mobile-alt text-[10px]"></i> Download App</a>
                    <span class="w-px h-3 bg-gray-200"></span>
                    <a href="#" class="hover:text-primary transition-colors whitespace-nowrap">Kataloque Care</a>
                </div>
                <div class="flex items-center gap-5">
                    <a href="#" class="hover:text-primary transition-colors whitespace-nowrap">Tentang Kami</a>
                    <a href="#" class="hover:text-primary transition-colors whitespace-nowrap">Mitra Toko</a>
                </div>
            </div>
        </div>

        {{-- === MAIN HEADER === --}}
        <header class="bg-white border-b border-gray-200" style="height: 56px;">
            <div class="w-full max-w-7xl mx-auto px-3 sm:px-4 lg:px-6 h-full flex items-center gap-2 md:gap-3">

                {{-- LOGO --}}
                <a href="{{ route('home') }}" class="hidden md:flex items-center gap-2.5 shrink-0">
                    <div class="w-9 h-9 rounded-lg bg-primary text-white flex items-center justify-center shadow-md shadow-primary/20 shrink-0">
                        <i class="fas fa-cube text-base"></i>
                    </div>
                    <span class="text-lg font-black text-gray-900 tracking-tight whitespace-nowrap">Kataloque</span>
                </a>

                {{-- CATEGORY BUTTON (Desktop lg+) --}}
                <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative hidden lg:block shrink-0 z-[60]">
                    <button @click="open = !open" class="flex items-center gap-1.5 px-3 rounded-lg bg-gray-50 border border-gray-200 hover:border-gray-300 text-gray-700 hover:text-primary font-bold transition-all text-[13px] group whitespace-nowrap" style="height: 36px;">
                        <i class="fas fa-th-large text-[11px] text-gray-400 group-hover:text-primary transition-colors"></i>
                        <span>Kategori</span>
                        <i class="fas fa-chevron-down text-[9px] opacity-50 group-hover:opacity-100 transition-all"></i>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2" class="absolute top-full left-0 mt-2 w-56 bg-white border border-gray-200 rounded-xl shadow-xl py-2 z-50" x-cloak>
                        @foreach($categories as $category)
                            <a href="{{ route('kategori.detail', $category['slug']) }}" class="block px-4 py-2.5 mx-2 rounded-lg hover:bg-primary/5 hover:text-primary transition-colors text-sm text-gray-700 font-medium">{{ $category['name'] }}</a>
                        @endforeach
                        <div class="h-px bg-gray-100 my-2 mx-4"></div>
                        <a href="{{ route('kategori') }}" class="flex items-center justify-between px-4 py-2.5 mx-2 rounded-lg hover:bg-primary/5 text-sm font-bold text-primary transition-colors group">
                            Explore kategori
                            <i class="fas fa-arrow-right text-xs opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0 transition-all"></i>
                        </a>
                    </div>
                </div>

                {{-- MOBILE SEARCH --}}
                <div class="flex md:hidden flex-1 min-w-0 relative" wire:click.stop>
                    <input wire:model.live.debounce.300ms="search" wire:keydown.enter="goToSearch" type="text" placeholder="Cari di Kataloque..."
                        class="w-full bg-white border border-gray-300 focus:border-primary rounded-lg outline-none transition text-[13px] font-semibold text-gray-700 placeholder:text-gray-400"
                        style="height: 36px; padding: 0 2.5rem 0 0.75rem;">
                    <button wire:click="goToSearch" class="absolute right-0.5 top-1/2 -translate-y-1/2 flex items-center justify-center bg-primary text-white rounded-md" style="width: 30px; height: 30px;">
                        <i class="fas fa-search text-[11px]"></i>
                    </button>

                    @if($search !== '')
                        <div class="absolute left-0 right-0 top-full mt-2 bg-white border border-gray-100 rounded-xl shadow-xl z-[200] overflow-hidden">
                            <div class="p-2 space-y-0.5 max-h-64 overflow-y-auto">
                                @forelse($this->searchResults as $item)
                                    <a href="{{ $item['url'] }}" class="block px-4 py-2.5 rounded-lg text-sm text-gray-700 hover:bg-primary/5 hover:text-primary font-medium transition-colors">{{ $item['name'] }}</a>
                                @empty
                                    <div class="px-4 py-3 text-sm text-gray-500 text-center">Produk tidak ditemukan.</div>
                                @endforelse
                                <div class="border-t border-gray-100 mt-1 pt-1">
                                    <button wire:click="goToSearch" class="w-full px-4 py-2 text-sm font-bold text-primary hover:bg-primary/5 rounded-lg transition-colors text-center">
                                        Lihat semua hasil &rarr;
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- DESKTOP SEARCH --}}
                <div class="hidden md:flex flex-1 min-w-0 relative z-[55]" wire:click.stop>
                    <div class="relative w-full">
                        <input wire:model.live.debounce.300ms="search" wire:keydown.enter="goToSearch" type="search" placeholder="Cari barang ori di Kataloque..."
                            class="w-full bg-gray-50 border border-gray-300 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-lg outline-none transition-all text-[13px] placeholder:text-gray-400 font-medium"
                            style="height: 36px; padding: 0 3rem 0 0.875rem;">
                        <button wire:click="goToSearch" class="absolute right-0 top-0 bottom-0 bg-primary text-white rounded-r-lg hover:bg-primary-dark transition-all flex items-center justify-center" style="width: 42px;">
                            <i class="fas fa-search text-sm"></i>
                        </button>
                    </div>

                    @if($search !== '')
                        <div class="absolute left-0 right-0 top-full mt-2 bg-white border border-gray-100 rounded-xl shadow-xl z-40 overflow-hidden">
                            <div class="p-2 space-y-0.5 max-h-72 overflow-y-auto">
                                @forelse($this->searchResults as $item)
                                    <a href="{{ $item['url'] }}" class="block px-4 py-2.5 rounded-lg text-sm text-gray-700 hover:bg-primary/5 hover:text-primary font-medium transition-colors">{{ $item['name'] }}</a>
                                @empty
                                    <div class="px-4 py-3 text-sm text-gray-500 text-center">Produk tidak ditemukan.</div>
                                @endforelse
                                <div class="border-t border-gray-100 mt-1 pt-1">
                                    <button wire:click="goToSearch" class="w-full px-4 py-2 text-sm font-bold text-primary hover:bg-primary/5 rounded-lg transition-colors text-center">
                                        Lihat semua hasil &rarr;
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- RIGHT ACTIONS --}}
                <div class="flex items-center gap-1.5 md:gap-2 shrink-0 relative z-50">
                    @guest
                        {{-- Mobile: single login button --}}
                        <a href="{{ route('user.login') }}" class="flex md:hidden items-center gap-1 bg-primary text-white px-3 rounded-lg font-bold text-[11px] shrink-0 shadow-md shadow-primary/20 whitespace-nowrap" style="height: 36px;">
                            <i class="fas fa-sign-in-alt text-[10px]"></i>
                            Masuk
                        </a>
                    @endguest

                    {{-- Notification Bell (Desktop) --}}
                    <div x-data="{ notif: false }" @mouseenter="notif = true" @mouseleave="notif = false" class="relative hidden md:block">
                        <button wire:click="clearNotifications" @click="notif = !notif" class="relative flex items-center justify-center rounded-full hover:bg-gray-50 transition-colors" style="width: 36px; height: 36px;" title="Notifikasi">
                            <i class="far fa-bell text-[17px] text-gray-500 hover:text-primary transition-colors"></i>
                            @if($notificationCount > 0)
                                <span class="absolute top-0.5 right-0.5 w-2.5 h-2.5 bg-red-500 border-2 border-white rounded-full blink"></span>
                            @endif
                        </button>
                        <div x-show="notif" x-cloak x-transition class="absolute top-full right-0 mt-2 w-72 sm:w-80 bg-white border border-gray-200 rounded-xl shadow-2xl py-3 z-[100]">
                            <div class="px-4 border-b border-gray-100 pb-2 mb-2">
                                <h4 class="text-sm font-black text-gray-800">Pembaruan & Diskon</h4>
                            </div>
                            <div class="max-h-64 overflow-y-auto px-2 space-y-1">
                                <a href="{{ route('katalog') }}" class="flex items-start gap-3 p-3 hover:bg-primary/5 rounded-lg transition-colors">
                                    <div class="w-9 h-9 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-bullhorn text-sm"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <h5 class="text-sm font-bold text-gray-800 mb-0.5">Promo Spesial Hari Ini!</h5>
                                        <p class="text-xs text-gray-500 line-clamp-2">Dapatkan diskon hingga 50% untuk kategori pilihan.</p>
                                        <span class="text-[10px] text-gray-400 mt-1 block font-medium">Beberapa menit lalu</span>
                                    </div>
                                </a>
                                <a href="{{ route('katalog') }}" class="flex items-start gap-3 p-3 hover:bg-primary/5 rounded-lg transition-colors">
                                    <div class="w-9 h-9 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-box-open text-sm"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <h5 class="text-sm font-bold text-gray-800 mb-0.5">Produk Baru Rilis</h5>
                                        <p class="text-xs text-gray-500 line-clamp-2">Cek koleksi terbaru kami minggu ini.</p>
                                        <span class="text-[10px] text-gray-400 mt-1 block font-medium">1 jam lalu</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Divider --}}
                    <div class="w-px h-5 bg-gray-200 hidden md:block"></div>

                    @auth
                        {{-- Authenticated Buttons (Desktop) --}}
                        <div class="hidden md:flex items-center gap-1.5">
                            @if(auth()->user()->hasRole('admin'))
                                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 bg-primary text-white px-4 rounded-lg font-bold text-[12px] leading-none hover:bg-primary-dark transition-all whitespace-nowrap" style="height: 36px;">
                                    <i class="fas fa-shield-alt text-[10px]"></i>
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ auth()->user()->hasVerifiedEmail() ? route('user.panel') : route('verification.notice') }}" class="inline-flex items-center gap-1.5 bg-primary text-white px-4 rounded-lg font-bold text-[12px] leading-none hover:bg-primary-dark transition-all whitespace-nowrap" style="height: 36px;">
                                    <i class="fas {{ auth()->user()->hasVerifiedEmail() ? 'fa-user-circle' : 'fa-envelope-open-text' }} text-[10px]"></i>
                                    {{ auth()->user()->hasVerifiedEmail() ? 'Profil' : 'Verifikasi' }}
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-1.5 bg-gray-50 text-gray-600 border border-gray-200 px-3 rounded-lg font-bold text-[12px] leading-none hover:bg-gray-100 transition-all whitespace-nowrap" style="height: 36px;">
                                    <i class="fas fa-sign-out-alt text-[10px]"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    @else
                        {{-- Guest Buttons (Desktop) --}}
                        <div class="hidden md:flex items-center gap-1.5 shrink-0">
                            <a href="{{ route('user.register') }}" class="inline-flex items-center justify-center bg-white text-gray-700 border border-gray-300 px-4 rounded-lg font-bold text-[12px] leading-none hover:bg-gray-50 hover:text-primary transition-all whitespace-nowrap" style="height: 36px;">
                                Daftar
                            </a>
                            <a href="{{ route('user.login') }}" class="inline-flex items-center justify-center bg-primary text-white px-4 rounded-lg font-bold text-[12px] leading-none hover:bg-primary-dark transition-all whitespace-nowrap" style="height: 36px;">
                                Masuk
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </header>
    </div>
</div>
