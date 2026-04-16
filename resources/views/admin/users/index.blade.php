@extends('admin.layouts.app')

@section('title', 'Pengguna')
@section('header', 'Pengguna')

@section('content')
    <div
        class="space-y-4 w-full"
        x-data="{
            currentPageIds: @js($users->getCollection()->pluck('id')->values()),
            selectedIds: [],
            bulkDeleteUrl: @js(route('admin.user.bulk-destroy')),
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
                if (!confirm(`Hapus ${this.selectedIds.length} user terpilih?`)) return;

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
        @if (session('error'))
            <div class="rounded-xl border border-rose-200 bg-rose-50 text-rose-700 px-4 py-3 text-sm font-semibold">
                {{ session('error') }}
            </div>
        @endif

        <div class="space-y-4">
            <!-- Header & Action Card -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-6 shadow-sm">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Manajemen Pengguna</h2>
                        <p class="text-sm text-gray-500">Kelola akun administrator dan member terdaftar.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <div x-data="{ 
                            showUpload() { this.$refs.fileInput.click() },
                            submit() { this.$refs.importForm.submit() }
                        }" class="flex items-center gap-2">
                            <form x-ref="importForm" action="{{ route('admin.user.import-csv') }}" method="POST" enctype="multipart/form-data" class="hidden">
                                @csrf
                                <input x-ref="fileInput" type="file" name="csv_file" accept=".csv" @change="submit()">
                            </form>
                            <button type="button" @click="showUpload()" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-800 px-4 py-2 text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-all">
                                <i class="ti ti-upload"></i>
                                Import
                            </button>
                            <a href="{{ route('admin.user.export-csv') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-800 px-4 py-2 text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-all">
                                <i class="ti ti-download"></i>
                                Export
                            </a>
                        </div>
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
                        <a href="{{ route('admin.user.create') }}" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition-all text-xs font-bold uppercase tracking-wider shadow-sm shadow-blue-200 dark:shadow-none">
                            <i class="ti ti-plus"></i>
                            Tambah Pengguna
                        </a>
                    </div>
                </div>

                <!-- Filters -->
                <form method="GET" action="{{ route('admin.user.index') }}" class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800 flex flex-wrap items-end gap-4">
                    <div class="flex-1 min-w-[300px]" x-data="{ q: '{{ request('q') }}' }">
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Pencarian Pengguna</label>
                        <div class="relative group">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center justify-center text-gray-400 group-focus-within:text-blue-600 transition-colors" style="width: 44px;">
                                <i class="ti ti-search text-xs"></i>
                            </div>
                            <input type="text" name="q" x-model="q" placeholder="Cari nama atau email..." 
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
                    <div class="w-full md:w-48">
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Filter Role</label>
                        <select name="role" class="w-full bg-gray-50/80 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 focus:bg-white dark:focus:bg-gray-900 rounded-xl outline-none transition-all duration-300 text-sm font-medium" style="padding: 0.65rem 1rem;">
                            <option value="">Semua Role</option>
                            <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                            <option value="member" @selected(request('role') === 'member')>Member</option>
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700 transition shadow-sm">
                            Filter
                        </button>
                        @if (request()->anyFilled(['role', 'q']))
                            <a href="{{ route('admin.user.index') }}" class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 text-sm font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Table Card -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 dark:bg-gray-800/50">
                            <tr>
                                <th class="px-6 py-4 text-center w-12 border-b border-gray-100 dark:border-gray-800">
                                    <input type="checkbox" :checked="isAllOnPageSelected" @change="toggleSelectAllOnPage()" class="rounded border-gray-300 text-blue-600">
                                </th>
                                <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-500 border-b border-gray-100 dark:border-gray-800">Pengguna</th>
                                <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-500 border-b border-gray-100 dark:border-gray-800 text-center">Role</th>
                                <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-500 border-b border-gray-100 dark:border-gray-800 text-center">Status Akun</th>
                                <th class="px-6 py-4 text-[11px] font-bold uppercase tracking-widest text-gray-500 border-b border-gray-100 dark:border-gray-800 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-sm">
                                @forelse ($users as $user)
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/50 transition-colors">
                                        <td class="px-4 py-3 text-center">
                                            <input type="checkbox" :checked="selectedIds.includes({{ $user->id }})" @change="toggleRowSelection({{ $user->id }})" class="rounded border-gray-300 text-blue-600">
                                        </td>
                                        <td class="px-4 py-3 min-w-[250px]">
                                            <div>
                                                <p class="font-semibold text-gray-900 dark:text-gray-100 leading-none">{{ $user->name }}</p>
                                                <p class="text-[10px] text-gray-500 mt-1.5">{{ $user->email }}</p>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span @class([
                                                'inline-flex rounded-lg px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider',
                                                'bg-indigo-100 text-indigo-700 shadow-sm shadow-indigo-100' => $user->hasRole('admin'),
                                                'bg-gray-100 text-gray-700' => ! $user->hasRole('admin'),
                                            ])>
                                                {{ $user->hasRole('admin') ? 'Admin' : 'Member' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span @class([
                                                'inline-flex rounded-lg px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider',
                                                'bg-rose-100 text-rose-700 shadow-sm shadow-rose-100' => $user->is_frozen,
                                                'bg-emerald-100 text-emerald-700 shadow-sm shadow-emerald-100' => ! $user->is_frozen,
                                            ])>
                                                {{ $user->is_frozen ? 'Dibekukan' : 'Aktif' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center justify-end gap-2">
                                                @if ($user->id !== auth()->id())
                                                    @if (!$user->is_frozen)
                                                        <form method="POST" action="{{ route('admin.user.freeze', $user) }}" onsubmit="return confirm('Bekukan akun user ini?')">
                                                            @csrf
                                                            <input type="hidden" name="freeze_reason" value="Dibekukan oleh admin">
                                                            <button type="submit" class="inline-flex items-center rounded-lg border border-amber-100 dark:border-amber-900/10 p-2 text-amber-600 hover:bg-amber-600 hover:text-white transition-all text-center" title="Bekukan">
                                                                <i class="ti ti-user-x text-base"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form method="POST" action="{{ route('admin.user.unfreeze', $user) }}" onsubmit="return confirm('Aktifkan kembali akun user ini?')">
                                                            @csrf
                                                            <button type="submit" class="inline-flex items-center rounded-lg border border-emerald-100 dark:border-emerald-900/10 p-2 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-all text-center" title="Aktifkan">
                                                                <i class="ti ti-user-check text-base"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <a href="{{ route('admin.user.edit', $user) }}" class="inline-flex items-center rounded-lg border border-gray-200 dark:border-gray-700 p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800" title="Edit">
                                                        <i class="ti ti-pencil text-base"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('admin.user.destroy', $user) }}" onsubmit="return confirm('Hapus user ini? Tindakan ini tidak dapat dibatalkan.')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center rounded-lg border border-rose-100 dark:border-rose-900/10 p-2 text-rose-600 hover:bg-rose-600 hover:text-white transition-all text-center" title="Hapus">
                                                            <i class="ti ti-trash text-base"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <div class="px-3 py-1.5 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 text-[10px] font-black uppercase tracking-widest border border-blue-100 dark:border-blue-800">
                                                        Akun Anda
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-10 text-center text-gray-500">Belum ada data pengguna.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-gray-100 dark:border-gray-800">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
@endsection









