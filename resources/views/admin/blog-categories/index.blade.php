@extends('admin.layouts.app')

@section('title', 'Kategori Blog')
@section('header', 'Kategori Blog')

@section('content')
    <div
        class="space-y-4"
        x-data="{
            init() {
                this.toggleBodyLock(this.drawerOpen);
                this.$watch('drawerOpen', (value) => this.toggleBodyLock(value));
            },
            drawerOpen: {{ ($editBlogCategory || $errors->any()) ? 'true' : 'false' }},
            isEdit: {{ $editBlogCategory ? 'true' : 'false' }},
            editId: @js(old('edit_id', $editBlogCategory->id ?? null)),
            form: {
                name: @js(old('name', $editBlogCategory->name ?? '')),
                slug: @js(old('slug', $editBlogCategory->slug ?? '')),
            },
            currentPageIds: @js($blogCategories->getCollection()->pluck('id')->values()),
            selectedIds: [],
            storeUrl: @js(route('admin.blog-kategori.store')),
            updateUrlTemplate: @js(route('admin.blog-kategori.update', ['blog_kategori' => '__ID__'])),
            bulkDeleteUrl: @js(route('admin.blog-kategori.bulk-destroy')),
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
                if (!confirm(`Hapus ${this.selectedIds.length} kategori blog terpilih?`)) return;

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
            openCreate() {
                this.drawerOpen = true;
                this.isEdit = false;
                this.editId = null;
                this.form = { name: '', slug: '' };
            },
            openEdit(item) {
                this.drawerOpen = true;
                this.isEdit = true;
                this.editId = item.id;
                this.form = {
                    name: item.name ?? '',
                    slug: item.slug ?? '',
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

        <div class="space-y-4">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Daftar Kategori Blog</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Kelola kategori blog.</p>
                </div>
                <div class="flex items-center gap-2">
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
                    <button type="button" @click="openCreate()" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 transition">
                        <i class="ti ti-plus text-base"></i> Tambah
                    </button>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden">
                <div class="w-full overflow-x-auto">
                    <table class="w-full min-w-[760px] lg:min-w-0 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-800/60 text-gray-600 dark:text-gray-300">
                            <tr>
                                <th class="px-4 py-3 text-center w-12">
                                    <input type="checkbox" :checked="isAllOnPageSelected" @change="toggleSelectAllOnPage()" class="rounded border-gray-300 text-blue-600">
                                </th>
                                <th class="px-4 py-3 text-left">Nama</th>
                                <th class="px-4 py-3 text-left">Slug</th>
                                <th class="px-4 py-3 text-center">Artikel</th>
                                <th class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse ($blogCategories as $category)
                                <tr>
                                    <td class="px-4 py-3 text-center">
                                        <input type="checkbox" :checked="selectedIds.includes({{ $category->id }})" @change="toggleRowSelection({{ $category->id }})" class="rounded border-gray-300 text-blue-600">
                                    </td>
                                    <td class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100">{{ $category->name }}</td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $category->slug }}</td>
                                    <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-300">{{ number_format($category->blogs_count) }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-end gap-2">
                                            <button
                                                type="button"
                                                data-id="{{ $category->id }}"
                                                data-name="{{ $category->name }}"
                                                data-slug="{{ $category->slug }}"
                                                @click="openEdit({ id: Number($el.dataset.id), name: $el.dataset.name, slug: $el.dataset.slug })"
                                                class="inline-flex items-center rounded-lg border border-gray-200 dark:border-gray-700 p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800"
                                                title="Edit"
                                                aria-label="Edit"
                                            >
                                                <i class="ti ti-pencil text-base"></i>
                                            </button>
                                            <form method="POST" action="{{ route('admin.blog-kategori.destroy', $category) }}" onsubmit="return confirm('Hapus kategori blog ini?')">
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
                                    <td colspan="5" class="px-4 py-10 text-center text-gray-500">Belum ada data kategori blog.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-gray-100 dark:border-gray-800">
                    {{ $blogCategories->links() }}
                </div>
            </div>
        </div>

        <div x-show="drawerOpen" x-cloak x-transition.opacity class="admin-drawer-overlay fixed -inset-2 bg-black/70 z-[12000]" @click="closeDrawer()"></div>
        <aside
            class="admin-drawer-panel fixed inset-y-0 right-0 h-[100dvh] min-h-[100dvh] w-full sm:max-w-md bg-white dark:bg-gray-900 border-l border-gray-200 dark:border-gray-800 z-[13000] shadow-2xl transform-gpu will-change-transform overflow-y-auto transition-transform duration-300 ease-out"
            :class="drawerOpen ? 'translate-x-0' : 'translate-x-full pointer-events-none'"
            x-cloak
        >
            <div class="p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100">
                        <span x-text="isEdit ? 'Edit Kategori Blog' : 'Tambah Kategori Blog'"></span>
                    </h3>
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
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Nama</label>
                        <input type="text" name="name" x-model="form.name" required class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Slug</label>
                        <input type="text" name="slug" x-model="form.slug" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
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









