@extends('admin.layouts.app')

@section('title', 'Produk')
@section('header', 'Produk')

@section('content')
    <div
        class="space-y-4"
        x-data="{
            init() {
                this.toggleBodyLock(this.drawerOpen);
                this.$watch('drawerOpen', (value) => this.toggleBodyLock(value));
            },
            drawerOpen: {{ ($editProduct || $errors->any()) ? 'true' : 'false' }},
            isEdit: {{ $editProduct ? 'true' : 'false' }},
            editId: @js(old('edit_id', $editProduct->id ?? null)),
            productsMap: @js(
                $products->getCollection()->mapWithKeys(fn($item) => [
                    $item->id => [
                        'id' => $item->id,
                        'category_id' => $item->category_id,
                        'name' => $item->name,
                        'slug' => $item->slug,
                        'description' => $item->description,
                        'price' => (float) $item->price,
                        'original_price' => $item->original_price !== null ? (float) $item->original_price : null,
                        'status' => (int) $item->status,
                        'sold_count' => (int) $item->sold_count,
                        'likes_count' => (int) $item->likes_count,
                        'rating_avg' => (float) $item->rating_avg,
                        'rating_count' => (int) $item->rating_count,
                        'is_featured' => (int) $item->is_featured,
                        'show_in_promo' => (int) $item->show_in_promo,
                        'marketplace_links' => $item->marketplaceLinks->map(fn($m) => ['marketplace' => $m->marketplace, 'url' => $m->url])->values(),
                    ]
                ])
            ),
            form: {
                category_id: @js(old('category_id', $editProduct->category_id ?? '')),
                name: @js(old('name', $editProduct->name ?? '')),
                slug: @js(old('slug', $editProduct->slug ?? '')),
                description: @js(old('description', $editProduct->description ?? '')),
                price: @js(old('price', isset($editProduct) ? (float) $editProduct->price : 0)),
                original_price: @js(old('original_price', isset($editProduct) && $editProduct->original_price !== null ? (float) $editProduct->original_price : '')),
                status: @js((string) old('status', isset($editProduct) ? (int) $editProduct->status : 1)),
                sold_count: @js(old('sold_count', $editProduct->sold_count ?? 0)),
                likes_count: @js(old('likes_count', $editProduct->likes_count ?? 0)),
                rating_avg: @js(old('rating_avg', isset($editProduct) ? (float) $editProduct->rating_avg : 0)),
                rating_count: @js(old('rating_count', $editProduct->rating_count ?? 0)),
                is_featured: @js((string) old('is_featured', isset($editProduct) ? (int) $editProduct->is_featured : 0)) === '1',
                show_in_promo: @js((string) old('show_in_promo', isset($editProduct) ? (int) $editProduct->show_in_promo : 0)) === '1',
                marketplace_links: @js(old('marketplace_links', collect($marketplaceOptions)->map(function($m) use ($editProduct) {
                    $url = optional(optional($editProduct)->marketplaceLinks?->firstWhere('marketplace', $m))->url;
                    return ['marketplace' => $m, 'url' => $url];
                })->values()->all())),
            },
            currentPageIds: @js($products->getCollection()->pluck('id')->values()),
            selectedIds: [],
            storeUrl: @js(route('admin.produk.store')),
            updateUrlTemplate: @js(route('admin.produk.update', ['produk' => '__ID__'])),
            bulkDeleteUrl: @js(route('admin.produk.bulk-destroy')),
            toggleBodyLock(isOpen) {
                document.documentElement.classList.toggle('overflow-hidden', isOpen);
                document.body.classList.toggle('overflow-hidden', isOpen);
                document.body.classList.toggle('drawer-open', isOpen);
            },
            toggleRowSelection(id) {
                const value = Number(id);
                if (this.selectedIds.includes(value)) {
                    this.selectedIds = this.selectedIds.filter((item) => item !== value);
                    return;
                }
                this.selectedIds.push(value);
            },
            toggleSelectAllOnPage() {
                const allSelected = this.currentPageIds.length > 0 && this.currentPageIds.every((id) => this.selectedIds.includes(id));
                if (allSelected) {
                    this.selectedIds = this.selectedIds.filter((id) => !this.currentPageIds.includes(id));
                    return;
                }
                this.selectedIds = Array.from(new Set([...this.selectedIds, ...this.currentPageIds]));
            },
            submitBulkDelete() {
                if (this.selectedIds.length === 0) return;
                if (!confirm(`Hapus ${this.selectedIds.length} produk terpilih?`)) return;

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = this.bulkDeleteUrl;

                const csrf = document.querySelector('meta[name=csrf-token]')?.getAttribute('content') ?? '';
                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = csrf;
                form.appendChild(tokenInput);

                this.selectedIds.forEach((id) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'selected_ids[]';
                    input.value = String(id);
                    form.appendChild(input);
                });

                document.body.appendChild(form);
                form.submit();
            },
            triggerCsvPicker() {
                this.$refs.csvFileInput?.click();
            },
            submitCsvImport(event) {
                const input = event.target;
                if (!input.files || input.files.length === 0) return;
                input.form?.requestSubmit();
            },
            openCreate() {
                this.drawerOpen = true;
                this.isEdit = false;
                this.editId = null;
                this.form = {
                    category_id: '',
                    name: '',
                    slug: '',
                    description: '',
                    price: 0,
                    original_price: '',
                    status: '1',
                    sold_count: 0,
                    likes_count: 0,
                    rating_avg: 0,
                    rating_count: 0,
                    is_featured: false,
                    show_in_promo: false,
                    marketplace_links: @js(collect($marketplaceOptions)->map(fn($m) => ['marketplace' => $m, 'url' => ''])->values()->all()),
                };
            },
            openEdit(id) {
                const item = this.productsMap[id];
                if (!item) return;
                this.drawerOpen = true;
                this.isEdit = true;
                this.editId = id;
                this.form = {
                    category_id: item.category_id ?? '',
                    name: item.name ?? '',
                    slug: item.slug ?? '',
                    description: item.description ?? '',
                    price: item.price ?? 0,
                    original_price: item.original_price ?? '',
                    status: String(item.status ?? 1),
                    sold_count: item.sold_count ?? 0,
                    likes_count: item.likes_count ?? 0,
                    rating_avg: item.rating_avg ?? 0,
                    rating_count: item.rating_count ?? 0,
                    is_featured: Number(item.is_featured ?? 0) === 1,
                    show_in_promo: Number(item.show_in_promo ?? 0) === 1,
                    marketplace_links: @js($marketplaceOptions).map((name) => {
                        const found = (item.marketplace_links || []).find((m) => m.marketplace === name);
                        return { marketplace: name, url: found ? found.url : '' };
                    }),
                };
            },
            closeDrawer() {
                this.drawerOpen = false;
            },
            get actionUrl() {
                return this.isEdit
                    ? this.updateUrlTemplate.replace('__ID__', this.editId)
                    : this.storeUrl;
            },
            get isAllOnPageSelected() {
                return this.currentPageIds.length > 0 && this.currentPageIds.every((id) => this.selectedIds.includes(id));
            }
        }"
        @keydown.escape.window="if (drawerOpen) closeDrawer()"
    >
        @if (session('status'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-700 px-4 py-3 text-sm font-semibold">
                {{ session('status') }}
            </div>
        @endif
        @if (session('error'))
            <div class="rounded-xl border border-rose-200 bg-rose-50 text-rose-700 px-4 py-3 text-sm font-semibold">
                {{ session('error') }}
            </div>
        @endif
        @if (session('import_errors'))
            <div class="rounded-xl border border-amber-200 bg-amber-50 text-amber-800 px-4 py-3 text-sm">
                <p class="font-semibold mb-1">Sebagian baris gagal diimport:</p>
                <ul class="list-disc pl-5 space-y-0.5">
                    @foreach (session('import_errors') as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="space-y-4">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Daftar Produk</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Kelola produk.</p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <form method="GET" action="{{ route('admin.produk.index') }}" class="flex flex-wrap items-center gap-2">
                        <select name="category_id" class="rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100 text-sm px-3 py-2.5 min-w-[170px]">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                            <i class="ti ti-filter text-base"></i> Filter
                        </button>
                        @if (request()->filled('category_id'))
                            <a href="{{ route('admin.produk.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                <i class="ti ti-x text-base"></i> Reset
                            </a>
                        @endif
                    </form>
                    <button
                        type="button"
                        x-show="selectedIds.length > 0"
                        x-cloak
                        @click="submitBulkDelete()"
                        class="inline-flex items-center gap-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold px-4 py-2.5 transition"
                    >
                        <i class="ti ti-trash text-base"></i>
                        <span x-text="`Hapus Terpilih (${selectedIds.length})`"></span>
                    </button>
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ request()->filled('category_id') ? route('admin.produk.export-csv', ['category_id' => request('category_id')]) : route('admin.produk.export-csv') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                            <i class="ti ti-download text-base"></i> Export
                        </a>
                        <form method="POST" action="{{ route('admin.produk.import-csv') }}" enctype="multipart/form-data" class="m-0">
                            @csrf
                            <input
                                x-ref="csvFileInput"
                                type="file"
                                name="csv_file"
                                accept=".csv,text/csv"
                                required
                                class="sr-only"
                                @change="submitCsvImport($event)"
                            >
                            <button type="button" @click="triggerCsvPicker()" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                <i class="ti ti-file-upload text-base"></i> Import CSV
                            </button>
                        </form>
                        <button type="button" @click="openCreate()" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 transition">
                            <i class="ti ti-plus text-base"></i> Tambah
                        </button>
                    </div>
                </div>
            </div>
            <div class="rounded-xl border border-dashed border-gray-300 dark:border-gray-700 bg-white/80 dark:bg-gray-900/80 px-4 py-3 text-[11px] sm:text-xs leading-relaxed text-gray-600 dark:text-gray-300 break-words">
                <p><span class="font-semibold">CSV:</span> <code class="font-mono">name,price</code> + kategori (<code class="font-mono">category_id</code> / <code class="font-mono">category_slug</code> / <code class="font-mono">category_name</code>).</p>
                <p><span class="font-semibold">Opsional:</span> slug, deskripsi, harga lama, status, statistik, promo, dan link marketplace.</p>
            </div>

            <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden">
                <div class="w-full overflow-x-auto">
                    <table class="w-full min-w-[1300px] lg:min-w-0 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-800/60 text-gray-600 dark:text-gray-300">
                            <tr>
                                <th class="px-4 py-3 text-center w-12">
                                    <input type="checkbox" :checked="isAllOnPageSelected" @change="toggleSelectAllOnPage()" class="rounded border-gray-300 text-blue-600">
                                </th>
                                <th class="px-4 py-3 text-left">Produk</th>
                                <th class="px-4 py-3 text-left">Kategori</th>
                                <th class="px-4 py-3 text-right">Harga</th>
                                <th class="px-4 py-3 text-right">Harga Coret</th>
                                <th class="px-4 py-3 text-center">Sold</th>
                                <th class="px-4 py-3 text-center">Rating</th>
                                <th class="px-4 py-3 text-center">Featured</th>
                                <th class="px-4 py-3 text-center">Produk Promo</th>
                                <th class="px-4 py-3 text-center">Marketplace</th>
                                <th class="px-4 py-3 text-center">Status</th>
                                <th class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse ($products as $product)
                                <tr>
                                    <td class="px-4 py-3 text-center">
                                        <input type="checkbox" :checked="selectedIds.includes({{ $product->id }})" @change="toggleRowSelection({{ $product->id }})" class="rounded border-gray-300 text-blue-600">
                                    </td>
                                    <td class="px-4 py-3">
                                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $product->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $product->slug }}</p>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $product->category?->name }}</td>
                                    <td class="px-4 py-3 text-right font-semibold text-gray-900 dark:text-gray-100">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-right text-gray-600 dark:text-gray-300">
                                        {{ $product->original_price ? 'Rp '.number_format((float) $product->original_price, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-center text-gray-700 dark:text-gray-300">{{ number_format($product->sold_count) }}</td>
                                    <td class="px-4 py-3 text-center text-gray-700 dark:text-gray-300">{{ number_format((float) $product->rating_avg, 1) }} ({{ number_format($product->rating_count) }})</td>
                                    <td class="px-4 py-3 text-center">
                                        <span @class([
                                            'inline-flex rounded-full px-2.5 py-1 text-xs font-bold',
                                            'bg-blue-100 text-blue-700' => $product->is_featured,
                                            'bg-gray-200 text-gray-700' => ! $product->is_featured,
                                        ])>
                                            {{ $product->is_featured ? 'Ya' : 'Tidak' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span @class([
                                            'inline-flex rounded-full px-2.5 py-1 text-xs font-bold',
                                            'bg-blue-100 text-blue-700' => $product->show_in_promo,
                                            'bg-gray-200 text-gray-700' => ! $product->show_in_promo,
                                        ])>
                                            {{ $product->show_in_promo ? 'Ya' : 'Tidak' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center text-gray-700 dark:text-gray-300">{{ $product->marketplaceLinks->count() }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span @class([
                                            'inline-flex rounded-full px-2.5 py-1 text-xs font-bold',
                                            'bg-emerald-100 text-emerald-700' => $product->status,
                                            'bg-gray-200 text-gray-700' => ! $product->status,
                                        ])>
                                            {{ $product->status ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-end gap-2">
                                            <button type="button" @click="openEdit({{ $product->id }})" class="inline-flex items-center rounded-lg border border-gray-200 dark:border-gray-700 p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800" title="Edit" aria-label="Edit">
                                                <i class="ti ti-pencil text-base"></i>
                                            </button>
                                            <form method="POST" action="{{ route('admin.produk.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center rounded-lg border border-rose-200 p-2 text-rose-600 hover:bg-rose-50" title="Hapus" aria-label="Hapus">
                                                    <i class="ti ti-trash text-base"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="px-4 py-10 text-center text-gray-500">Belum ada data produk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-gray-100 dark:border-gray-800">
                    {{ $products->links() }}
                </div>
            </div>
        </div>

        <div x-show="drawerOpen" x-cloak x-transition.opacity class="admin-drawer-overlay fixed -inset-2 bg-black/70 z-[12000]" @click="closeDrawer()"></div>
        <aside
            class="admin-drawer-panel fixed inset-y-0 right-0 h-[100dvh] min-h-[100dvh] w-full sm:max-w-xl bg-white dark:bg-gray-900 border-l border-gray-200 dark:border-gray-800 z-[13000] shadow-2xl transform-gpu will-change-transform overflow-y-auto transition-transform duration-300 ease-out"
            :class="drawerOpen ? 'translate-x-0' : 'translate-x-full pointer-events-none'"
            x-cloak
        >
            <div class="p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100" x-text="isEdit ? 'Edit Produk' : 'Tambah Produk'"></h3>
                    <button type="button" class="w-9 h-9 rounded-lg border border-gray-200 dark:border-gray-700 inline-flex items-center justify-center" @click="closeDrawer()">
                        <i class="ti ti-x text-lg"></i>
                    </button>
                </div>
                <form method="POST" :action="actionUrl" class="space-y-4">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>
                    <input type="hidden" name="edit_id" :value="editId ?? ''">

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Kategori</label>
                        <select name="category_id" x-model="form.category_id" required class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                            <option value="">Pilih kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Nama Produk</label>
                        <input type="text" name="name" x-model="form.name" required class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Slug</label>
                        <input type="text" name="slug" x-model="form.slug" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Harga</label>
                            <input type="number" step="0.01" min="0" name="price" x-model="form.price" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Harga Coret</label>
                            <input type="number" step="0.01" min="0" name="original_price" x-model="form.original_price" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100 text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                        <textarea name="description" rows="3" x-model="form.description" placeholder="Deskripsi" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Status</label>
                            <select name="status" x-model="form.status" class="rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100 w-full">
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <label class="inline-flex items-center gap-2 text-xs font-semibold text-gray-700 dark:text-gray-300">
                                <input type="hidden" name="is_featured" value="0">
                                <input type="checkbox" name="is_featured" value="1" x-model="form.is_featured" class="rounded border-gray-300 text-blue-600"> Featured
                            </label>
                        </div>
                        <div class="flex items-end col-span-2">
                            <label class="inline-flex items-center gap-2 text-xs font-semibold text-gray-700 dark:text-gray-300">
                                <input type="hidden" name="show_in_promo" value="0">
                                <input type="checkbox" name="show_in_promo" value="1" x-model="form.show_in_promo" class="rounded border-gray-300 text-blue-600"> Tampilkan di section Produk Promo
                            </label>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Sold Count</label>
                            <input type="number" min="0" name="sold_count" x-model="form.sold_count" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Likes Count</label>
                            <input type="number" min="0" name="likes_count" x-model="form.likes_count" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Rating Avg</label>
                            <input type="number" min="0" max="5" step="0.1" name="rating_avg" x-model="form.rating_avg" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Rating Count</label>
                            <input type="number" min="0" name="rating_count" x-model="form.rating_count" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100 text-sm">
                        </div>
                    </div>

                    <div class="rounded-xl border border-gray-200 dark:border-gray-800 p-3">
                        <h4 class="text-xs font-bold text-gray-700 dark:text-gray-200 mb-2">Marketplace</h4>
                        <div class="space-y-2">
                            @foreach ($marketplaceOptions as $index => $marketplace)
                                <input type="hidden" name="marketplace_links[{{ $index }}][marketplace]" :value="form.marketplace_links[{{ $index }}].marketplace">
                                <input
                                    type="url"
                                    name="marketplace_links[{{ $index }}][url]"
                                    x-model="form.marketplace_links[{{ $index }}].url"
                                    placeholder="{{ $marketplace }} URL"
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100 text-sm"
                                >
                            @endforeach
                        </div>
                    </div>

                    @if ($errors->any())
                        <ul class="text-xs text-rose-600 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif

                    <div class="flex items-center gap-2">
                        <button type="submit" class="inline-flex items-center rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 transition">
                            <span x-text="isEdit ? 'Update' : 'Simpan'"></span>
                        </button>
                        <button type="button" x-show="isEdit" @click="closeDrawer()" class="inline-flex items-center rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-semibold px-4 py-2.5 hover:bg-gray-50 dark:hover:bg-gray-800">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </aside>
    </div>
@endsection









