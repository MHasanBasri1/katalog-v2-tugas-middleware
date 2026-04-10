@extends('admin.layouts.app')

@section('title', 'Tambah Produk')
@section('header', 'Tambah Produk')

@section('content')
<div class="space-y-6 pb-20">
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-gray-400">
        <a href="{{ route('admin.produk.index') }}" class="hover:text-blue-600 transition-colors">Produk</a>
        <i class="ti ti-chevron-right text-[10px]"></i>
        <span class="text-gray-900 dark:text-white">Tambah Produk Baru</span>
    </nav>

    <form method="POST" action="{{ route('admin.produk.store') }}" class="space-y-6">
        @csrf
        
        <!-- Main Info Card -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-800">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white text-center sm:text-left">Informasi Produk</h3>
                <p class="text-sm text-gray-500 text-center sm:text-left">Detail utama dan kategori produk.</p>
            </div>
            <div class="p-6 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Nama Produk</label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Headphone Wireless Premium"
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                        @error('name') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Kategori</label>
                        <select name="category_id" required 
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                            <option value="">Pilih Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Slug (Opsional)</label>
                        <input type="text" name="slug" value="{{ old('slug') }}" placeholder="headphone-wireless-premium"
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                        @error('slug') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Deskripsi Produk</label>
                    <textarea name="description" rows="4" placeholder="Jelaskan detail produk Anda..."
                        class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">{{ old('description') }}</textarea>
                    @error('description') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Pricing & Stats Card -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm p-6 space-y-5">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-4 mb-2">Harga & Promo</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Harga Jual (Rp)</label>
                        <input type="number" name="price" value="{{ old('price', 0) }}" step="0.01" min="0" required
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                        @error('price') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Harga Coret (Rp)</label>
                        <input type="number" name="original_price" value="{{ old('original_price') }}" step="0.01" min="0"
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                        @error('original_price') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex flex-col gap-3 pt-2">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <div class="relative">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured')) class="sr-only peer">
                            <div class="w-10 h-6 bg-gray-200 dark:bg-gray-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </div>
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-blue-600 transition-colors">Produk Unggulan (Featured)</span>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer group">
                        <div class="relative">
                            <input type="hidden" name="show_in_promo" value="0">
                            <input type="checkbox" name="show_in_promo" value="1" @checked(old('show_in_promo')) class="sr-only peer">
                            <div class="w-10 h-6 bg-gray-200 dark:bg-gray-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </div>
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-blue-600 transition-colors">Tampilkan di Produk Promo</span>
                    </label>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm p-6 space-y-5">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-4 mb-2">Statistik & Status</h3>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Terjual</label>
                        <input type="number" name="sold_count" value="{{ old('sold_count', 0) }}" min="0" required
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Likes</label>
                        <input type="number" name="likes_count" value="{{ old('likes_count', 0) }}" min="0" required
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Rating (0-5)</label>
                        <input type="number" name="rating_avg" value="{{ old('rating_avg', 0) }}" step="0.1" min="0" max="5" required
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Jumlah Rating</label>
                        <input type="number" name="rating_count" value="{{ old('rating_count', 0) }}" min="0" required
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Status Publikasi</label>
                    <select name="status" required 
                        class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                        <option value="1" @selected(old('status', '1') == '1')>Aktif</option>
                        <option value="0" @selected(old('status') == '0')>Nonaktif</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Marketplace Links Card -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm">
            <div class="p-6 border-b border-gray-100 dark:border-gray-800">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-900 dark:text-white">Tautan Marketplace</h3>
                <p class="text-xs text-gray-500 mt-1">Masukkan URL toko atau produk di setiap platform.</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    @foreach ($marketplaceOptions as $index => $marketplace)
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">{{ $marketplace }}</label>
                            <input type="hidden" name="marketplace_links[{{ $index }}][marketplace]" value="{{ $marketplace }}">
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <i class="ti ti-link text-gray-400 group-focus-within:text-blue-600 transition-colors"></i>
                                </div>
                                <input type="url" name="marketplace_links[{{ $index }}][url]" value="{{ old('marketplace_links.'.$index.'.url') }}" placeholder="https://..."
                                    class="w-full pl-10 bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium">
                            </div>
                        </div>
                    @endforeach
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
                <a href="{{ route('admin.produk.index') }}" class="w-full sm:w-auto px-6 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition text-center">
                    Batal
                </a>
                <button type="submit" name="action" value="save_and_another" class="w-full sm:w-auto px-6 py-2.5 rounded-xl border border-blue-600 text-blue-600 text-sm font-bold hover:bg-blue-50 transition text-center">
                    Simpan & Buat Lagi
                </button>
                <button type="submit" class="w-full sm:w-auto px-10 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200 dark:shadow-none text-center">
                    Simpan Produk
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
