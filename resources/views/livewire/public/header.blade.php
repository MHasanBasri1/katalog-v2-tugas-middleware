<div
    x-data="{
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
    <!-- Unified Header Stack (Fixed) -->
    <div class="fixed top-0 left-0 right-0 z-[100] transition-transform duration-300 transform" :class="isDesktopMenuHidden ? '-translate-y-[33px]' : 'translate-y-0'">
        <!-- Top Bar (Trust Signals) -->
        <div class="hidden md:block bg-gray-50 border-b border-gray-200 py-1.5">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center text-[12px] font-semibold text-gray-500 tracking-tight">
                <div class="flex items-center gap-5">
                    <a href="#" class="hover:text-primary transition-colors flex items-center gap-1.5"><i class="fas fa-mobile-alt text-[10px]"></i> Download App</a>
                    <span class="w-px h-3 bg-gray-200"></span>
                    <a href="#" class="hover:text-primary transition-colors">Kataloque Care</a>
                </div>
                <div class="flex items-center gap-5">
                    <a href="#" class="hover:text-primary transition-colors">Tentang Kami</a>
                    <a href="#" class="hover:text-primary transition-colors">Mitra Toko</a>
                </div>
            </div>
        </div>

        <!-- Main Header Container (Retail Style) -->
        <header class="bg-white border-b border-gray-200 transition-all duration-300">
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center gap-1.5 md:gap-3 lg:gap-3">
        <div class="flex items-center gap-2 md:gap-3 flex-1 md:flex-none md:shrink-0">
            <!-- Desktop Branding (Hidden on Mobile) -->
            <a href="{{ route('home') }}" class="hidden md:flex items-center gap-3 text-2xl font-black text-gray-900 tracking-tight shrink-0">
                <div class="w-11 h-11 rounded-xl bg-primary text-white flex items-center justify-center shadow-lg shadow-primary/20 shrink-0">
                    <i class="fas fa-cube text-xl"></i>
                </div>
                <span class="block text-2xl">Kataloque</span>
            </a>

            <!-- Integrated Mobile Search (Blibli Style) -->
            <div class="flex md:hidden flex-1 min-w-0 relative">
                <input wire:model.live.debounce.300ms="search" wire:keydown.enter="goToSearch" type="text" placeholder="Cari di Kataloque..." class="w-full bg-white border border-gray-300 focus:border-primary focus:bg-white rounded-xl py-2.5 pl-4 pr-12 outline-none transition text-[13px] font-semibold text-gray-700 placeholder:text-gray-400">
                <button wire:click="goToSearch" class="absolute right-1 top-1/2 -translate-y-1/2 w-9 h-9 flex items-center justify-center bg-primary text-white rounded-lg">
                    <i class="fas fa-search text-xs"></i>
                </button>
            </div>

            <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative hidden lg:block z-[60]">
                <button @click="open = !open" class="flex items-center gap-2 px-3.5 h-[42px] rounded-xl bg-gray-50 border border-gray-200 hover:border-gray-300 text-gray-700 hover:text-primary font-bold transition-all text-[13px] group shrink-0">
                    <i class="fas fa-th-large text-[11px] text-gray-400 group-hover:text-primary transition-colors"></i>
                    <span>Kategori</span>
                    <i class="fas fa-chevron-down text-[10px] ml-0.5 opacity-50 group-hover:opacity-100 transition-all"></i>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2" class="absolute top-full left-0 mt-3 w-56 bg-white border border-gray-200 rounded-xl shadow-xl py-2 z-50">
                    @foreach($categories as $category)
                        <a href="{{ route('kategori.detail', $category['slug']) }}" class="block px-4 py-2.5 mx-2 rounded-lg hover:bg-primary/5 hover:text-primary transition-colors text-sm text-gray-700 font-medium">{{ $category['name'] }}</a>
                    @endforeach
                    <div class="h-px bg-gray-50 my-2 mx-4"></div>
                    <a href="{{ route('kategori') }}" class="block px-4 py-2.5 mx-2 rounded-lg hover:bg-primary/5 text-sm font-bold text-primary transition-colors flex items-center justify-between group">
                        Explore kategori
                        <i class="fas fa-arrow-right text-xs opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0 transition-all"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="hidden md:flex flex-1 relative z-[55]" wire:click.stop>
            <div class="relative w-full group">
                <input wire:model.live.debounce.300ms="search" wire:keydown.enter="goToSearch" type="search" placeholder="Cari barang ori di Kataloque..." class="w-full h-[42px] bg-gray-50 border border-gray-300 focus:border-primary focus:ring-4 focus:ring-primary/10 rounded-xl pl-4 pr-14 outline-none transition-all duration-300 text-[13px] placeholder:text-gray-400 font-medium">
                <button wire:click="goToSearch" class="absolute right-0 top-0 bottom-0 px-7 bg-primary text-white rounded-r-xl hover:bg-primary-dark transition-all flex items-center justify-center">
                    <i class="fas fa-search"></i>
                </button>
            </div>

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

        <div class="flex items-center gap-2 sm:gap-4 relative z-50 shrink-0">
            @guest
                <!-- Mobile Auth Button (Blibli Style) -->
                <a href="{{ route('user.login') }}" class="flex md:hidden items-center gap-1.5 bg-primary text-white px-4 py-2.5 rounded-xl font-bold text-xs shrink-0 shadow-lg shadow-primary/20">
                    <i class="fas fa-sign-in-alt text-[10px]"></i>
                    <span>Masuk</span>
                </a>
            @endguest

            <div x-data="{ notif: false }" @mouseenter="notif = true" @mouseleave="notif = false" class="relative">
                <button wire:click="clearNotifications" @click="notif = !notif" class="hidden md:flex relative w-10 h-10 items-center justify-center rounded-full hover:bg-gray-50 transition-colors" title="Notifikasi">
                    <i class="far fa-bell text-lg text-gray-600 hover:text-primary"></i>
                    @if($notificationCount > 0)
                        <span class="icon-dot blink bg-red-500 border-2 border-white w-3 h-3 rounded-full absolute top-2 right-2"></span>
                    @endif
                </button>
                <div x-show="notif" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2" class="absolute top-full right-0 mt-3 w-72 sm:w-80 bg-white border border-gray-200 rounded-2xl shadow-2xl py-3 z-[100] overflow-hidden">
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

            <div class="w-px h-6 bg-gray-300 mx-1 sm:mx-2 hidden md:block"></div>
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
                <div class="hidden lg:flex items-center gap-3 shrink-0">
                    <a href="{{ route('user.register') }}" class="inline-flex items-center justify-center bg-white text-gray-700 border border-gray-300 px-5 h-[42px] rounded-xl font-bold text-[13px] leading-none hover:bg-gray-50 hover:text-primary hover:-translate-y-0.5 hover:shadow-sm transition-all duration-300 min-w-[90px]">
                        <span>Daftar</span>
                    </a>
                    <a href="{{ route('user.login') }}" class="inline-flex items-center justify-center bg-primary text-white px-5 h-[42px] rounded-xl font-bold text-[13px] leading-none hover:bg-primary-dark hover:-translate-y-0.5 hover:shadow-lg hover:shadow-primary/20 transition-all duration-300 min-w-[90px]">
                        <span>Masuk</span>
                    </a>
                </div>
@endauth
        </div>
    </div>

    </header>
    </div>
</div>
