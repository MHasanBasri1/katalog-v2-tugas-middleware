<div>
    <footer class="bg-gradient-to-br from-gray-50 via-white to-blue-50/70 border-t border-gray-100 text-gray-600 py-12 md:py-16 mt-16 relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.03] z-0"></div>
        <div class="absolute -right-20 -top-20 w-64 h-64 rounded-full bg-primary/5 blur-[100px] z-0"></div>
        <div class="absolute -left-20 -bottom-20 w-80 h-80 rounded-full bg-blue-500/5 blur-[100px] z-0"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 md:gap-12 relative z-10">
            <div>
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 text-2xl md:text-3xl font-black text-gray-900 tracking-tight mb-5">
                    <div class="w-10 h-10 md:w-12 md:h-12 rounded-2xl bg-gradient-to-br from-primary to-primary-dark text-white flex items-center justify-center shadow-xl shadow-primary/20">
                        <i class="fas fa-cube text-lg md:text-xl"></i>
                    </div>
                    <span>Kataloque</span>
                </a>
                <p class="text-gray-500 mb-6 leading-relaxed text-sm font-medium">
                    {{ $setting?->shop_description ?? 'Katalog produk modern, cepat, dan terpercaya. Temukan berbagai gaya terbaru dan terbaik di sini.' }}
                </p>
                <div class="flex gap-3">
                    <a href="#" class="w-10 h-10 rounded-full bg-white border border-gray-100 flex items-center justify-center text-gray-500 hover:bg-primary hover:text-white hover:border-primary transition-all duration-300 shadow-sm hover:shadow-lg hover:-translate-y-1"><i class="fab fa-facebook-f text-sm"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white border border-gray-100 flex items-center justify-center text-gray-500 hover:bg-primary hover:text-white hover:border-primary transition-all duration-300 shadow-sm hover:shadow-lg hover:-translate-y-1"><i class="fab fa-twitter text-sm"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white border border-gray-100 flex items-center justify-center text-gray-500 hover:bg-primary hover:text-white hover:border-primary transition-all duration-300 shadow-sm hover:shadow-lg hover:-translate-y-1"><i class="fab fa-instagram text-sm"></i></a>
                </div>
            </div>

            <div class="space-y-6">
                <h4 class="text-gray-900 font-black text-lg flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-full bg-blue-100/50 text-blue-600 flex items-center justify-center"><i class="fas fa-link text-sm"></i></span>
                    Tautan Cepat
                </h4>
                <ul class="space-y-3.5 text-sm font-medium">
                    <li><a href="{{ url('/tentang-kami') }}" class="text-gray-500 hover:text-primary transition-all flex items-center gap-2 group"><span class="w-1.5 h-1.5 rounded-full bg-gray-300 group-hover:bg-primary group-hover:w-3 transition-all"></span> Tentang Kami</a></li>
                    <li><a href="{{ url('/blog') }}" class="text-gray-500 hover:text-primary transition-all flex items-center gap-2 group"><span class="w-1.5 h-1.5 rounded-full bg-gray-300 group-hover:bg-primary group-hover:w-3 transition-all"></span> Blog</a></li>
                    <li><a href="{{ url('/cara-pesan') }}" class="text-gray-500 hover:text-primary transition-all flex items-center gap-2 group"><span class="w-1.5 h-1.5 rounded-full bg-gray-300 group-hover:bg-primary group-hover:w-3 transition-all"></span> Cara Pesan</a></li>
                    <li><a href="{{ url('/pembayaran') }}" class="text-gray-500 hover:text-primary transition-all flex items-center gap-2 group"><span class="w-1.5 h-1.5 rounded-full bg-gray-300 group-hover:bg-primary group-hover:w-3 transition-all"></span>Pembayaran</a></li>
                    <li><a href="{{ url('/lokasi-toko') }}" class="text-gray-500 hover:text-primary transition-all flex items-center gap-2 group"><span class="w-1.5 h-1.5 rounded-full bg-gray-300 group-hover:bg-primary group-hover:w-3 transition-all"></span> Lokasi Toko</a></li>
                </ul>
            </div>

            <div class="space-y-6">
                <h4 class="text-gray-900 font-black text-lg flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-full bg-indigo-100/50 text-indigo-600 flex items-center justify-center"><i class="fas fa-layer-group text-sm"></i></span>
                    Kategori
                </h4>
                <ul class="space-y-3.5 text-sm font-medium">
                    @foreach($categories as $category)
                        <li><a href="#" class="text-gray-500 hover:text-primary transition-all flex items-center gap-2 group"><span class="w-1.5 h-1.5 rounded-full bg-gray-300 group-hover:bg-primary group-hover:w-3 transition-all"></span> {{ $category }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div class="space-y-6">
                <h4 class="text-gray-900 font-black text-lg flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-full bg-rose-100/50 text-rose-500 flex items-center justify-center"><i class="fas fa-headset text-sm"></i></span>
                    Hubungi Kami
                </h4>
                <ul class="space-y-4 text-sm font-medium">
                    <li class="flex items-start gap-3.5 group cursor-pointer">
                        <div class="w-10 h-10 rounded-full bg-white border border-gray-100 text-gray-400 group-hover:bg-blue-50 group-hover:text-blue-600 group-hover:border-blue-200 flex items-center justify-center flex-shrink-0 transition-all shadow-sm">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="mt-0.5">
                            <span class="block text-xs text-gray-400 font-bold uppercase tracking-wider mb-0.5">Email</span>
                            <span class="text-gray-700 font-semibold group-hover:text-blue-600 transition-colors">{{ $setting?->email ?? 'cs@kataloque.id' }}</span>
                        </div>
                    </li>
                    <li class="flex items-start gap-3.5 group cursor-pointer">
                        <div class="w-10 h-10 rounded-full bg-white border border-gray-100 text-gray-400 group-hover:bg-green-50 group-hover:text-green-600 group-hover:border-green-200 flex items-center justify-center flex-shrink-0 transition-all shadow-sm">
                            <i class="fab fa-whatsapp text-lg"></i>
                        </div>
                        <div class="mt-0.5">
                            <span class="block text-xs text-gray-400 font-bold uppercase tracking-wider mb-0.5">WhatsApp</span>
                            <span class="text-gray-700 font-semibold group-hover:text-green-600 transition-colors">{{ $setting?->whatsapp ?? '+62 812 3456 7890' }}</span>
                        </div>
                    </li>
                    <li class="flex items-start gap-3.5 group cursor-pointer">
                        <div class="w-10 h-10 rounded-full bg-white border border-gray-100 text-gray-400 group-hover:bg-rose-50 group-hover:text-rose-500 group-hover:border-rose-200 flex items-center justify-center flex-shrink-0 transition-all shadow-sm">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="mt-0.5">
                            <span class="block text-xs text-gray-400 font-bold uppercase tracking-wider mb-0.5">Lokasi</span>
                            <span class="text-gray-700 font-semibold leading-relaxed group-hover:text-rose-500 transition-colors">{{ $setting?->shop_address ?? 'Jl. Sudirman No. 1' }}, {{ $setting?->city ?? 'Jakarta' }}</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pt-6 border-t border-gray-200 flex flex-col md:flex-row items-center justify-between gap-4 relative z-10 text-sm text-gray-500 font-medium">
            <p>&copy; 2026 <span class="text-gray-800 font-bold">{{ $setting?->shop_name ?? 'Kataloque' }}</span>. Hak Cipta Dilindungi.</p>
            <p class="flex items-center gap-1.5 tracking-wide">Build with <i class="fas fa-heart text-rose-500 animate-pulse"></i> in Sidoarjo.</p>
        </div>
    </footer>

    <!-- Floating Customer Service -->
    <div class="fixed bottom-24 md:bottom-6 right-6 z-[100] group flex items-end">
        <!-- Pulse Effect -->
        <span class="absolute right-0 bottom-0 inline-flex h-12 w-12 md:h-14 md:w-14 rounded-full bg-primary/40 animate-ping group-hover:animate-none"></span>
        
        <div class="flex flex-col items-end gap-4">
            <!-- Chat Illustration/Box (Shows on Hover - Desktop Only) -->
            <div class="hidden md:block translate-y-4 opacity-0 scale-90 group-hover:translate-y-0 group-hover:opacity-100 group-hover:scale-100 transition-all duration-300 pointer-events-none mb-2 w-64">
                <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
                    <!-- Chat Header -->
                    <div class="bg-primary p-4 flex items-center gap-3">
                        <div class="relative">
                            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                                <i class="fas fa-headset text-white text-lg"></i>
                            </div>
                            <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-primary rounded-full animate-pulse"></span>
                        </div>
                        <div>
                            <h5 class="text-white text-xs font-black uppercase tracking-widest leading-none">Customer Service</h5>
                            <p class="text-blue-100 text-[10px] mt-1 font-medium italic">Online & Siap Membantu</p>
                        </div>
                    </div>
                    <!-- Chat Body -->
                    <div class="p-4 bg-gray-50/50 space-y-3">
                        <div class="bg-white p-3 rounded-2xl rounded-tl-none shadow-sm border border-gray-100">
                            <p class="text-[11px] text-gray-600 leading-relaxed">Halo! Ada yang bisa kami bantu untuk pencarian produk Anda? 😊</p>
                        </div>
                        <div class="flex items-center gap-2 text-[9px] text-gray-400 font-bold px-1 uppercase tracking-tighter">
                            <i class="fas fa-clock text-[8px]"></i> Balas dalam sekejap
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Button -->
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $setting?->whatsapp ?? '+62 812 3456 7890') }}" 
               target="_blank" 
               rel="noopener noreferrer"
               class="relative flex items-center gap-3 bg-primary text-white p-3 md:pl-5 md:pr-2 md:py-2 rounded-full shadow-2xl shadow-primary/40 transform transition-all duration-300 group-hover:shadow-primary/60 border-0 group-hover:-translate-y-1 overflow-hidden"
               title="Hubungi Customer Service">
                
                <span class="hidden md:block text-sm font-black tracking-tight whitespace-nowrap">Customer Service</span>
                
                <div class="w-6 h-6 md:w-10 md:h-10 flex items-center justify-center transform group-hover:rotate-12 transition-transform">
                    <i class="fas fa-comments text-lg md:text-lg"></i>
                </div>

                <!-- Shine Effect -->
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
            </a>
        </div>
    </div>
</div>

