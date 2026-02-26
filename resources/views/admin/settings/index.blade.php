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
            <form method="POST" action="{{ $setting->exists ? route('admin.setting.update', $setting) : route('admin.setting.store') }}" class="space-y-5">
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
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Logo URL</label>
                        <input type="text" name="shop_logo" value="{{ old('shop_logo', $setting->shop_logo) }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
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
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Favicon URL</label>
                        <input type="text" name="favicon" value="{{ old('favicon', $setting->favicon) }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                    </div>
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
