@extends('admin.layouts.app')

@section('title', 'User')
@section('header', 'User')

@section('content')
    <div
        class="space-y-4"
        x-data="{
            init() {
                this.toggleBodyLock(this.drawerOpen);
                this.$watch('drawerOpen', (value) => this.toggleBodyLock(value));
            },
            drawerOpen: {{ ($editUser || $errors->any()) ? 'true' : 'false' }},
            isEdit: {{ $editUser ? 'true' : 'false' }},
            editId: @js(old('edit_id', $editUser->id ?? null)),
            usersMap: @js(
                $users->getCollection()->mapWithKeys(fn($item) => [
                    $item->id => [
                        'id' => $item->id,
                        'name' => $item->name,
                        'email' => $item->email,
                        'role' => $item->hasRole('admin') ? 'admin' : 'user',
                        'is_frozen' => (int) $item->is_frozen,
                        'freeze_reason' => $item->freeze_reason,
                    ],
                ])
            ),
            form: {
                name: @js(old('name', $editUser->name ?? '')),
                email: @js(old('email', $editUser->email ?? '')),
                password: '',
                role: @js(old('role', isset($editUser) ? ($editUser->hasRole('admin') ? 'admin' : 'user') : 'user')),
                is_frozen: @js((string) old('is_frozen', isset($editUser) ? (int) $editUser->is_frozen : 0)),
                freeze_reason: @js(old('freeze_reason', $editUser->freeze_reason ?? '')),
            },
            showPassword: false,
            currentPageIds: @js($users->getCollection()->pluck('id')->values()),
            selectedIds: [],
            storeUrl: @js(route('admin.user.store')),
            updateUrlTemplate: @js(route('admin.user.update', ['user' => '__ID__'])),
            bulkDeleteUrl: @js(route('admin.user.bulk-destroy')),
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
            openCreate() {
                this.drawerOpen = true;
                this.isEdit = false;
                this.editId = null;
                this.showPassword = false;
                this.form = {
                    name: '',
                    email: '',
                    password: '',
                    role: 'user',
                    is_frozen: '0',
                    freeze_reason: '',
                };
            },
            openEdit(id) {
                const item = this.usersMap[id];
                if (!item) return;
                this.drawerOpen = true;
                this.isEdit = true;
                this.editId = id;
                this.showPassword = false;
                this.form = {
                    name: item.name ?? '',
                    email: item.email ?? '',
                    password: '',
                    role: item.role ?? 'user',
                    is_frozen: String(item.is_frozen ?? 0),
                    freeze_reason: item.freeze_reason ?? '',
                };
            },
            closeDrawer() {
                this.drawerOpen = false;
                this.showPassword = false;
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
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Daftar User</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Kelola akun user.</p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <form method="GET" action="{{ route('admin.user.index') }}" class="flex flex-wrap items-center gap-2">
                        <select name="role" class="rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100 text-sm px-3 py-2.5 min-w-[160px]">
                            <option value="">Semua Role</option>
                            <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                            <option value="user" @selected(request('role') === 'user')>User</option>
                        </select>
                        <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                            <i class="ti ti-filter text-base"></i> Filter
                        </button>
                        @if (request()->filled('role'))
                            <a href="{{ route('admin.user.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
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
                    <button type="button" @click="openCreate()" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 transition">
                        <i class="ti ti-plus text-base"></i> Tambah
                    </button>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden">
                <div class="w-full overflow-x-auto">
                    <table class="w-full min-w-[980px] lg:min-w-0 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-800/60 text-gray-600 dark:text-gray-300">
                            <tr>
                                <th class="px-4 py-3 text-center w-12">
                                    <input type="checkbox" :checked="isAllOnPageSelected" @change="toggleSelectAllOnPage()" class="rounded border-gray-300 text-blue-600">
                                </th>
                                <th class="px-4 py-3 text-left">Nama</th>
                                <th class="px-4 py-3 text-left">Email</th>
                                <th class="px-4 py-3 text-center">Role</th>
                                <th class="px-4 py-3 text-center">Status</th>
                                <th class="px-4 py-3 text-left">Alasan Beku</th>
                                <th class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse ($users as $user)
                                <tr>
                                    <td class="px-4 py-3 text-center">
                                        <input type="checkbox" :checked="selectedIds.includes({{ $user->id }})" @change="toggleRowSelection({{ $user->id }})" class="rounded border-gray-300 text-blue-600">
                                    </td>
                                    <td class="px-4 py-3 font-semibold text-gray-900 dark:text-gray-100">{{ $user->name }}</td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $user->email }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span @class([
                                            'inline-flex rounded-full px-2.5 py-1 text-xs font-bold',
                                            'bg-indigo-100 text-indigo-700' => $user->hasRole('admin'),
                                            'bg-gray-200 text-gray-700' => ! $user->hasRole('admin'),
                                        ])>
                                            {{ $user->hasRole('admin') ? 'Admin' : 'User' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span @class([
                                            'inline-flex rounded-full px-2.5 py-1 text-xs font-bold',
                                            'bg-rose-100 text-rose-700' => $user->is_frozen,
                                            'bg-emerald-100 text-emerald-700' => ! $user->is_frozen,
                                        ])>
                                            {{ $user->is_frozen ? 'Dibekukan' : 'Aktif' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $user->freeze_reason ?: '-' }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-end gap-2">
                                            @if (!$user->is_frozen)
                                                <form method="POST" action="{{ route('admin.user.freeze', $user) }}">
                                                    @csrf
                                                    <input type="hidden" name="freeze_reason" value="Dibekukan oleh admin">
                                                    <button type="submit" class="inline-flex items-center rounded-lg border border-amber-200 p-2 text-amber-700 hover:bg-amber-50" title="Bekukan" aria-label="Bekukan">
                                                        <i class="ti ti-user-x text-base"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form method="POST" action="{{ route('admin.user.unfreeze', $user) }}">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center rounded-lg border border-emerald-200 p-2 text-emerald-700 hover:bg-emerald-50" title="Aktifkan" aria-label="Aktifkan">
                                                        <i class="ti ti-user-check text-base"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <button type="button" @click="openEdit({{ $user->id }})" class="inline-flex items-center rounded-lg border border-gray-200 dark:border-gray-700 p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800" title="Edit" aria-label="Edit">
                                                <i class="ti ti-pencil text-base"></i>
                                            </button>
                                            <form method="POST" action="{{ route('admin.user.destroy', $user) }}" onsubmit="return confirm('Hapus user ini?')">
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
                                    <td colspan="7" class="px-4 py-10 text-center text-gray-500">Belum ada data user.</td>
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

        <div x-show="drawerOpen" x-cloak x-transition.opacity class="admin-drawer-overlay fixed -inset-2 bg-black/70 z-[12000]" @click="closeDrawer()"></div>
        <aside
            class="admin-drawer-panel fixed inset-y-0 right-0 h-[100dvh] min-h-[100dvh] w-full sm:max-w-xl bg-white dark:bg-gray-900 border-l border-gray-200 dark:border-gray-800 z-[13000] shadow-2xl transform-gpu will-change-transform overflow-y-auto transition-transform duration-300 ease-out"
            :class="drawerOpen ? 'translate-x-0' : 'translate-x-full pointer-events-none'"
            x-cloak
        >
            <div class="p-5 md:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100" x-text="isEdit ? 'Edit User' : 'Tambah User'"></h3>
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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Nama</label>
                            <input type="text" name="name" x-model="form.name" required class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Email</label>
                            <input type="email" name="email" x-model="form.email" required class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Password <span class="text-gray-400" x-show="isEdit">(kosongkan jika tidak ganti)</span></label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" name="password" x-model="form.password" :required="!isEdit" class="w-full rounded-xl border-gray-300 pr-11 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 px-3 text-gray-500 hover:text-blue-600" :aria-label="showPassword ? 'Sembunyikan password' : 'Tampilkan password'" :title="showPassword ? 'Sembunyikan password' : 'Tampilkan password'">
                                <i class="ti" :class="showPassword ? 'ti-eye-off' : 'ti-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Role</label>
                            <select name="role" x-model="form.role" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Status Akun</label>
                            <select name="is_frozen" x-model="form.is_frozen" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                                <option value="0">Aktif</option>
                                <option value="1">Dibekukan</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Alasan Beku</label>
                        <input type="text" name="freeze_reason" x-model="form.freeze_reason" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                    </div>

                    @if ($errors->any())
                        <ul class="text-xs text-rose-600 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif

                    <div class="flex items-center gap-2 pt-1">
                        <button type="submit" class="inline-flex items-center rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 transition">
                            <span x-text="isEdit ? 'Update' : 'Simpan'"></span>
                        </button>
                        <button type="button" @click="closeDrawer()" class="inline-flex items-center rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-semibold px-4 py-2.5 hover:bg-gray-50 dark:hover:bg-gray-800">
                            Tutup
                        </button>
                    </div>
                </form>
            </div>
        </aside>
    </div>
@endsection









