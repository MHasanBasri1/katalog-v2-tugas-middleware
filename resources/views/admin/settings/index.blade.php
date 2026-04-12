@extends('admin.layouts.app')

@section('title', 'Pengaturan Toko')
@section('header', 'Pengaturan Toko')

@section('content')
<div class="space-y-6 pb-20">
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-gray-400 mb-6">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition-colors">Admin</a>
        <i class="ti ti-chevron-right text-[10px]"></i>
        <a href="{{ route('admin.setting.index') }}" class="hover:text-blue-600 transition-colors">Pengaturan</a>
        <i class="ti ti-chevron-right text-[10px]"></i>
        <span class="text-gray-900 dark:text-white font-black">{{ ucfirst($section) }}</span>
    </nav>

    @if (session('status'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-700 px-4 py-3 text-sm font-semibold flex items-center gap-3 animate-in fade-in slide-in-from-bottom-4 duration-300 shadow-sm shadow-emerald-100 dark:shadow-none">
            <i class="ti ti-check bg-emerald-600 text-white rounded-full p-1 text-[10px]"></i>
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ $setting->exists ? route('admin.setting.update', $setting) : route('admin.setting.store') }}" enctype="multipart/form-data" class="space-y-6" id="settings-form">
        @csrf
        @if ($setting->exists)
            @method('PUT')
        @endif

        @if($section === 'umum')
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
                            <input type="text" name="shop_name" required value="{{ old('shop_name', $setting->shop_name) }}" placeholder="Kataloque"
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

                    <div class="pt-8 border-t border-gray-100 dark:border-gray-800" x-data="{ 
                        socials: {{ json_encode($setting->social_media ?? [
                            ['platform' => 'instagram', 'username' => $setting->instagram ?? ''],
                            ['platform' => 'facebook', 'username' => $setting->facebook ?? '']
                        ]) }},
                        addSocial() {
                            this.socials.push({ platform: 'instagram', username: '' });
                        },
                        removeSocial(index) {
                            this.socials.splice(index, 1);
                        }
                    }">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-3">
                                <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                                <h3 class="text-xs font-black uppercase tracking-widest text-gray-900 dark:text-white">Media Sosial</h3>
                            </div>
                            <button type="button" @click="addSocial()" class="flex items-center gap-1.5 text-[10px] font-black uppercase tracking-widest text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 px-4 py-2 rounded-xl transition-all">
                                <i class="ti ti-plus text-xs"></i>
                                Tambah Sosmed
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
        @endif

        @if($section === 'branding')
            <!-- Branding Assets Card -->
            <div class="bg-white dark:bg-gray-900 rounded-[2rem] border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="p-8 border-b border-gray-100 dark:border-gray-800 flex items-center gap-3">
                    <div class="w-1.5 h-6 bg-amber-500 rounded-full"></div>
                    <h3 class="text-xs font-black uppercase tracking-widest text-gray-900 dark:text-white">Aset Visual & Branding</h3>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <!-- Logo Upload -->
                        <div x-data="{ photoName: null, photoPreview: null }">
                            <label class="block text-[10px] font-black text-gray-400 mb-4 uppercase tracking-widest">Logo Utama (Light/Dark)</label>
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
                </div>
            </div>
        @endif

        @if($section === 'marketplace')
            <!-- Marketplaces Card -->
            <div class="bg-white dark:bg-gray-900 rounded-[2rem] border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="p-8 border-b border-gray-100 dark:border-gray-800 flex items-center gap-3">
                    <div class="w-1.5 h-6 bg-emerald-500 rounded-full"></div>
                    <h3 class="text-xs font-black uppercase tracking-widest text-gray-900 dark:text-white">Ekosistem Belanja</h3>
                </div>
                <div class="p-8 space-y-6">
                    <div class="space-y-1.5">
                        <label class="flex items-center gap-2 text-[10px] font-black text-[#EE4D2D] uppercase tracking-widest">
                            <i class="ti ti-brand-shopee text-base"></i>
                            Shopee Marketplace
                        </label>
                        <input type="url" name="marketplaces[shopee]" value="{{ old('marketplaces.shopee', $setting->marketplaces['shopee'] ?? '') }}" placeholder="https://shopee.co.id/toko-kamu"
                            class="w-full bg-[#FEF6F4] dark:bg-[#EE4D2D]/5 border border-[#FADCD5] dark:border-[#EE4D2D]/10 focus:border-[#EE4D2D] focus:ring-4 focus:ring-[#EE4D2D]/10 rounded-2xl outline-none transition-all duration-300 text-[10px] font-bold p-4 text-[#EE4D2D]">
                    </div>

                    <div class="space-y-1.5">
                        <label class="flex items-center gap-2 text-[10px] font-black text-[#03AC0E] uppercase tracking-widest">
                            <i class="ti ti-brand-tokopedia text-base"></i>
                            Tokopedia Store
                        </label>
                        <input type="url" name="marketplaces[tokopedia]" value="{{ old('marketplaces.tokopedia', $setting->marketplaces['tokopedia'] ?? '') }}" placeholder="https://tokopedia.com/toko-kamu"
                            class="w-full bg-[#F1FCF2] dark:bg-[#03AC0E]/5 border border-[#DFF6E2] dark:border-[#03AC0E]/10 focus:border-[#03AC0E] focus:ring-4 focus:ring-[#03AC0E]/10 rounded-2xl outline-none transition-all duration-300 text-[10px] font-bold p-4 text-[#03AC0E]">
                    </div>

                    <div class="space-y-1.5">
                        <label class="flex items-center gap-2 text-[10px] font-black text-gray-900 dark:text-white uppercase tracking-widest">
                            <i class="ti ti-brand-tiktok text-base"></i>
                            TikTok Shop
                        </label>
                        <input type="url" name="marketplaces[tiktok]" value="{{ old('marketplaces.tiktok', $setting->marketplaces['tiktok'] ?? '') }}" placeholder="https://tiktok.com/@kamu/shop"
                            class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 focus:border-gray-900 dark:focus:border-white focus:ring-4 focus:ring-gray-900/10 rounded-2xl outline-none transition-all duration-300 text-[10px] font-bold p-4">
                    </div>
                </div>
            </div>
        @endif

        @if($section === 'seo')
            <!-- SEO Integration Card -->
            <div class="bg-white dark:bg-gray-900 rounded-[2rem] border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="p-8 border-b border-gray-100 dark:border-gray-800 flex items-center gap-3">
                    <div class="w-1.5 h-6 bg-indigo-600 rounded-full"></div>
                    <h3 class="text-xs font-black uppercase tracking-widest text-gray-900 dark:text-white">SEO & Metadata</h3>
                </div>
                <div class="p-8 space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 mb-2 uppercase tracking-widest">Global Meta Description</label>
                        <textarea name="shop_description" rows="4" placeholder="Masukan deskripsi singkat untuk Google..."
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 rounded-2xl outline-none transition-all duration-300 text-[11px] font-medium p-4 leading-relaxed">{{ old('shop_description', $setting->shop_description) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 mb-2 uppercase tracking-widest">Footer Copy (Copyright)</label>
                        <input type="text" name="footer_text" value="{{ old('footer_text', $setting->footer_text) }}" placeholder="© 2024 Kataloque."
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-600/10 rounded-2xl outline-none transition-all duration-300 text-[11px] font-medium p-4">
                    </div>
                </div>
            </div>
        @endif

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
@endsection
