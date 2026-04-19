@extends('admin.layouts.app')

@section('title', 'Pengaturan Toko')
@section('header', 'Pengaturan Toko')

@section('content')
<div class="space-y-6 pb-20">
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-gray-400 mb-6">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition-colors">Admin</a>
        <i class="ti ti-chevron-right text-[10px]"></i>
        <span class="text-gray-900 dark:text-white font-black">Pengaturan Toko</span>
    </nav>

    @if (session('status'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-700 px-4 py-3 text-sm font-semibold flex items-center gap-3 animate-in fade-in slide-in-from-bottom-4 duration-300 shadow-sm shadow-emerald-100 dark:shadow-none mb-6">
            <i class="ti ti-check bg-emerald-600 text-white rounded-full p-1 text-[10px]"></i>
            {{ session('status') }}
        </div>
    @endif

    <div x-data="{ 
        activeTab: new URLSearchParams(window.location.search).get('tab') || 'branding',
        setTab(tab) {
            this.activeTab = tab;
            const url = new URL(window.location);
            url.searchParams.set('tab', tab);
            window.history.pushState({}, '', url);
        }
    }">
        <!-- Settings Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-6 no-scrollbar border-b border-gray-100 dark:border-gray-800">
            @php
                $tabs = [
                    'branding' => ['label' => 'Branding', 'icon' => 'ti ti-palette'],
                    'marketplace' => ['label' => 'Marketplace', 'icon' => 'ti ti-shopping-cart'],
                    'navigasi' => ['label' => 'Navigasi', 'icon' => 'ti ti-map-2'],
                    'seo' => ['label' => 'SEO', 'icon' => 'ti ti-search'],
                    'sistem' => ['label' => 'Sistem', 'icon' => 'ti ti-server'],
                ];
            @endphp

            @foreach($tabs as $key => $tab)
                <button type="button" @click="setTab('{{ $key }}')" 
                    class="flex items-center gap-2.5 px-6 py-3 rounded-2xl text-xs font-black uppercase tracking-widest transition-all duration-300 border"
                    :class="activeTab === '{{ $key }}' ? 'bg-blue-600 border-blue-600 text-white shadow-lg shadow-blue-500/30' : 'bg-white dark:bg-gray-900 border-gray-100 dark:border-gray-800 text-gray-400 hover:text-blue-600 hover:border-blue-100 dark:hover:border-blue-900/30'">
                    <i class="{{ $tab['icon'] }} text-sm"></i>
                    {{ $tab['label'] }}
                </button>
            @endforeach
        </div>

        <form method="POST" action="{{ $setting->exists ? route('admin.setting.update', $setting) : route('admin.setting.store') }}" enctype="multipart/form-data" class="space-y-6" id="settings-form">
            @csrf
            @if ($setting->exists)
                @method('PUT')
            @endif

        <!-- Section: BRANDING -->
        <div x-show="activeTab === 'branding'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-cloak class="space-y-6">


            <!-- Shop Identity Card -->
            <div class="bg-white dark:bg-gray-900 rounded-[2rem] border border-gray-200 dark:border-gray-800 shadow-sm animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="p-8 border-b border-gray-100 dark:border-gray-800 flex items-center gap-3">
                    <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                    <h3 class="text-xs font-black uppercase tracking-widest text-gray-900 dark:text-white">Konfigurasi Identitas</h3>
                </div>
                <div class="p-8 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 mb-2.5 uppercase tracking-widest">Nama Platform / Toko</label>
                            <input type="text" name="shop_name" required value="{{ old('shop_name', $setting->shop_name ?? 'Kataloque') }}" placeholder="Kataloque"
                                class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-2xl outline-none transition-all duration-300 text-sm font-bold p-4">
                            @error('shop_name') <p class="mt-2 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 mb-2.5 uppercase tracking-widest">Email Layanan Pelanggan</label>
                            <input type="email" name="email" value="{{ old('email', $setting->email) }}" placeholder="hello@kataloque.com"
                                class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-2xl outline-none transition-all duration-300 text-sm font-bold p-4">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 mb-2.5 uppercase tracking-widest">Nomor Kantor / Telp</label>
                            <input type="text" name="phone" value="{{ old('phone', $setting->phone) }}" placeholder="021-..."
                                class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-2xl outline-none transition-all duration-300 text-sm font-bold p-4">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 mb-2.5 uppercase tracking-widest">WhatsApp Business</label>
                            <input type="text" name="whatsapp" value="{{ old('whatsapp', $setting->whatsapp) }}" placeholder="+62..."
                                class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-2xl outline-none transition-all duration-300 text-sm font-bold p-4">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 mb-2.5 uppercase tracking-widest">Alamat Pusat / Kantor Offline</label>
                        <textarea name="shop_address" rows="3" placeholder="Jl. Raya Utama No. 123, Jakarta..."
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-2xl outline-none transition-all duration-300 text-sm font-bold p-4 resize-none leading-relaxed">{{ old('shop_address', $setting->shop_address) }}</textarea>
                    </div>
                </div>
            </div>
            <!-- Branding Assets Card -->
            <div class="bg-white dark:bg-gray-900 rounded-[2rem] border border-gray-200 dark:border-gray-800 shadow-sm animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="p-8 border-b border-gray-100 dark:border-gray-800 flex items-center gap-3">
                    <div class="w-1.5 h-6 bg-amber-500 rounded-full"></div>
                    <h3 class="text-xs font-black uppercase tracking-widest text-gray-900 dark:text-white">Aset Visual & Branding</h3>
                </div>
                <div class="p-8 space-y-12">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <!-- Logo Upload -->
                        <div x-data="{ photoName: null, photoPreview: null }">
                            <label class="block text-[10px] font-black text-gray-400 mb-4 uppercase tracking-widest">Logo Utama / Header Branding (Support SVG)</label>
                            <input type="file" name="shop_logo" class="hidden" x-ref="photo" x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            ">
                            <div class="relative group w-full aspect-video rounded-3xl bg-gray-50/50 dark:bg-gray-800/30 border-2 border-dashed border-gray-200 dark:border-gray-700 flex flex-col items-center justify-center overflow-hidden transition-all duration-300 hover:border-blue-600 hover:bg-white dark:hover:bg-gray-800"
                                    x-on:click.prevent="$refs.photo.click()" style="cursor: pointer;">
                                
                                <div class="absolute inset-0 flex items-center justify-center" x-show="photoPreview || '{{ $setting->shop_logo }}'">
                                    <img :src="photoPreview ?? '{{ $setting->shop_logo }}'" class="max-w-[70%] max-h-[70%] object-contain drop-shadow-md" alt="Logo Preview">
                                    <div class="absolute inset-0 bg-gray-900/60 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all duration-300 backdrop-blur-[2px]">
                                        <div class="bg-white/90 rounded-xl px-4 py-2 text-[10px] font-black uppercase tracking-widest text-gray-900 shadow-lg">Ganti Logo</div>
                                    </div>
                                </div>

                                <div x-show="!photoPreview && !'{{ $setting->shop_logo }}'" class="text-center animate-pulse">
                                    <i class="ti ti-camera-plus text-3xl text-gray-300 mb-2"></i>
                                    <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Pilih Logo</p>
                                </div>
                            </div>
                            @error('shop_logo') <p class="mt-2 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Favicon Upload -->
                        <div x-data="{ favName: null, favPreview: null }">
                            <label class="block text-[10px] font-black text-gray-400 mb-4 uppercase tracking-widest">Icon Browser (Favicon)</label>
                            <input type="file" name="favicon" class="hidden" x-ref="favicon_photo" x-on:change="
                                    favName = $refs.favicon_photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        favPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.favicon_photo.files[0]);
                            ">
                            <div class="flex flex-col sm:flex-row items-center gap-6">
                                <div class="relative group w-24 h-24 rounded-2xl bg-white dark:bg-gray-800 border-2 border-dashed border-gray-200 dark:border-gray-700 flex items-center justify-center overflow-hidden transition-all hover:border-blue-600 shadow-sm"
                                        x-on:click.prevent="$refs.favicon_photo.click()" style="cursor: pointer;">
                                    
                                    <div class="absolute inset-0 flex items-center justify-center" x-show="favPreview || '{{ $setting->favicon }}'">
                                        <img :src="favPreview ?? '{{ $setting->favicon }}'" class="w-12 h-12 object-contain" alt="Favicon Preview">
                                        <div class="absolute inset-0 bg-gray-900/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                                            <i class="ti ti-pencil text-white"></i>
                                        </div>
                                    </div>

                                    <div x-show="!favPreview && !'{{ $setting->favicon }}'" class="text-gray-300">
                                        <i class="ti ti-world text-2xl"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-[11px] font-bold text-gray-700 dark:text-gray-200 mb-2">Sangat Penting!</h4>
                                    <p class="text-[10px] text-gray-400 leading-relaxed">Gunakan format <span class="font-mono text-gray-900 dark:text-white">.ico</span> atau <span class="font-mono text-gray-900 dark:text-white">.png</span> transparan. Ukuran ideal 32x32 piksel.</p>
                                </div>
                            </div>
                            @error('favicon') <p class="mt-2 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Social Media Section -->
                    <div class="pt-10 border-t border-gray-100 dark:border-gray-800" x-data="{ 
                        socials: {{ json_encode(old('social_media', $setting->social_media ?? [
                            ['platform' => 'instagram', 'username' => $setting->instagram ?? ''],
                            ['platform' => 'facebook', 'username' => $setting->facebook ?? '']
                        ])) }},
                        addSocial() {
                            this.socials.push({ platform: 'instagram', username: '' });
                        },
                        removeSocial(index) {
                            this.socials.splice(index, 1);
                        }
                    }">
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex items-center gap-3">
                                <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                                <h3 class="text-xs font-black uppercase tracking-widest text-gray-900 dark:text-white">Eksistensi Media Sosial</h3>
                            </div>
                            <button type="button" @click="addSocial()" class="flex items-center gap-1.5 text-[10px] font-black uppercase tracking-widest text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 px-6 py-2.5 rounded-xl border border-blue-100 dark:border-blue-900/30 transition-all shadow-sm">
                                <i class="ti ti-plus text-xs"></i>
                                Tambah Platform
                            </button>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <template x-for="(social, index) in socials" :key="index">
                                <div x-data="{ open: false }" 
                                     class="group relative bg-gray-50/50 dark:bg-gray-800/30 border border-gray-200 dark:border-gray-700 rounded-2xl p-4 transition-all duration-300 hover:border-blue-600/30 hover:bg-white dark:hover:bg-gray-900"
                                     :class="open ? 'z-[150] ring-2 ring-blue-600/10 bg-white dark:bg-gray-900' : 'z-10'">
                                    <div class="flex items-center gap-4">
                                        <!-- Modern Custom Dropdown -->
                                        <div class="relative flex-shrink-0">
                                            <button type="button" @click="open = !open" @click.away="open = false" 
                                                class="flex items-center gap-2 px-3 py-1.5 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-sm hover:border-blue-500 transition-all">
                                                <template x-if="social.platform === 'instagram'"><i class="ti ti-brand-instagram text-pink-600"></i></template>
                                                <template x-if="social.platform === 'facebook'"><i class="ti ti-brand-facebook text-blue-600"></i></template>
                                                <template x-if="social.platform === 'twitter'"><i class="ti ti-brand-twitter text-blue-400"></i></template>
                                                <template x-if="social.platform === 'tiktok'"><i class="ti ti-brand-tiktok text-gray-900 dark:text-white"></i></template>
                                                <template x-if="social.platform === 'youtube'"><i class="ti ti-brand-youtube text-red-600"></i></template>
                                                <template x-if="social.platform === 'website'"><i class="ti ti-world text-gray-600"></i></template>
                                                <span class="text-[10px] font-black uppercase tracking-widest text-gray-700 dark:text-gray-300" x-text="social.platform"></span>
                                                <i class="ti ti-chevron-down text-[10px] text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                                            </button>
                                            
                                            <div x-show="open" 
                                                 x-transition:enter="transition ease-out duration-100"
                                                 x-transition:enter-start="opacity-0 scale-95"
                                                 x-transition:enter-end="opacity-100 scale-100"
                                                 class="absolute left-0 mt-2 w-40 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-xl z-50 overflow-hidden"
                                                 x-cloak>
                                                <div class="p-1">
                                                    <template x-for="p in ['instagram', 'facebook', 'twitter', 'tiktok', 'youtube', 'website']">
                                                        <button type="button" @click="social.platform = p; open = false" 
                                                            class="flex items-center gap-3 w-full px-3 py-2 text-left rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                                            <i :class="{
                                                                'ti ti-brand-instagram text-pink-600': p === 'instagram',
                                                                'ti ti-brand-facebook text-blue-600': p === 'facebook',
                                                                'ti ti-brand-twitter text-blue-400': p === 'twitter',
                                                                'ti ti-brand-tiktok text-gray-900 dark:text-white': p === 'tiktok',
                                                                'ti ti-brand-youtube text-red-600': p === 'youtube',
                                                                'ti ti-world text-gray-600': p === 'website'
                                                            }" class="text-base"></i>
                                                            <span class="text-[10px] font-black uppercase tracking-widest text-gray-600 dark:text-gray-400" x-text="p"></span>
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>
                                            <input type="hidden" :name="'social_media['+index+'][platform]'" x-model="social.platform">
                                        </div>

                                        <div class="h-8 w-px bg-gray-100 dark:bg-gray-800"></div>

                                        <div class="flex-1">
                                            <input type="text" :name="'social_media['+index+'][username]'" x-model="social.username" placeholder="Username / ID / URL"
                                                class="w-full bg-transparent border-none focus:ring-0 text-xs font-bold p-0 text-gray-900 dark:text-white placeholder-gray-400">
                                        </div>

                                        <button type="button" @click="removeSocial(index)" class="p-2 text-gray-300 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-xl transition-all">
                                            <i class="ti ti-trash text-base"></i>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                </div>
            </div>

        </div> <!-- End Section: BRANDING -->

        <!-- Section: MARKETPLACE -->
        <div x-show="activeTab === 'marketplace'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
            <!-- Marketplaces Card -->
            <div class="bg-white dark:bg-gray-900 rounded-[2rem] border border-gray-200 dark:border-gray-800 shadow-sm animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="p-8 border-b border-gray-100 dark:border-gray-800 flex items-center gap-3">
                    <div class="w-1.5 h-6 bg-emerald-500 rounded-full"></div>
                    <h3 class="text-xs font-black uppercase tracking-widest text-gray-900 dark:text-white">Ekosistem Belanja</h3>
                </div>
                <div class="p-8" x-data="{ 
                    marketplaces: {{ json_encode(old('marketplaces', collect($setting->marketplaces ?? [])->map(function($url, $key) { 
                        return ['platform' => $key, 'url' => $url]; 
                    })->values()->all() ?: [['platform' => 'shopee', 'url' => ''], ['platform' => 'tokopedia', 'url' => '']])) }},
                    addMarketplace() {
                        this.marketplaces.push({ platform: 'shopee', url: '' });
                    },
                    removeMarketplace(index) {
                        this.marketplaces.splice(index, 1);
                    }
                }">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex flex-col gap-1">
                            <h4 class="text-[11px] font-black uppercase tracking-wider text-gray-400">Daftar Toko Online</h4>
                            <p class="text-[10px] text-gray-400">Hubungkan toko anda ke berbagai ekosistem e-commerce.</p>
                        </div>
                        <button type="button" @click="addMarketplace()" class="flex items-center gap-1.5 text-[10px] font-black uppercase tracking-widest text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 px-6 py-2.5 rounded-xl border border-emerald-100 dark:border-emerald-900/30 transition-all shadow-sm">
                            <i class="ti ti-plus text-xs"></i>
                            Tambah Toko
                        </button>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <template x-for="(market, index) in marketplaces" :key="index">
                            <div x-data="{ open: false }" 
                                 class="group relative bg-gray-50/50 dark:bg-gray-800/30 border border-gray-200 dark:border-gray-700 rounded-2xl p-4 transition-all duration-300 hover:border-emerald-600/30 hover:bg-white dark:hover:bg-gray-900"
                                 :class="open ? 'z-[150] ring-2 ring-emerald-600/10 bg-white dark:bg-gray-900' : 'z-10'">
                                <div class="flex items-center gap-4">
                                    <!-- Dynamic Platform Selector -->
                                    <div class="relative flex-shrink-0">
                                        <button type="button" @click="open = !open" @click.away="open = false" 
                                            class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-sm hover:border-emerald-500 transition-all min-w-[140px]">
                                            <template x-if="market.platform === 'shopee'"><i class="fas fa-shopping-bag text-[#EE4D2D] text-sm"></i></template>
                                            <template x-if="market.platform === 'tokopedia'"><i class="fas fa-bag-shopping text-[#42B549] text-sm"></i></template>
                                            <template x-if="market.platform === 'tiktok'"><i class="fab fa-tiktok text-gray-900 dark:text-white text-sm"></i></template>
                                            <template x-if="market.platform === 'lazada'"><i class="fas fa-heart text-[#F3209B] text-sm"></i></template>
                                            <template x-if="market.platform === 'blibli'"><i class="fas fa-shopping-basket text-[#0095DC] text-sm"></i></template>
                                            <span class="text-[10px] font-black uppercase tracking-widest text-gray-700 dark:text-gray-300" x-text="market.platform"></span>
                                            <i class="ti ti-chevron-down text-[10px] text-gray-400 ms-auto transition-transform" :class="open ? 'rotate-180' : ''"></i>
                                        </button>
                                        
                                        <div x-show="open" 
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="opacity-0 scale-95"
                                             x-transition:enter-end="opacity-100 scale-100"
                                             class="absolute left-0 mt-2 w-48 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-xl z-50 overflow-hidden"
                                             x-cloak>
                                            <div class="p-1">
                                                <template x-for="p in ['shopee', 'tokopedia', 'tiktok', 'lazada', 'blibli']">
                                                    <button type="button" @click="market.platform = p; open = false" 
                                                        class="flex items-center gap-3 w-full px-3 py-2.5 text-left rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                                        <i :class="{
                                                            'fas fa-shopping-bag text-[#EE4D2D]': p === 'shopee',
                                                            'fas fa-bag-shopping text-[#42B549]': p === 'tokopedia',
                                                            'fab fa-tiktok text-gray-900 dark:text-white': p === 'tiktok',
                                                            'fas fa-heart text-[#F3209B]': p === 'lazada',
                                                            'fas fa-shopping-basket text-[#0095DC]': p === 'blibli'
                                                        }" class="text-sm"></i>
                                                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-600 dark:text-gray-400" x-text="p"></span>
                                                    </button>
                                                </template>
                                            </div>
                                        </div>
                                        <!-- Hidden inputs to keep Laravel compatible with the current update logic -->
                                        <input type="hidden" :name="'marketplaces['+market.platform+']'" x-model="market.url">
                                    </div>

                                    <div class="h-8 w-px bg-gray-100 dark:bg-gray-800"></div>

                                    <div class="flex-1">
                                        <input type="url" x-model="market.url" placeholder="Paste link toko kamu di sini..."
                                            class="w-full bg-transparent border-none focus:ring-0 text-xs font-bold p-0 text-gray-900 dark:text-white placeholder-gray-400">
                                    </div>

                                    <button type="button" @click="removeMarketplace(index)" class="p-2 text-gray-300 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-xl transition-all">
                                        <i class="ti ti-trash text-base"></i>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Payment Methods Section -->
                    <div class="pt-10 border-t border-gray-100 dark:border-gray-800">
                        <div class="flex flex-col gap-1 mb-8">
                            <h4 class="text-[11px] font-black uppercase tracking-wider text-gray-400">Metode Pembayaran (Footer)</h4>
                            <p class="text-[10px] text-gray-400">Pilih metode pembayaran yang akan ditampilkan sebagai ikon informasi di footer.</p>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                            @php
                                $commonMethods = ['BCA', 'Mandiri', 'BNI', 'BRI', 'BSI', 'QRIS', 'GoPay', 'OVO', 'Dana', 'LinkAja', 'ShopeePay'];
                                $selectedMethods = $setting->payment_methods ?? ['BCA', 'BNI', 'GOPAY'];
                            @endphp
                            @foreach($commonMethods as $method)
                                <label class="group relative flex items-center gap-3 p-3 rounded-2xl border transition-all duration-300 cursor-pointer 
                                    {{ in_array($method, $selectedMethods) 
                                        ? 'bg-emerald-50 border-emerald-200 dark:bg-emerald-900/20 dark:border-emerald-800' 
                                        : 'bg-gray-50/50 border-gray-100 dark:bg-gray-800/30 dark:border-gray-700 hover:border-emerald-200' }}">
                                    <div class="relative flex items-center justify-center">
                                        <input type="checkbox" name="payment_methods[]" value="{{ $method }}" 
                                            @checked(in_array($method, $selectedMethods))
                                            class="peer h-5 w-5 cursor-pointer appearance-none rounded-md border border-gray-300 dark:border-gray-600 checked:bg-emerald-600 checked:border-emerald-600 transition-all">
                                        <i class="ti ti-check absolute text-[10px] text-white opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                                    </div>
                                    <span class="text-xs font-black uppercase tracking-tight {{ in_array($method, $selectedMethods) ? 'text-emerald-900 dark:text-emerald-400' : 'text-gray-500' }}">
                                        {{ $method }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- End Section: MARKETPLACE -->

        <!-- Section: NAVIGASI -->
        <div x-show="activeTab === 'navigasi'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
            <!-- Navigation Card -->
            <div class="space-y-6 animate-in fade-in slide-in-from-bottom-4 duration-500">

                <!-- Trending Keywords -->
                <div class="bg-white dark:bg-gray-900 rounded-[2rem] border border-gray-200 dark:border-gray-800 shadow-sm" x-data="{ 
                    items: {{ json_encode(old('trending_keywords', !empty($setting->trending_keywords) ? $setting->trending_keywords : [
                        ['keyword' => 'iPhone 15 Pro', 'url' => ''],
                        ['keyword' => 'Samsung S24 Ultra', 'url' => ''],
                        ['keyword' => 'MacBook Pro M3', 'url' => ''],
                        ['keyword' => 'Sony WH-1000XM5', 'url' => ''],
                        ['keyword' => 'Logitech G Pro', 'url' => ''],
                        ['keyword' => 'iPad Pro M2', 'url' => '']
                    ])) }},
                    addItem() { this.items.push({ keyword: '', url: '' }); },
                    removeItem(index) { this.items.splice(index, 1); }
                }">
                    <div class="p-8 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                            <h3 class="text-xs font-black uppercase tracking-widest text-gray-900 dark:text-white">Trending Keywords (Header Tag)</h3>
                        </div>
                        <button type="button" @click="addItem()" class="flex items-center gap-1.5 text-[10px] font-black uppercase tracking-widest text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 px-6 py-2.5 rounded-xl border border-blue-100 dark:border-blue-900/30 transition-all shadow-sm">
                            <i class="ti ti-plus text-xs"></i>
                            Tambah Keyword
                        </button>
                    </div>
                    <div class="p-8 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="flex items-center gap-3 bg-gray-50/50 dark:bg-gray-800/30 border border-gray-200 dark:border-gray-700 rounded-2xl p-3">
                                    <div class="flex-1 space-y-2">
                                        <input type="text" :name="'trending_keywords['+index+'][keyword]'" x-model="item.keyword" placeholder="Keyword (ex: iPhone 15 Pro)"
                                            class="w-full bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl px-4 py-2 text-xs font-bold">
                                        <input type="text" :name="'trending_keywords['+index+'][url]'" x-model="item.url" placeholder="URL Target (kosongkan untuk pencarian otomatis)"
                                            class="w-full bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl px-4 py-2 text-[10px] font-medium text-gray-400">
                                    </div>
                                    <button type="button" @click="removeItem(index)" class="p-2 text-gray-300 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-xl transition-all">
                                        <i class="ti ti-trash text-base"></i>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Header Navigation -->
                <div class="bg-white dark:bg-gray-900 rounded-[2rem] border border-gray-200 dark:border-gray-800 shadow-sm" x-data="{ 
                    items: {{ json_encode(old('header_navigation', !empty($setting->header_navigation) ? $setting->header_navigation : [
                        ['label' => 'Tentang Kami', 'url' => '/tentang-kami'],
                        ['label' => 'Blog & Edukasi', 'url' => '/blog'],
                        ['label' => 'Cara Order', 'url' => '/cara-pesan']
                    ])) }},
                    addItem() { this.items.push({ label: '', url: '' }); },
                    removeItem(index) { this.items.splice(index, 1); }
                }">
                    <div class="p-8 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-1.5 h-6 bg-indigo-600 rounded-full"></div>
                            <h3 class="text-xs font-black uppercase tracking-widest text-gray-900 dark:text-white">Menu Navigasi Header (Top Bar)</h3>
                        </div>
                        <button type="button" @click="addItem()" class="flex items-center gap-1.5 text-[10px] font-black uppercase tracking-widest text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 px-6 py-2.5 rounded-xl border border-indigo-100 dark:border-indigo-900/30 transition-all shadow-sm">
                            <i class="ti ti-plus text-xs"></i>
                            Tambah Menu
                        </button>
                    </div>
                    <div class="p-8 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="flex items-center gap-3 bg-gray-50/50 dark:bg-gray-800/30 border border-gray-200 dark:border-gray-700 rounded-2xl p-3">
                                    <div class="flex-1 space-y-2">
                                        <input type="text" :name="'header_navigation['+index+'][label]'" x-model="item.label" placeholder="Label Menu (ex: Tentang Kami)"
                                            class="w-full bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl px-4 py-2 text-xs font-bold">
                                        <input type="text" :name="'header_navigation['+index+'][url]'" x-model="item.url" placeholder="URL Target"
                                            class="w-full bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl px-4 py-2 text-[10px] font-medium text-gray-400">
                                    </div>
                                    <button type="button" @click="removeItem(index)" class="p-2 text-gray-300 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-xl transition-all">
                                        <i class="ti ti-trash text-base"></i>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Footer Quick Links -->
                <div class="bg-white dark:bg-gray-900 rounded-[2rem] border border-gray-200 dark:border-gray-800 shadow-sm" x-data="{ 
                    items: {{ json_encode(old('footer_navigation', !empty($setting->footer_navigation) ? $setting->footer_navigation : [
                        ['label' => 'Tentang Kami', 'url' => '/tentang-kami'],
                        ['label' => 'Blog', 'url' => '/blog'],
                        ['label' => 'Cara Belanja', 'url' => '/cara-pesan'],
                        ['label' => 'Metode Pembayaran', 'url' => '/pembayaran']
                    ])) }},
                    addItem() { this.items.push({ label: '', url: '' }); },
                    removeItem(index) { this.items.splice(index, 1); }
                }">
                    <div class="p-8 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-1.5 h-6 bg-emerald-600 rounded-full"></div>
                            <h3 class="text-xs font-black uppercase tracking-widest text-gray-900 dark:text-white">Quick Links Footer (Tautan Cepat)</h3>
                        </div>
                        <button type="button" @click="addItem()" class="flex items-center gap-1.5 text-[10px] font-black uppercase tracking-widest text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 px-6 py-2.5 rounded-xl border border-emerald-100 dark:border-emerald-900/30 transition-all shadow-sm">
                            <i class="ti ti-plus text-xs"></i>
                            Tambah Link
                        </button>
                    </div>
                    <div class="p-8 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="flex items-center gap-3 bg-gray-50/50 dark:bg-gray-800/30 border border-gray-200 dark:border-gray-700 rounded-2xl p-3">
                                    <div class="flex-1 space-y-2">
                                        <input type="text" :name="'footer_navigation['+index+'][label]'" x-model="item.label" placeholder="Label Link"
                                            class="w-full bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl px-4 py-2 text-xs font-bold">
                                        <input type="text" :name="'footer_navigation['+index+'][url]'" x-model="item.url" placeholder="URL Target"
                                            class="w-full bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl px-4 py-2 text-[10px] font-medium text-gray-400">
                                    </div>
                                    <button type="button" @click="removeItem(index)" class="p-2 text-gray-300 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-xl transition-all">
                                        <i class="ti ti-trash text-base"></i>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- End Section: NAVIGASI -->

        <!-- Section: SEO -->
        <div x-show="activeTab === 'seo'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
            <div class="space-y-6 animate-in fade-in slide-in-from-bottom-4 duration-500">
                <!-- Meta Information Group -->
                <div class="bg-white dark:bg-gray-900 rounded-[2rem] border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-8 border-b border-gray-100 dark:border-gray-800 flex items-center gap-3">
                        <div class="w-1.5 h-6 bg-indigo-600 rounded-full"></div>
                        <h3 class="text-xs font-black uppercase tracking-widest text-gray-900 dark:text-white">Informasi Meta Utama</h3>
                    </div>
                    <div class="p-8 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 mb-2.5 uppercase tracking-widest">SEO Title Bar / Meta Title</label>
                                <input type="text" name="seo_settings[seo_title]" value="{{ old('seo_settings.seo_title', $setting->seo_settings['seo_title'] ?? $setting->shop_name ?? 'Kataloque - Digital Catalog') }}" placeholder="Kataloque - Digital Catalog & Marketplace"
                                    class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 rounded-2xl outline-none transition-all duration-300 text-sm font-bold p-4">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 mb-2.5 uppercase tracking-widest">Global Meta Keywords</label>
                                <input type="text" name="seo_settings[seo_keywords]" value="{{ old('seo_settings.seo_keywords', $setting->seo_settings['seo_keywords'] ?? '') }}" placeholder="katalog, belanja, ecommerce, online shop"
                                    class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 rounded-2xl outline-none transition-all duration-300 text-sm font-bold p-4">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 mb-2.5 uppercase tracking-widest">Global Meta Description</label>
                            <textarea name="shop_description" rows="3" placeholder="Masukan deskripsi singkat untuk Google..."
                                class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 rounded-2xl outline-none transition-all duration-300 text-sm font-bold p-4 leading-relaxed">{{ old('shop_description', $setting->shop_description ?? 'Satu destinasi untuk semua kebutuhan gaya hidup Anda. Belanja cerdas, cepat, dan aman hanya di ' . ($setting->shop_name ?? 'Kataloque')) }}</textarea>
                            <p class="mt-2 text-[10px] text-gray-400">Deskripsi ini akan muncul di hasil pencarian Google (Ideal: 150-160 karakter).</p>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 mb-2.5 uppercase tracking-widest">Footer Copy (Copyright)</label>
                            <input type="text" name="footer_text" value="{{ old('footer_text', $setting->footer_text) }}" placeholder="© 2024 Kataloque."
                                class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 rounded-2xl outline-none transition-all duration-300 text-sm font-bold p-4">
                        </div>
                    </div>
                </div>

                <!-- Social Shared Image & Cards -->
                <div class="bg-white dark:bg-gray-900 rounded-[2rem] border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-8 border-b border-gray-100 dark:border-gray-800 flex items-center gap-3">
                        <div class="w-1.5 h-6 bg-rose-500 rounded-full"></div>
                        <h3 class="text-xs font-black uppercase tracking-widest text-gray-900 dark:text-white">Pencitraan Sosial (Open Graph)</h3>
                    </div>
                    <div class="p-8 space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            <div x-data="{ ogPreview: null }">
                                <label class="block text-[10px] font-black text-gray-400 mb-4 uppercase tracking-widest">Shared Image (WA/FB/Twitter)</label>
                                <input type="file" name="seo_settings[og_image]" class="hidden" x-ref="og_photo" x-on:change="
                                        const reader = new FileReader();
                                        reader.onload = (e) => { ogPreview = e.target.result; };
                                        reader.readAsDataURL($refs.og_photo.files[0]);
                                ">
                                <div class="relative group w-full aspect-video rounded-3xl bg-gray-50/50 dark:bg-gray-800/30 border-2 border-dashed border-gray-200 dark:border-gray-700 flex flex-col items-center justify-center overflow-hidden transition-all duration-300 hover:border-rose-500 hover:bg-white dark:hover:bg-gray-800 shadow-inner"
                                        x-on:click.prevent="$refs.og_photo.click()" style="cursor: pointer;">
                                    
                                    <div class="absolute inset-0 flex items-center justify-center" x-show="ogPreview || '{{ $setting->seo_settings['og_image'] ?? '' }}'">
                                        <img :src="ogPreview ?? '{{ $setting->seo_settings['og_image'] ?? '' }}'" class="w-full h-full object-cover" alt="OG Preview">
                                        <div class="absolute inset-0 bg-gray-900/60 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all duration-300 backdrop-blur-[2px]">
                                            <div class="bg-white/90 rounded-xl px-4 py-2 text-[10px] font-black uppercase tracking-widest text-gray-900 shadow-lg">Ganti Preview</div>
                                        </div>
                                    </div>

                                    <div x-show="!ogPreview && !'{{ $setting->seo_settings['og_image'] ?? '' }}'" class="text-center">
                                        <i class="ti ti-share-off text-3xl text-gray-300 mb-2"></i>
                                        <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest leading-tight px-6">Upload Gambar Berbagi<br><span class="opacity-50">(Ideal: 1200x630px)</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 mb-2.5 uppercase tracking-widest">Twitter Card Type</label>
                                    <select name="seo_settings[twitter_card]" 
                                        class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-rose-500 rounded-2xl outline-none transition-all duration-300 text-sm font-bold p-4">
                                        <option value="summary" @selected(($setting->seo_settings['twitter_card'] ?? '') === 'summary')>Summary</option>
                                        <option value="summary_large_image" @selected(($setting->seo_settings['twitter_card'] ?? '') === 'summary_large_image')>Summary with Large Image</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 mb-2.5 uppercase tracking-widest">Author / Pemilik</label>
                                    <input type="text" name="seo_settings[author]" value="{{ old('seo_settings.author', $setting->seo_settings['author'] ?? '') }}" placeholder="Kataloque Studio"
                                        class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-rose-500 rounded-2xl outline-none transition-all duration-300 text-sm font-bold p-4">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Webmaster Verification Card -->
                <div class="bg-white dark:bg-gray-900 rounded-[2rem] border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-8 border-b border-gray-100 dark:border-gray-800 flex items-center gap-3">
                        <div class="w-1.5 h-6 bg-emerald-500 rounded-full"></div>
                        <h3 class="text-xs font-black uppercase tracking-widest text-gray-900 dark:text-white">Verifikasi Webmaster (Search Console)</h3>
                    </div>
                    <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 mb-2.5 uppercase tracking-widest flex items-center gap-2">
                                <i class="ti ti-brand-google text-blue-600"></i>
                                Google Search Console
                            </label>
                            <input type="text" name="seo_settings[google_verification]" value="{{ old('seo_settings.google_verification', $setting->seo_settings['google_verification'] ?? '') }}" placeholder="A-B-C-123"
                                class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-emerald-500 rounded-2xl outline-none transition-all duration-300 text-sm font-bold p-4">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 mb-2.5 uppercase tracking-widest flex items-center gap-2">
                                <i class="ti ti-brand-bing text-blue-400"></i>
                                Bing Webmaster
                            </label>
                            <input type="text" name="seo_settings[bing_verification]" value="{{ old('seo_settings.bing_verification', $setting->seo_settings['bing_verification'] ?? '') }}" placeholder="A-B-C-123"
                                class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-emerald-500 rounded-2xl outline-none transition-all duration-300 text-sm font-bold p-4">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 mb-2.5 uppercase tracking-widest flex items-center gap-2">
                                <i class="ti ti-letter-y text-red-600"></i>
                                Yandex Webmaster
                            </label>
                            <input type="text" name="seo_settings[yandex_verification]" value="{{ old('seo_settings.yandex_verification', $setting->seo_settings['yandex_verification'] ?? '') }}" placeholder="A-B-C-123"
                                class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-emerald-500 rounded-2xl outline-none transition-all duration-300 text-sm font-bold p-4">
                        </div>
                    </div>
                </div>

                <!-- Robots Config Card -->
                <div class="bg-white dark:bg-gray-900 rounded-[2rem] border border-gray-200 dark:border-gray-800 shadow-sm p-8 overflow-hidden">
                    <div class="flex items-center gap-6">
                        <div class="flex-1">
                            <h4 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest">Robots Indexing</h4>
                            <p class="text-[10px] text-gray-500 mt-1 uppercase tracking-wider">Tentukan apakah website ini boleh di-crawl oleh mesin pencari.</p>
                        </div>
                        <select name="seo_settings[robots]" 
                            class="bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl px-6 py-2.5 text-[11px] font-black uppercase tracking-widest outline-none focus:ring-2 focus:ring-indigo-600/20 transition-all">
                            <option value="index, follow" @selected(($setting->seo_settings['robots'] ?? '') === 'index, follow')>Index, Follow</option>
                        </select>
                    </div>
                </div>
            </div>
        </div> <!-- End Section: SEO -->

        <!-- Section: SISTEM -->
        <div x-show="activeTab === 'sistem'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
            <div class="space-y-6 animate-in fade-in slide-in-from-bottom-4 duration-500">
                <!-- Maintenance Mode Card -->
                <div class="bg-white dark:bg-gray-900 rounded-[2rem] border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-8 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-1.5 h-6 bg-rose-600 rounded-full"></div>
                            <h3 class="text-xs font-black uppercase tracking-widest text-gray-900 dark:text-white">Mode Pemeliharaan (Maintenance)</h3>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="hidden" name="is_maintenance" value="0">
                            <input type="checkbox" name="is_maintenance" value="1" @checked($setting->is_maintenance) class="sr-only peer">
                            <div class="w-12 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-rose-600"></div>
                        </label>
                    </div>
                    <div class="p-8 space-y-4">
                        <div class="p-4 bg-rose-50 dark:bg-rose-900/10 rounded-2xl border border-rose-100 dark:border-rose-900/20 flex gap-4">
                            <div class="w-10 h-10 bg-rose-600 text-white rounded-xl flex items-center justify-center shrink-0 shadow-lg shadow-rose-200 dark:shadow-none">
                                <i class="ti ti-tool"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-black text-rose-900 dark:text-rose-400 uppercase tracking-widest mb-1">Perhatian!</h4>
                                <p class="text-[10px] text-rose-700 dark:text-rose-500 leading-relaxed font-bold">Saat mode pemeliharaan aktif, pengunjung tidak akan bisa mengakses katalog Anda. Pastikan Anda sudah mengisi pesan informasi di bawah.</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-gray-400 mb-2.5 uppercase tracking-widest">Pesan Pemeliharaan</label>
                            <textarea name="system_settings[maintenance_message]" rows="3" placeholder="Maaf, kami sedang melakukan perbaikan sistem..."
                                class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-rose-600 focus:ring-4 focus:ring-rose-600/10 rounded-2xl outline-none transition-all duration-300 text-sm font-bold p-4 resize-none leading-relaxed">{{ old('system_settings.maintenance_message', $setting->system_settings['maintenance_message'] ?? 'Maaf, saat ini sistem kami sedang dalam tahap pemeliharaan rutin untuk meningkatkan layanan. Kami akan segera kembali!') }}</textarea>
                        </div>
                    </div>
                </div>



                <!-- Marketing & Tracking Card -->
                <div class="bg-white dark:bg-gray-900 rounded-[2rem] border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-8 border-b border-gray-100 dark:border-gray-800 flex items-center gap-3">
                        <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                        <h3 class="text-xs font-black uppercase tracking-widest text-gray-900 dark:text-white">Marketing & Tracking ID</h3>
                    </div>
                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 mb-2.5 uppercase tracking-widest flex items-center gap-2">
                                <i class="ti ti-brand-google text-blue-600"></i>
                                Google Analytics ID (G-XXXX)
                            </label>
                            <input type="text" name="system_settings[google_analytics_id]" value="{{ old('system_settings.google_analytics_id', $setting->system_settings['google_analytics_id'] ?? '') }}" placeholder="G-ABC123XYZ"
                                class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 rounded-2xl outline-none transition-all duration-300 text-sm font-bold p-4">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 mb-2.5 uppercase tracking-widest flex items-center gap-2">
                                <i class="ti ti-brand-facebook text-blue-600"></i>
                                Facebook Pixel ID
                            </label>
                            <input type="text" name="system_settings[facebook_pixel_id]" value="{{ old('system_settings.facebook_pixel_id', $setting->system_settings['facebook_pixel_id'] ?? '') }}" placeholder="1234567890"
                                class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 rounded-2xl outline-none transition-all duration-300 text-sm font-bold p-4">
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- End Section: SISTEM -->

        <!-- Sticky Bottom Actions -->
        <div class="fixed bottom-0 right-0 z-[100] transition-all duration-300 bg-white/80 dark:bg-gray-900/80 backdrop-blur-lg border-t border-gray-200 dark:border-gray-800 p-4"
            x-data="{ isExpanded: $store.sidebar.isExpanded }"
            :class="isExpanded ? 'xl:left-72' : 'xl:left-20 left-0'">
            <div class="flex flex-row items-center justify-end gap-3 px-3 sm:px-6">
                <button type="button" @click="location.reload()" 
                    class="px-8 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                    Reset
                </button>
                <button type="submit" 
                    class="px-14 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700 transition shadow-[0_10px_20px_-5px_rgba(37,99,235,0.4)] dark:shadow-none">
                    Terapkan Perubahan
                </button>
            </div>
        </div>
    </form>
    </div>
</div>
@endsection
