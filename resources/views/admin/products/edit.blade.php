@extends('admin.layouts.app')

@section('title', 'Edit Produk')
@section('header', 'Edit Produk')

@section('content')
<div class="space-y-6 pb-20">
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-gray-400">
        <a href="{{ route('admin.produk.index') }}" class="hover:text-blue-600 transition-colors">Produk</a>
        <i class="ti ti-chevron-right text-[10px]"></i>
        <span class="text-gray-900 dark:text-white">Edit Produk</span>
        <i class="ti ti-chevron-right text-[10px]"></i>
        <span class="text-gray-500 truncate max-w-[200px]">{{ $product->name }}</span>
    </nav>

    <form method="POST" action="{{ route('admin.produk.update', $product) }}" enctype="multipart/form-data" class="space-y-6" x-data="{ isSubmitting: false }" @submit="isSubmitting = true">
        @csrf
        @method('PUT')
        
        <!-- Main Info Card -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-gray-800">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white text-center sm:text-left">Informasi Produk</h3>
                <p class="text-sm text-gray-500 text-center sm:text-left">Perbarui detail utama dan kategori produk.</p>
            </div>
            <div class="p-6 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Nama Produk</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium"
                            :readonly="isSubmitting">
                        @error('name') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Kategori</label>
                        <select name="category_id" required 
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium"
                            :class="isSubmitting ? 'opacity-50 pointer-events-none' : ''">
                            <option value="">Pilih Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Slug (Kosongkan untuk auto)</label>
                        <input type="text" name="slug" value="{{ old('slug', $product->slug) }}" placeholder="headphone-wireless-premium" 
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium"
                            :readonly="isSubmitting">
                        @error('slug') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Deskripsi Produk</label>
                    <textarea name="description" rows="5"
                        class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium"
                        :readonly="isSubmitting">{{ old('description', $product->description) }}</textarea>
                    @error('description') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Pricing & Stats Card -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm p-6 space-y-5" :class="isSubmitting ? 'opacity-50 pointer-events-none' : ''">
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-800 pb-4 mb-2">Harga & Promo</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Harga Jual (Rp)</label>
                        <input type="number" name="price" value="{{ old('price', (float)$product->price) }}" step="0.01" min="0" required
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium"
                            :readonly="isSubmitting">
                        @error('price') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Harga Coret (Rp)</label>
                        <input type="number" name="original_price" value="{{ old('original_price', $product->original_price ? (float)$product->original_price : '') }}" step="0.01" min="0"
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium"
                            :readonly="isSubmitting">
                        @error('original_price') <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex flex-col gap-3 pt-2">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <div class="relative">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured)) class="sr-only peer">
                            <div class="w-10 h-6 bg-gray-200 dark:bg-gray-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </div>
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-blue-600 transition-colors">Produk Unggulan (Featured)</span>
                    </label>

                    <label class="flex items-center gap-3 cursor-pointer group">
                        <div class="relative">
                            <input type="hidden" name="show_in_promo" value="0">
                            <input type="checkbox" name="show_in_promo" value="1" @checked(old('show_in_promo', $product->show_in_promo)) class="sr-only peer">
                            <div class="w-10 h-6 bg-gray-200 dark:bg-gray-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </div>
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-blue-600 transition-colors">Tampilkan di Produk Promo</span>
                    </label>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm p-6 space-y-5 relative overflow-hidden"
                x-data="{ 
                    loading: false,
                    lastSync: '{{ $product->last_sync_at ? $product->last_sync_at->format('d/m/Y H:i') : '-' }}',
                    totalReviews: {{ $product->reviews()->count() }},
                    syncMarketplace() {
                        this.loading = true;
                        fetch('{{ route('admin.produk.sync-market', $product) }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.rating_avg !== undefined) {
                                document.getElementsByName('rating_avg')[0].value = data.rating_avg;
                                document.getElementsByName('rating_count')[0].value = data.rating_count;
                                document.getElementsByName('sold_count')[0].value = data.sold_count;
                                this.lastSync = data.last_sync_at;
                                this.totalReviews = data.total_reviews;
                                
                                window.dispatchEvent(new CustomEvent('notify', {
                                    detail: { message: data.message || 'Berhasil sinkronisasi!', type: 'success' }
                                }));
                            } else {
                                window.dispatchEvent(new CustomEvent('notify', {
                                    detail: { message: data.message || 'Gagal sinkronisasi data.', type: 'error' }
                                }));
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            window.dispatchEvent(new CustomEvent('notify', {
                                detail: { message: 'Terjadi kesalahan saat menyambung ke server.', type: 'error' }
                            }));
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                    }
                }" :class="isSubmitting ? 'opacity-50 pointer-events-none' : ''">

                <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-4 mb-2">
                    <div class="flex flex-col">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-gray-900 dark:text-white">Statistik & Status</h3>
                        <span class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mt-1">SINKRON: <span x-text="lastSync"></span></span>
                    </div>
                    <button type="button" @click="syncMarketplace()" :disabled="loading"
                        class="flex items-center gap-2 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 rounded-lg text-[10px] font-black hover:bg-emerald-100 dark:hover:bg-emerald-900/30 transition-all disabled:opacity-50">
                        <i class="fas fa-sync" :class="loading ? 'fa-spin' : ''"></i>
                        <span x-text="loading ? 'MENYINKRONKAN...' : 'AUTO SYNC DATA'"></span>
                    </button>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Terjual</label>
                        <input type="number" name="sold_count" value="{{ old('sold_count', $product->sold_count) }}" min="0" required
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium"
                            :readonly="isSubmitting">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Likes</label>
                        <input type="number" name="likes_count" value="{{ old('likes_count', $product->likes_count) }}" min="0" required
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium"
                            :readonly="isSubmitting">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Rating (0-5)</label>
                        <input type="number" name="rating_avg" value="{{ old('rating_avg', (float)$product->rating_avg) }}" step="0.1" min="0" max="5" required
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium"
                            :readonly="isSubmitting">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Jumlah Rating</label>
                        <input type="number" name="rating_count" value="{{ old('rating_count', $product->rating_count) }}" min="0" required
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium"
                            :readonly="isSubmitting">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Total Ulasan di Database</label>
                        <div class="relative">
                            <input type="number" readonly :value="totalReviews"
                                class="w-full bg-gray-100 dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl text-sm font-bold text-blue-600 dark:text-blue-400 cursor-not-allowed">
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 text-[9px] font-black text-gray-400">JUMLAH BARIS</div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest text-blue-600">Limit Sync Ulasan</label>
                        <input type="number" name="review_sync_limit" value="{{ old('review_sync_limit', $product->review_sync_limit) }}" min="1" max="50" required
                            class="w-full bg-blue-50/50 dark:bg-blue-900/10 border border-blue-200 dark:border-blue-900 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-bold text-gray-900 dark:text-white"
                            :readonly="isSubmitting">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Status Publikasi</label>
                    <select name="status" required 
                        class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium"
                        :class="isSubmitting ? 'opacity-50 pointer-events-none' : ''">
                        <option value="1" @selected(old('status', $product->status) == '1')>Aktif</option>
                        <option value="0" @selected(old('status', $product->status) == '0')>Nonaktif</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Gallery & Marketplace Card Group -->
        <div class="space-y-6">
            <!-- Product Gallery Card -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden"
                x-data="{ 
                    previews: [],
                    handleFileChange(event) {
                        const files = Array.from(event.target.files);
                        this.previews = [];
                        files.forEach(file => {
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                this.previews.push(e.target.result);
                            };
                            reader.readAsDataURL(file);
                        });
                    }
                }" :class="isSubmitting ? 'opacity-50 pointer-events-none' : ''">
                <div class="p-6 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-900 dark:text-white text-center sm:text-left">Galeri Produk</h3>
                    <p class="text-xs text-gray-500 mt-1 text-center sm:text-left">Kelola galeri foto produk (Maks 2MB/file).</p>
                </div>
                <div class="p-6">
                    <livewire:admin.product-gallery :product="$product" />
                </div>
            </div>

            <!-- Marketplace Links Card -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden" :class="isSubmitting ? 'opacity-50 pointer-events-none' : ''">
                <div class="p-6 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-900 dark:text-white text-center sm:text-left">Tautan Marketplace</h3>
                    <p class="text-xs text-gray-500 mt-1 text-center sm:text-left">Masukkan URL produk di setiap marketplace.</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @foreach ($marketplaceOptions as $index => $marketplace)
                            @php
                                // Menggunakan format array ['id'] atau ['name'] karena Supabase/PostgreSQL
                                $link = $product->marketplaceLinks->firstWhere('marketplace_id', $marketplace['id'] ?? null);
                            @endphp
                            <div>
                                {{-- 1. Perbaikan Label: Tambahkan ['name'] --}}
                                <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">
                                    {{ $marketplace['name'] ?? 'Marketplace' }}
                                </label>

                                {{-- 2. Perbaikan Hidden Input: Gunakan ['id'] --}}
                                <input type="hidden" name="marketplace_links[{{ $index }}][marketplace_id]" value="{{ $marketplace['id'] ?? '' }}">
                                
                                {{-- 3. Perbaikan Input URL --}}
                                <input type="url" 
                                    name="marketplace_links[{{ $index }}][url]" 
                                    value="{{ old('marketplace_links.'.$index.'.url', $link?->url) }}" 
                                    placeholder="https://..."
                                    class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium"
                                    :readonly="isSubmitting">
                            </div>
                        @endforeach

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
            <div class="flex flex-row items-center justify-end gap-2 sm:gap-3 px-3 sm:px-6">
                <a href="{{ route('admin.produk.index') }}" class="flex-1 sm:flex-none px-4 sm:px-8 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 text-[10px] sm:text-sm font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition text-center whitespace-nowrap"
                    x-show="!isSubmitting">
                    Batal
                </a>
                <button type="submit" class="flex-1 sm:flex-none px-8 sm:px-14 py-2.5 rounded-xl bg-blue-600 text-white text-[10px] sm:text-sm font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200 dark:shadow-none text-center whitespace-nowrap disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="isSubmitting">
                    <span x-show="!isSubmitting">Simpan Perubahan</span>
                    <span x-show="isSubmitting" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Menyimpan...
                    </span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
