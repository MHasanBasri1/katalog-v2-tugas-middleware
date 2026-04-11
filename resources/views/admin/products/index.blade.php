@extends('admin.layouts.app')

@section('title', 'Produk')
@section('header', 'Produk')

@section('content')
    <div
        class="space-y-4"
        x-data="{
            currentPageIds: @js($products->getCollection()->pluck('id')->values()),
            selectedIds: [],
            bulkDeleteUrl: @js(route('admin.produk.bulk-destroy')),
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
            get isAllOnPageSelected() {
                return this.currentPageIds.length > 0 && this.currentPageIds.every((id) => this.selectedIds.includes(id));
            }
        }"
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

        <div class="space-y-4 w-full">
        <!-- Header & Action Card -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Manajemen Produk</h2>
                    <p class="text-sm text-gray-500">Kelola katalog produk, harga, dan tautan marketplace.</p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <button
                        type="button"
                        x-show="selectedIds.length > 0"
                        x-cloak
                        @click="submitBulkDelete()"
                        class="inline-flex items-center gap-2 rounded-xl bg-rose-50 dark:bg-rose-900/20 text-rose-600 hover:bg-rose-600 hover:text-white transition-all text-xs font-bold uppercase tracking-wider border border-rose-100 dark:border-rose-900/10 px-4 py-2"
                    >
                        <i class="ti ti-trash"></i>
                        <span x-text="`Hapus (${selectedIds.length})`"></span>
                    </button>
                    
                    <div class="flex items-center gap-2">
                        <a href="{{ request()->filled('category_id') ? route('admin.produk.export-csv', ['category_id' => request('category_id')]) : route('admin.produk.export-csv') }}" class="flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-800 text-xs font-bold uppercase tracking-wider hover:bg-gray-50 dark:hover:bg-gray-800 transition-all">
                            <i class="ti ti-download"></i> Export
                        </a>
                        <form method="POST" action="{{ route('admin.produk.import-csv') }}" enctype="multipart/form-data" class="m-0">
                            @csrf
                            <input x-ref="csvFileInput" type="file" name="csv_file" accept=".csv,text/csv" required class="sr-only" @change="submitCsvImport($event)">
                            <button type="button" @click="triggerCsvPicker()" class="flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-800 text-xs font-bold uppercase tracking-wider hover:bg-gray-50 dark:hover:bg-gray-800 transition-all">
                                <i class="ti ti-file-upload"></i> Import
                            </button>
                        </form>
                        <a href="{{ route('admin.produk.create') }}" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition-all text-xs font-bold uppercase tracking-wider shadow-sm shadow-blue-200 dark:shadow-none">
                            <i class="ti ti-plus"></i>
                            Tambah Produk
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <form method="GET" action="{{ route('admin.produk.index') }}" class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800 flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[300px]" x-data="{ q: '{{ request('q') }}' }">
                    <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Pencarian Produk</label>
                    <div class="relative group">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center justify-center text-gray-400 group-focus-within:text-blue-600 transition-colors" style="width: 44px;">
                            <i class="ti ti-search text-xs"></i>
                        </div>
                        <input type="text" name="q" x-model="q" placeholder="Cari nama produk..." 
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium placeholder:text-gray-500 pr-10"
                            style="padding: 0.65rem 2.5rem 0.65rem 44px;">
                        
                        {{-- Clear Button --}}
                        <template x-if="q.length > 0">
                            <button type="button" @click="q = ''; $nextTick(() => $el.closest('form').submit())" class="absolute right-0 top-0 bottom-0 px-3 text-gray-400 hover:text-rose-500 transition-colors">
                                <i class="ti ti-circle-x text-sm"></i>
                            </button>
                        </template>
                    </div>
                </div>
                <div class="w-full md:w-56">
                    <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Filter Kategori</label>
                    <select name="category_id" class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium" style="padding: 0.65rem 1rem;">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700 transition shadow-sm">
                        Filter
                    </button>
                    @if (request()->anyFilled(['category_id', 'q']))
                        <a href="{{ route('admin.produk.index') }}" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 text-sm font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th class="px-6 py-4 text-center w-12">
                                <input type="checkbox" :checked="isAllOnPageSelected" @change="toggleSelectAllOnPage()" class="rounded border-gray-300 text-blue-600">
                            </th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-500">Produk</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-500 text-right">Harga</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-500 text-center">Featured</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-500 text-center">Status</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-500 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-sm">
                            @forelse ($products as $product)
                                <tr>
                                    <td class="px-4 py-3 text-center">
                                        <input type="checkbox" :checked="selectedIds.includes({{ $product->id }})" @change="toggleRowSelection({{ $product->id }})" class="rounded border-gray-300 text-blue-600">
                                    </td>
                                    <td class="px-4 py-3 min-w-[200px] max-w-[400px]">
                                        <p class="font-semibold text-gray-900 dark:text-gray-100 line-clamp-1" title="{{ $product->name }}">
                                            {{ $product->name }}
                                        </p>
                                        <div class="mt-1">
                                            <span class="inline-flex rounded bg-blue-50 dark:bg-blue-900/20 text-blue-600 px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wider">
                                                {{ $product->category?->name ?? 'Uncategorized' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-right font-semibold text-gray-900 dark:text-gray-100 whitespace-nowrap">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span @class([
                                            'inline-flex rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider',
                                            'bg-blue-100 text-blue-700' => $product->is_featured,
                                            'bg-gray-100 text-gray-500' => ! $product->is_featured,
                                        ])>
                                            {{ $product->is_featured ? 'Featured' : 'Regular' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span @class([
                                            'inline-flex rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider',
                                            'bg-emerald-100 text-emerald-700' => $product->status,
                                            'bg-rose-100 text-rose-700' => ! $product->status,
                                        ])>
                                            {{ $product->status ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.produk.edit', $product) }}" class="inline-flex items-center rounded-lg border border-gray-200 dark:border-gray-700 p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800" title="Edit">
                                                <i class="ti ti-pencil text-base"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.produk.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center rounded-lg border border-rose-100 dark:border-rose-900/10 p-2 text-rose-600 hover:bg-rose-600 hover:text-white transition-all" title="Hapus">
                                                    <i class="ti ti-trash text-base"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-10 text-center text-gray-500">Belum ada data produk.</td>
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
    </div>
@endsection









