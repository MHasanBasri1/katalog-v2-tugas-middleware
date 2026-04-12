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
                    @if($setting?->social_media)
                        @foreach($setting->social_media as $social)
                            @php
                                $icon = match($social['platform']) {
                                    'facebook' => 'ti ti-brand-facebook',
                                    'instagram' => 'ti ti-brand-instagram',
                                    'twitter' => 'ti ti-brand-twitter',
                                    'tiktok' => 'ti ti-brand-tiktok',
                                    'youtube' => 'ti ti-brand-youtube',
                                    'website' => 'ti ti-world',
                                    default => 'ti ti-link'
                                };
                                $color = match($social['platform']) {
                                    'facebook' => 'text-blue-600',
                                    'instagram' => 'text-pink-600',
                                    'twitter' => 'text-blue-400',
                                    'tiktok' => 'text-black dark:text-white',
                                    'youtube' => 'text-red-600',
                                    'website' => 'text-emerald-600',
                                    default => 'text-blue-600'
                                };
                                $url = $social['username'];
                                if ($social['platform'] === 'instagram' && !Str::startsWith($url, 'http')) $url = "https://instagram.com/" . ltrim($url, '@');
                                if ($social['platform'] === 'facebook' && !Str::startsWith($url, 'http')) $url = "https://facebook.com/" . $url;
                                if ($social['platform'] === 'twitter' && !Str::startsWith($url, 'http')) $url = "https://twitter.com/" . ltrim($url, '@');
                                if ($social['platform'] === 'tiktok' && !Str::startsWith($url, 'http')) $url = "https://tiktok.com/@" . ltrim($url, '@');
                                if ($social['platform'] === 'youtube' && !Str::startsWith($url, 'http')) $url = "https://youtube.com/" . $url;
                            @endphp
                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="{{ $color }} hover:scale-110 transition-transform block" aria-label="{{ ucfirst($social['platform']) }}">
                                <i class="{{ $icon }} text-2xl" aria-hidden="true"></i>
                            </a>
                        @endforeach
                    @else
                        @if($setting?->facebook)
                            <a href="{{ Str::startsWith($setting->facebook, 'http') ? $setting->facebook : 'https://facebook.com/' . $setting->facebook }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:scale-110 transition-transform" aria-label="Facebook">
                                <i class="ti ti-brand-facebook text-2xl" aria-hidden="true"></i>
                            </a>
                        @endif
                        @if($setting?->instagram)
                            <a href="{{ Str::startsWith($setting->instagram, 'http') ? $setting->instagram : 'https://instagram.com/' . ltrim($setting->instagram, '@') }}" target="_blank" rel="noopener noreferrer" class="text-pink-600 hover:scale-110 transition-transform" aria-label="Instagram">
                                <i class="ti ti-brand-instagram text-2xl" aria-hidden="true"></i>
                            </a>
                        @endif
                    @endif
                </div>
            </div>

            <div class="space-y-6">
                <h4 class="text-gray-900 font-bold text-sm tracking-wide">Tautan Cepat</h4>
                <ul class="space-y-3 text-sm font-medium">
                    @if($setting && !empty($setting->footer_navigation))
                        @foreach($setting->footer_navigation as $link)
                            <li><a href="{{ $link['url'] }}" class="text-gray-500 hover:text-blue-600 transition-colors">{{ $link['label'] }}</a></li>
                        @endforeach
                    @else
                        <li><a href="{{ url('/tentang-kami') }}" class="text-gray-500 hover:text-blue-600 transition-colors">Tentang Kami</a></li>
                        <li><a href="{{ url('/blog') }}" class="text-gray-500 hover:text-blue-600 transition-colors">Blog</a></li>
                        <li><a href="{{ url('/cara-pesan') }}" class="text-gray-500 hover:text-blue-600 transition-colors">Cara Belanja</a></li>
                        <li><a href="{{ url('/pembayaran') }}" class="text-gray-500 hover:text-blue-600 transition-colors">Metode Pembayaran</a></li>
                    @endif
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
                        @if($setting?->marketplaces)
                            @foreach($setting->marketplaces as $platform => $url)
                                @php
                                    $colorStyles = match($platform) {
                                        'shopee' => 'hover:bg-orange-50 hover:border-[#EE4D2D] hover:text-[#EE4D2D]',
                                        'tokopedia' => 'hover:bg-emerald-50 hover:border-[#42B549] hover:text-[#42B549]',
                                        'tiktok' => 'hover:bg-gray-50 hover:border-black hover:text-black',
                                        'lazada' => 'hover:bg-blue-50 hover:border-[#0F146D] hover:text-[#0F146D]',
                                        'blibli' => 'hover:bg-blue-50 hover:border-[#0095DC] hover:text-[#0095DC]',
                                        default => 'hover:bg-gray-100 hover:border-gray-500 hover:text-gray-700'
                                    };
                                @endphp
                                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" 
                                   class="h-6 px-2 bg-white border border-gray-200 rounded text-[9px] flex items-center font-black shadow-sm {{ $colorStyles }} transition-all cursor-pointer uppercase tracking-tighter">
                                   {{ $platform }}
                                </a>
                            @endforeach
                        @else
                            <span class="text-[10px] text-gray-400 font-medium italic">Belum ada marketplace terhubung.</span>
                        @endif
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

