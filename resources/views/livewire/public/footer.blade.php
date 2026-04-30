<div>
    <footer class="bg-gray-50 border-t border-gray-200 text-gray-600/80 pt-12 pb-6 mt-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
            
            {{-- 1. Logo & Deskripsi --}}
            <div class="space-y-6">
                <a href="/" class="flex items-center">
                    @if($setting?->shop_logo)
                        <img src="{{ $setting->shop_logo }}" alt="Logo" class="h-10 w-auto">
                    @else
                        <span class="text-xl font-black text-primary">{{ $setting?->shop_name ?? 'Kataloque' }}</span>
                    @endif
                </a>
                <p class="text-sm leading-relaxed font-medium text-gray-500 max-w-sm">
                    {{ $setting?->shop_description ?? 'Satu destinasi untuk semua kebutuhan gaya hidup Anda.' }}
                </p>
                
                {{-- Media Sosial --}}
                <div class="flex gap-4">
                    @if($setting?->social_media && is_array($setting->social_media))
                        @foreach($setting->social_media as $social)
                            @php
                                $platform = $social['platform'] ?? 'website';
                                $username = is_array($social['username'] ?? '') ? '#' : ($social['username'] ?? '#');
                                
                                $icon = match($platform) {
                                    'facebook' => 'ti ti-brand-facebook',
                                    'instagram' => 'ti ti-brand-instagram',
                                    'tiktok' => 'ti ti-brand-tiktok',
                                    default => 'ti ti-link'
                                };
                            @endphp
                            <a href="{{ $username }}" target="_blank" class="text-gray-400 hover:text-primary transition-transform hover:scale-110">
                                <i class="{{ $icon }} text-2xl"></i>
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- 2. Tautan Cepat --}}
            <div class="space-y-6">
                <h4 class="text-gray-900 font-bold text-sm tracking-wide">Tautan Cepat</h4>
                <ul class="space-y-3 text-sm font-medium">
                    @if($setting?->footer_navigation && is_array($setting->footer_navigation))
                        @foreach($setting->footer_navigation as $link)
                            <li><a href="{{ $link['url'] ?? '#' }}" class="text-gray-500 hover:text-blue-600 transition-colors">{{ $link['label'] ?? 'Link' }}</a></li>
                        @endforeach
                    @else
                        <li><a href="#" class="text-gray-500">Tentang Kami</a></li>
                        <li><a href="#" class="text-gray-500">Hubungi Kami</a></li>
                    @endif
                </ul>
            </div>

            {{-- 3. Layanan Pelanggan --}}
            <div class="space-y-6">
                <h4 class="text-gray-900 font-bold text-sm tracking-wide">Kontak</h4>
                <ul class="space-y-3 text-sm font-medium text-gray-500">
                    <li class="flex items-center gap-2">
                        <i class="fas fa-envelope text-blue-500 w-4"></i> 
                        <span>{{ $setting?->email ?? 'cs@kataloque.id' }}</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fab fa-whatsapp text-green-500 w-4"></i> 
                        <span>{{ $setting?->whatsapp ?? '+62 812' }}</span>
                    </li>
                </ul>
            </div>

            {{-- 4. Marketplace (BAGIAN YANG ERROR TADI) --}}
            <div class="space-y-4">
                <h4 class="text-gray-900 font-bold text-sm tracking-wide">Marketplace</h4>
                <div class="flex flex-wrap gap-2 text-gray-600">
                    @if($setting?->marketplaces && (is_array($setting->marketplaces) || is_object($setting->marketplaces)))
                        @foreach($setting->marketplaces as $platform => $urlData)
                            @php
                                // PROTEKSI: Jika $urlData ternyata array, ambil string di dalamnya. Jika bukan, pakai langsung.
                                $url = is_array($urlData) ? ($urlData['url'] ?? '#') : $urlData;
                                
                                $colorStyles = match($platform) {
                                    'shopee' => 'hover:bg-orange-50 hover:border-[#EE4D2D] hover:text-[#EE4D2D]',
                                    'tokopedia' => 'hover:bg-emerald-50 hover:border-[#42B549] hover:text-[#42B549]',
                                    'lazada' => 'hover:bg-blue-50 hover:border-[#0F146D] hover:text-[#0F146D]',
                                    default => 'hover:bg-gray-100 hover:border-gray-400'
                                };
                            @endphp
                            
                            {{-- Pastikan hanya merender jika $url bukan array --}}
                            @if(!is_array($url))
                                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" 
                                   class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs font-semibold transition-all duration-300 {{ $colorStyles }}">
                                    {{ ucfirst($platform) }}
                                </a>
                            @endif
                        @endforeach
                    @else
                        <span class="text-xs text-gray-400 italic">Belum ada marketplace tersedia</span>
                    @endif
                </div>
            </div>

        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pt-6 border-t border-gray-100">
            <p class="text-center text-xs font-medium text-gray-400">
                &copy; {{ date('Y') }} {{ $setting?->shop_name ?? 'Kataloque' }}.
            </p>
        </div>
    </footer>
</div>
