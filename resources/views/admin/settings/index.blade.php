@extends('admin.layouts.app')

@section('title', 'Setting')
@section('header', 'Setting')

@section('content')
    <div class="max-w-5xl space-y-4">
        @if (session('status'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-700 px-4 py-3 text-sm font-semibold">
                {{ session('status') }}
            </div>
        @endif

        <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-6">
            <form method="POST" action="{{ $setting->exists ? route('admin.setting.update', $setting) : route('admin.setting.store') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @if ($setting->exists)
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Nama Toko</label>
                        <input type="text" name="shop_name" required value="{{ old('shop_name', $setting->shop_name) }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                        @error('shop_name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $setting->email) }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                        @error('email') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Telepon</label>
                        <input type="text" name="phone" value="{{ old('phone', $setting->phone) }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">WhatsApp</label>
                        <input type="text" name="whatsapp" value="{{ old('whatsapp', $setting->whatsapp) }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Website</label>
                        <input type="url" name="website" value="{{ old('website', $setting->website) }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                    </div>
                    <div x-data="{ photoName: null, photoPreview: null }">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Logo URL / Upload Logo</label>
                        <input type="file" name="shop_logo" class="hidden" x-ref="photo" x-on:change="
                                photoName = $refs.photo.files[0].name;
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    photoPreview = e.target.result;
                                };
                                reader.readAsDataURL($refs.photo.files[0]);
                        " accept="image/*">

                        <div class="mt-2 flex items-center gap-4">
                            <div class="shrink-0">
                                <span class="block w-16 h-16 rounded-lg bg-cover bg-center bg-no-repeat border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900" 
                                    x-bind:style="'background-image: url(\'' + (photoPreview ?? '{{ $setting->shop_logo ?: 'https://ui-avatars.com/api/?name=Logo&color=7F9CF5&background=EBF4FF' }}') + '\');'"
                                ></span>
                            </div>
                            <button type="button" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg text-xs font-semibold" x-on:click.prevent="$refs.photo.click()">
                                Upload Logo Baru
                            </button>
                        </div>
                        @error('shop_logo') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Kota</label>
                        <input type="text" name="city" value="{{ old('city', $setting->city) }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Provinsi</label>
                        <input type="text" name="province" value="{{ old('province', $setting->province) }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Alamat</label>
                    <textarea name="shop_address" rows="2" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">{{ old('shop_address', $setting->shop_address) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Deskripsi Toko</label>
                    <textarea name="shop_description" rows="3" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">{{ old('shop_description', $setting->shop_description) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Facebook</label>
                        <input type="url" name="facebook" value="{{ old('facebook', $setting->facebook) }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Instagram</label>
                        <input type="url" name="instagram" value="{{ old('instagram', $setting->instagram) }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Footer Text</label>
                        <input type="text" name="footer_text" value="{{ old('footer_text', $setting->footer_text) }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                    </div>
                    <div x-data="{ favName: null, favPreview: null }">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Favicon URL / Upload Favicon</label>
                        <input type="file" name="favicon" class="hidden" x-ref="favicon_photo" x-on:change="
                                favName = $refs.favicon_photo.files[0].name;
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    favPreview = e.target.result;
                                };
                                reader.readAsDataURL($refs.favicon_photo.files[0]);
                        " accept="image/*">

                        <div class="mt-2 flex items-center gap-4">
                            <div class="shrink-0">
                                <span class="block w-16 h-16 rounded-lg bg-cover bg-center bg-no-repeat border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900" 
                                    x-bind:style="'background-image: url(\'' + (favPreview ?? '{{ $setting->favicon ?: 'https://ui-avatars.com/api/?name=Fv&color=34D399&background=D1FAE5' }}') + '\');'"
                                ></span>
                            </div>
                            <button type="button" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg text-xs font-semibold" x-on:click.prevent="$refs.favicon_photo.click()">
                                Upload Favicon Baru
                            </button>
                        </div>
                        @error('favicon') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Pilihan Marketplace</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach(['Shopee', 'Tokopedia', 'Lazada', 'Blibli', 'Tiktok Shop'] as $marketplace)
                            <label class="inline-flex items-center gap-2 p-3 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 hover:bg-white dark:hover:bg-gray-900 transition cursor-pointer group">
                                <input 
                                    type="checkbox" 
                                    name="marketplaces[]" 
                                    value="{{ $marketplace }}" 
                                    @checked(in_array($marketplace, old('marketplaces', $setting->marketplaces ?? []))) 
                                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                >
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 group-hover:text-blue-600 transition">{{ $marketplace }}</span>
                            </label>
                        @endforeach
                    </div>
                    <p class="text-[11px] text-gray-500 italic">* Pilih marketplace yang aktif digunakan untuk semua produk.</p>
                </div>

                <div class="pt-2">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 transition">
                        <i class="ti ti-device-floppy text-base"></i>
                        Simpan Setting
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
