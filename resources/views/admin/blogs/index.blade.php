@extends('admin.layouts.app')

@section('title', 'Blog')
@section('header', 'Blog')

@section('content')
    <div
        class="space-y-4 w-full"
        x-data="{
            currentPageIds: @js($blogs->getCollection()->pluck('id')->values()),
            selectedIds: [],
            bulkDeleteUrl: @js(route('admin.blog.bulk-destroy')),
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
                if (!confirm(`Hapus ${this.selectedIds.length} artikel blog terpilih?`)) return;

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

        <!-- Header & Action Card -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Manajemen Blog</h2>
                    <p class="text-sm text-gray-500">Kelola artikel, berita, dan panduan untuk pengguna.</p>
                </div>
                <div class="flex items-center gap-2">
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
                    <a href="{{ route('admin.blog.create') }}" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition-all text-xs font-bold uppercase tracking-wider shadow-sm shadow-blue-200 dark:shadow-none">
                        <i class="ti ti-plus"></i>
                        Tambah Artikel
                    </a>
                </div>
            </div>

            <!-- Filters -->
            <form method="GET" action="{{ route('admin.blog.index') }}" class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800 flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[300px]">
                    <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Pencarian Artikel</label>
                    <div class="relative group">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center justify-center text-gray-400 group-focus-within:text-blue-600 transition-colors" style="width: 44px;">
                            <i class="ti ti-search text-xs"></i>
                        </div>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul artikel..." 
                            class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium placeholder:text-gray-500"
                            style="padding: 0.65rem 1rem 0.65rem 44px;">
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
                        <a href="{{ route('admin.blog.index') }}" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 text-sm font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th class="px-6 py-4 text-center w-12 border-b border-gray-100 dark:border-gray-800">
                                <input type="checkbox" :checked="isAllOnPageSelected" @change="toggleSelectAllOnPage()" class="rounded border-gray-300 text-blue-600">
                            </th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-500 border-b border-gray-100 dark:border-gray-800">Artikel</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-500 border-b border-gray-100 dark:border-gray-800 text-center">Cover</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-500 border-b border-gray-100 dark:border-gray-800">Kategori</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-500 border-b border-gray-100 dark:border-gray-800 text-center">Status</th>
                            <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-500 border-b border-gray-100 dark:border-gray-800 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-sm">
                            @forelse ($blogs as $blog)
                                <tr>
                                    <td class="px-4 py-3 text-center">
                                        <input type="checkbox" :checked="selectedIds.includes({{ $blog->id }})" @change="toggleRowSelection({{ $blog->id }})" class="rounded border-gray-300 text-blue-600">
                                    </td>
                                    <td class="px-4 py-3 min-w-[300px]">
                                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $blog->title }}</p>
                                        <p class="text-[10px] text-gray-500 font-mono">#{{ $blog->id }} • {{ $blog->author_name }} • {{ optional($blog->published_at)->format('d M Y') ?: '-' }}</p>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($blog->cover_image)
                                            <img src="{{ asset('storage/' . $blog->cover_image) }}" class="w-12 h-12 object-cover rounded-lg border border-gray-100 dark:border-gray-800 mx-auto" alt="Cover">
                                        @else
                                            <div class="w-12 h-12 rounded-lg bg-gray-50 dark:bg-gray-800 border border-dashed border-gray-200 dark:border-gray-700 flex items-center justify-center mx-auto">
                                                <i class="ti ti-photo text-gray-400"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 px-2 py-1 text-[10px] font-bold uppercase tracking-wider">
                                            {{ $blog->category?->name ?? 'Uncategorized' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span @class([
                                            'inline-flex rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider',
                                            'bg-emerald-100 text-emerald-700 shadow-sm shadow-emerald-100' => $blog->is_published,
                                            'bg-gray-100 text-gray-700' => ! $blog->is_published,
                                        ])>
                                            {{ $blog->is_published ? 'Published' : 'Draft' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.blog.edit', $blog) }}" class="inline-flex items-center rounded-lg border border-gray-200 dark:border-gray-700 p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800" title="Edit">
                                                <i class="ti ti-pencil text-base"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.blog.destroy', $blog) }}" onsubmit="return confirm('Hapus artikel ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center rounded-lg border border-rose-100 dark:border-rose-900/10 p-2 text-rose-600 hover:bg-rose-600 hover:text-white transition-all text-center" title="Hapus">
                                                    <i class="ti ti-trash text-base"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-10 text-center text-gray-500">Belum ada artikel blog.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-gray-100 dark:border-gray-800">
                    {{ $blogs->links() }}
                </div>
        </div>
    </div>
@endsection









