<div>
    <footer class="bg-gray-50 border-t border-gray-200 text-gray-600/80 pt-12 pb-6 mt-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
            <div class="space-y-6">
                <a href="{{ route('home') }}" class="flex items-center gap-3 text-2xl font-black text-gray-900 tracking-tight">
                    <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shadow-lg shadow-blue-200">
                        <i class="fas fa-cube text-lg"></i>
                    </div>
                    <span>{{ $setting?->shop_name ?? 'Kataloque' }}</span>
                </a>
                <p class="text-sm leading-relaxed font-medium text-gray-500 max-w-sm">
                    {{ $setting?->shop_description ?? 'Satu destinasi untuk semua kebutuhan gaya hidup Anda. Belanja cerdas, cepat, dan aman hanya di Kataloque.' }}
                </p>
                <div class="flex gap-4">
                    <a href="#" class="text-gray-400 hover:text-blue-600 transition-colors"><i class="fab fa-facebook-square text-xl"></i></a>
                    <a href="#" class="text-gray-400 hover:text-black transition-colors"><i class="fab fa-instagram text-xl"></i></a>
                    <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors"><i class="fab fa-twitter text-xl"></i></a>
                </div>
            </div>

            <div class="space-y-6">
                <h4 class="text-gray-900 font-bold text-sm tracking-wide">Tautan Cepat</h4>
                <ul class="space-y-3 text-sm font-medium">
                    <li><a href="{{ url('/tentang-kami') }}" class="text-gray-500 hover:text-blue-600 transition-colors">Tentang Kami</a></li>
                    <li><a href="{{ url('/blog') }}" class="text-gray-500 hover:text-blue-600 transition-colors">Blog</a></li>
                    <li><a href="{{ url('/cara-pesan') }}" class="text-gray-500 hover:text-blue-600 transition-colors">Cara Belanja</a></li>
                    <li><a href="{{ url('/pembayaran') }}" class="text-gray-500 hover:text-blue-600 transition-colors">Metode Pembayaran</a></li>
                </ul>
            </div>

            <div class="space-y-6">
                <h4 class="text-gray-900 font-bold text-sm tracking-wide">Layanan Pelanggan</h4>
                <ul class="space-y-3 text-sm font-medium text-gray-500">
                    <li class="flex items-center gap-2">
                        <i class="fas fa-envelope text-blue-500 w-4"></i> 
                        <a href="mailto:{{ $setting?->email ?? 'cs@kataloque.id' }}" class="hover:text-blue-600 transition-colors">{{ $setting?->email ?? 'cs@kataloque.id' }}</a>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fab fa-whatsapp text-green-500 w-4"></i> 
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $setting?->whatsapp ?? '6281234567890') }}" target="_blank" class="hover:text-green-600 transition-colors">{{ $setting?->whatsapp ?? '+62 812 3456 7890' }}</a>
                    </li>
                    <li class="flex items-start gap-2"><i class="fas fa-map-marker-alt text-rose-500 w-4 mt-1"></i> <span>{{ $setting?->shop_address ?? 'Jakarta, Indonesia' }}</span></li>
                </ul>
            </div>

            <div class="space-y-8">
                <div class="space-y-4">
                    <h4 class="text-gray-900 font-bold text-sm tracking-wide">Marketplace</h4>
                    <div class="flex flex-wrap gap-2 opacity-70">
                         <div class="h-6 px-2 bg-white border border-gray-300 rounded text-[9px] flex items-center font-bold">SHOPEE</div>
                         <div class="h-6 px-2 bg-white border border-gray-300 rounded text-[9px] flex items-center font-bold">TOKOPEDIA</div>
                         <div class="h-6 px-2 bg-white border border-gray-300 rounded text-[9px] flex items-center font-bold">TIKTOK</div>
                    </div>
                </div>
                <div class="space-y-4">
                    <h4 class="text-gray-900 font-bold text-sm tracking-wide">Pembayaran</h4>
                    <div class="flex flex-wrap gap-2 opacity-70">
                         <div class="h-6 px-2 bg-white border border-gray-300 rounded text-[9px] flex items-center font-bold">BCA</div>
                         <div class="h-6 px-2 bg-white border border-gray-300 rounded text-[9px] flex items-center font-bold">BNI</div>
                         <div class="h-6 px-2 bg-white border border-gray-300 rounded text-[9px] flex items-center font-bold">GOPAY</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 pt-4 border-t border-gray-200 text-center">
            <p class="text-[11px] text-gray-400 font-medium whitespace-nowrap">
                &copy; {{ date('Y') }} {{ $setting?->shop_name ?? 'Kataloque' }}. Hak Cipta Dilindungi Undang-Undang.
            </p>
        </div>
    </footer>

</div>

