<div>
    <footer class="bg-gray-50 border-t border-gray-200 text-gray-600/80 pt-12 pb-6 mt-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
            <div class="space-y-6">
                <a href="{{ route('home') }}" class="flex items-center" aria-label="Kataloque Beranda">
                    <img src="https://www.static-src.com/frontend/static/img/logo-blibli-blue.0f340eba.svg" alt="Logo" class="h-10 w-auto">
                </a>
                <p class="text-sm leading-relaxed font-medium text-gray-500 max-w-sm">
                    {{ $setting?->shop_description ?? 'Satu destinasi untuk semua kebutuhan gaya hidup Anda. Belanja cerdas, cepat, dan aman hanya di Kataloque.' }}
                </p>
                <div class="flex gap-4">
                    <a href="#" class="text-gray-500 hover:text-blue-600 transition-colors" aria-label="Facebook Kataloque"><i class="fab fa-facebook-square text-xl" aria-hidden="true"></i></a>
                    <a href="#" class="text-gray-500 hover:text-black transition-colors" aria-label="Instagram Kataloque"><i class="fab fa-instagram text-xl" aria-hidden="true"></i></a>
                    <a href="#" class="text-gray-500 hover:text-blue-400 transition-colors" aria-label="Twitter Kataloque"><i class="fab fa-twitter text-xl" aria-hidden="true"></i></a>
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
                        <i class="fas fa-envelope text-blue-500 w-4" aria-hidden="true"></i> 
                        <a href="mailto:{{ $setting?->email ?? 'cs@kataloque.id' }}" class="hover:text-blue-600 transition-colors">{{ $setting?->email ?? 'cs@kataloque.id' }}</a>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fab fa-whatsapp text-green-500 w-4" aria-hidden="true"></i> 
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $setting?->whatsapp ?? '6281234567890') }}" target="_blank" class="hover:text-green-600 transition-colors" rel="noopener noreferrer">{{ $setting?->whatsapp ?? '+62 812 3456 7890' }}</a>
                    </li>
                    <li class="flex items-start gap-2"><i class="fas fa-map-marker-alt text-rose-500 w-4 mt-1" aria-hidden="true"></i> <span>{{ $setting?->shop_address ?? 'Jakarta, Indonesia' }}</span></li>
                </ul>
            </div>

            <div class="space-y-8">
                <div class="space-y-4">
                    <h4 class="text-gray-900 font-bold text-sm tracking-wide">Marketplace</h4>
                    <div class="flex flex-wrap gap-2 text-gray-600">
                        @php
                            $shopeeUrl = $setting->marketplaces['shopee'] ?? '#';
                            $tokopediaUrl = $setting->marketplaces['tokopedia'] ?? '#';
                            $tiktokUrl = $setting->marketplaces['tiktok'] ?? '#';
                        @endphp
                        <a href="{{ $shopeeUrl }}" target="_blank" rel="noopener noreferrer" class="h-6 px-2 bg-white border border-gray-300 rounded text-[9px] flex items-center font-bold hover:bg-orange-50 hover:border-orange-500 hover:text-orange-600 transition-colors cursor-pointer">SHOPEE</a>
                        <a href="{{ $tokopediaUrl }}" target="_blank" rel="noopener noreferrer" class="h-6 px-2 bg-white border border-gray-300 rounded text-[9px] flex items-center font-bold hover:bg-emerald-50 hover:border-emerald-500 hover:text-emerald-600 transition-colors cursor-pointer">TOKOPEDIA</a>
                        <a href="{{ $tiktokUrl }}" target="_blank" rel="noopener noreferrer" class="h-6 px-2 bg-white border border-gray-300 rounded text-[9px] flex items-center font-bold hover:bg-gray-50 hover:border-black hover:text-black transition-colors cursor-pointer">TIKTOK</a>
                    </div>
                </div>
                <div class="space-y-4">
                    <h4 class="text-gray-900 font-bold text-sm tracking-wide">Pembayaran</h4>
                    <div class="flex flex-wrap gap-2 text-gray-600">
                         <div class="h-6 px-2 bg-white border border-gray-300 rounded text-[9px] flex items-center font-bold">BCA</div>
                         <div class="h-6 px-2 bg-white border border-gray-300 rounded text-[9px] flex items-center font-bold">BNI</div>
                         <div class="h-6 px-2 bg-white border border-gray-300 rounded text-[9px] flex items-center font-bold">GOPAY</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 pt-4 border-t border-gray-200 text-center">
            <p class="text-[11px] text-gray-500 font-medium whitespace-nowrap">
                &copy; {{ date('Y') }} {{ $setting?->shop_name ?? 'Kataloque' }}. Hak Cipta Dilindungi Undang-Undang.
            </p>
        </div>
    </footer>

</div>

