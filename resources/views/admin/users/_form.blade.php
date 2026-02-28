@php
    /** @var \App\Models\User|null $user */
    $isEdit = isset($user) && $user->exists;
    $selectedRole = old('role', $isEdit ? ($user->hasRole('admin') ? 'admin' : 'user') : 'user');
@endphp

<form method="POST" action="{{ $isEdit ? route('admin.user.update', $user) : route('admin.user.store') }}" class="space-y-4">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div>
            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Nama</label>
            <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" required class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
        </div>
    </div>

    <div>
        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Password {{ $isEdit ? '(kosongkan jika tidak ganti)' : '' }}</label>
        <input type="password" name="password" {{ $isEdit ? '' : 'required' }} class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div>
            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Role</label>
            <select name="role" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                <option value="user" @selected($selectedRole === 'user')>User</option>
                <option value="admin" @selected($selectedRole === 'admin')>Admin</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Status Akun</label>
            <select name="is_frozen" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
                <option value="0" @selected((string) old('is_frozen', isset($user) ? (int) $user->is_frozen : 0) === '0')>Aktif</option>
                <option value="1" @selected((string) old('is_frozen', isset($user) ? (int) $user->is_frozen : 0) === '1')>Dibekukan</option>
            </select>
        </div>
    </div>

    <div>
        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Alasan Beku</label>
        <input type="text" name="freeze_reason" value="{{ old('freeze_reason', $user->freeze_reason ?? '') }}" class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-950 dark:text-gray-100">
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
            {{ $isEdit ? 'Update' : 'Simpan' }}
        </button>
        <a href="{{ route('admin.user.index') }}" class="inline-flex items-center rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-semibold px-4 py-2.5 hover:bg-gray-50 dark:hover:bg-gray-800">
            Kembali
        </a>
    </div>
</form>
