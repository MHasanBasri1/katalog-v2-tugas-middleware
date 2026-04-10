@extends('admin.layouts.app')

@section('title', 'Pengaturan Toko')
@section('header', 'Pengaturan Toko')

@section('content')
<div class="space-y-6 pb-20">
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-gray-400">
        <i class="ti ti-settings text-[10px]"></i>
        <span class="text-gray-900 dark:text-white">Pengaturan Umum</span>
    </nav>

    @if (session('status'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-700 px-4 py-3 text-sm font-semibold flex items-center gap-3 animate-in fade-in slide-in-from-top-4 duration-300">
            <i class="ti ti-check bg-emerald-600 text-white rounded-full p-0.5 text-[10px]"></i>
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ $setting->exists ? route('admin.setting.update', $setting) : route('admin.setting.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if ($setting->exists)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Side: Identity & Contact -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Shop Identity Card -->
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-800">
                        <h3 class="text-[10px] font-bold uppercase tracking-widest text-blue-600">Identitas Toko</h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Nama Toko</label>
                                <input type="text" name="shop_name" required value="{{ old('shop_name', $setting->shop_name) }}"
                                    class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                                @error('shop_name') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Email Kontak</label>
                                <input type="email" name="email" value="{{ old('email', $setting->email) }}"
                                    class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Telepon / Kantor</label>
                                <input type="text" name="phone" value="{{ old('phone', $setting->phone) }}"
                                    class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest text-emerald-600">WhatsApp Bisnis</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-emerald-500">
                                        <i class="ti ti-brand-whatsapp text-lg"></i>
                                    </div>
                                    <input type="text" name="whatsapp" value="{{ old('whatsapp', $setting->whatsapp) }}" placeholder="+62..."
                                        class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium pl-10">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Alamat Lengkap (Footer & Contact Page)</label>
                            <textarea name="shop_address" rows="3"
                                class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">{{ old('shop_address', $setting->shop_address) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Branding Assets Card -->
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-800">
                        <h3 class="text-[10px] font-bold uppercase tracking-widest text-blue-600">Aset Visual & Branding</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Logo Upload -->
                            <div x-data="{ photoName: null, photoPreview: null }">
                                <label class="block text-[10px] font-bold text-gray-400 mb-3 uppercase tracking-widest">Logo Toko</label>
                                <input type="file" name="shop_logo" class="hidden" x-ref="photo" x-on:change="
                                        photoName = $refs.photo.files[0].name;
                                        const reader = new FileReader();
                                        reader.onload = (e) => {
                                            photoPreview = e.target.result;
                                        };
                                        reader.readAsDataURL($refs.photo.files[0]);
                                ">
                                <div class="relative group w-full aspect-video rounded-2xl bg-gray-50 dark:bg-gray-800/50 border-2 border-dashed border-gray-200 dark:border-gray-700 flex flex-col items-center justify-center overflow-hidden transition-colors hover:border-blue-600"
                                     x-on:click.prevent="$refs.photo.click()" style="cursor: pointer;">
                                    
                                    <div class="absolute inset-0" x-show="photoPreview || '{{ $setting->shop_logo }}'">
                                        <img :src="photoPreview ?? '{{ $setting->shop_logo }}'" class="w-full h-full object-contain p-4" alt="Logo Preview">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                                            <span class="text-white text-[10px] font-bold uppercase tracking-widest bg-black/60 px-3 py-1.5 rounded-full">Ganti Logo</span>
                                        </div>
                                    </div>

                                    <div x-show="!photoPreview && !'{{ $setting->shop_logo }}'" class="text-center">
                                        <i class="ti ti-photo-plus text-2xl text-gray-400 group-hover:text-blue-600 transition-colors"></i>
                                        <p class="mt-1 text-[10px] text-gray-500 font-bold uppercase tracking-widest">Klik untuk Upload</p>
                                    </div>
                                </div>
                                @error('shop_logo') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                            </div>

                            <!-- Favicon Upload -->
                            <div x-data="{ favName: null, favPreview: null }">
                                <label class="block text-[10px] font-bold text-gray-400 mb-3 uppercase tracking-widest">Favicon (Browser Icon)</label>
                                <input type="file" name="favicon" class="hidden" x-ref="favicon_photo" x-on:change="
                                        favName = $refs.favicon_photo.files[0].name;
                                        const reader = new FileReader();
                                        reader.onload = (e) => {
                                            favPreview = e.target.result;
                                        };
                                        reader.readAsDataURL($refs.favicon_photo.files[0]);
                                ">
                                <div class="relative group w-24 h-24 rounded-2xl bg-gray-50 dark:bg-gray-800/50 border-2 border-dashed border-gray-200 dark:border-gray-700 flex flex-col items-center justify-center overflow-hidden transition-colors hover:border-blue-600 mx-auto md:mx-0"
                                     x-on:click.prevent="$refs.favicon_photo.click()" style="cursor: pointer;">
                                    
                                    <div class="absolute inset-0" x-show="favPreview || '{{ $setting->favicon }}'">
                                        <img :src="favPreview ?? '{{ $setting->favicon }}'" class="w-full h-full object-contain p-4" alt="Favicon Preview">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                                            <i class="ti ti-pencil text-white text-base"></i>
                                        </div>
                                    </div>

                                    <div x-show="!favPreview && !'{{ $setting->favicon }}'" class="text-center">
                                        <i class="ti ti-photo-plus text-xl text-gray-400"></i>
                                    </div>
                                </div>
                                @error('favicon') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                                <p class="mt-3 text-[10px] text-gray-400 italic text-center md:text-left leading-relaxed">Format: .ico, .png, .svg. Ukuran ideal: 32x32px atau 64x64px.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer & SEO Card -->
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-800">
                        <h3 class="text-[10px] font-bold uppercase tracking-widest text-blue-600">Konten Footer & SEO</h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Deskripsi Toko (Metadata SEO)</label>
                            <textarea name="shop_description" rows="3" placeholder="Deskripsi ini akan muncul di Google Search..."
                                class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium leading-relaxed">{{ old('shop_description', $setting->shop_description) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Copyright Text</label>
                            <input type="text" name="footer_text" value="{{ old('footer_text', $setting->footer_text) }}" placeholder="Contoh: © 2024 Kataloque. All Rights Reserved."
                                class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Marketplaces -->
            <div class="space-y-6">
                <!-- Marketplaces Card -->
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-800 border-l-4 border-l-blue-600">
                        <h3 class="text-[10px] font-bold uppercase tracking-widest text-gray-900 dark:text-white">External Marketplace</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="space-y-4">
                            <div>
                                <label class="flex items-center gap-2 text-[10px] font-bold text-orange-600 mb-2 uppercase tracking-widest">
                                    <i class="ti ti-brand-shopee text-base"></i>
                                    Shopee Store
                                </label>
                                <input type="url" name="marketplaces[shopee]" value="{{ old('marketplaces.shopee', $setting->marketplaces['shopee'] ?? '') }}" placeholder="URL Shopee..."
                                    class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-orange-600 focus:ring-4 focus:ring-orange-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-[10px] font-mono">
                            </div>

                            <div>
                                <label class="flex items-center gap-2 text-[10px] font-bold text-emerald-600 mb-2 uppercase tracking-widest">
                                    <i class="ti ti-brand-tokopedia text-base"></i>
                                    Tokopedia Store
                                </label>
                                <input type="url" name="marketplaces[tokopedia]" value="{{ old('marketplaces.tokopedia', $setting->marketplaces['tokopedia'] ?? '') }}" placeholder="URL Tokopedia..."
                                    class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-[10px] font-mono">
                            </div>

                            <div>
                                <label class="flex items-center gap-2 text-[10px] font-bold text-gray-900 dark:text-white mb-2 uppercase tracking-widest">
                                    <i class="ti ti-brand-tiktok text-base"></i>
                                    TikTok Shop
                                </label>
                                <input type="url" name="marketplaces[tiktok]" value="{{ old('marketplaces.tiktok', $setting->marketplaces['tiktok'] ?? '') }}" placeholder="URL TikTok..."
                                    class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-gray-900 dark:focus:border-white focus:ring-4 focus:ring-gray-900/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-[10px] font-mono">
                            </div>

                            <div>
                                <label class="flex items-center gap-2 text-[10px] font-bold text-pink-600 mb-2 uppercase tracking-widest">
                                    <i class="ti ti-brand-instagram text-base"></i>
                                    Instagram Profile
                                </label>
                                <input type="url" name="instagram" value="{{ old('instagram', $setting->instagram) }}" placeholder="URL Instagram..."
                                    class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-pink-600 focus:ring-4 focus:ring-pink-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-[10px] font-mono">
                            </div>

                            <div>
                                <label class="flex items-center gap-2 text-[10px] font-bold text-blue-800 mb-2 uppercase tracking-widest">
                                    <i class="ti ti-brand-facebook text-base"></i>
                                    Facebook Page
                                </label>
                                <input type="url" name="facebook" value="{{ old('facebook', $setting->facebook) }}" placeholder="URL Facebook..."
                                    class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-800 focus:ring-4 focus:ring-blue-800/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-[10px] font-mono">
                            </div>
                        </div>

                        <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-100 dark:border-gray-800">
                            <p class="text-[10px] text-gray-500 leading-relaxed italic text-center">
                                Tautkan toko marketplace Kamu untuk memudahkan pengunjung bertransaksi di platform favorit mereka.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sticky Bottom Actions -->
        <div class="fixed bottom-0 right-0 z-[100] transition-all duration-300 bg-white/80 dark:bg-gray-900/80 backdrop-blur-lg border-t border-gray-200 dark:border-gray-800 p-4"
            :class="{
                'xl:left-72': $store.sidebar.isExpanded,
                'xl:left-20': !$store.sidebar.isExpanded,
                'left-0': true
            }">
            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 px-4">
                <button type="submit" class="w-full sm:w-auto px-12 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200 dark:shadow-none text-center">
                    Simpan Seluruh Pengaturan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
