<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::query()
            ->when(
                $request->filled('role'),
                fn($query) => $query->whereHas('roles', fn($roleQuery) => $roleQuery->where('name', $request->string('role')->toString()))
            )
            ->when(
                $request->filled('q'),
                fn($query) => $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->q . '%')
                        ->orWhere('email', 'like', '%' . $request->q . '%');
                })
            )
            ->latest('id')
            ->paginate(25)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatePayload($request);
        $role = $data['role'] ?? 'user';

        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make((string) $data['password']),
            'is_admin' => $role === 'admin',
            'is_frozen' => (bool) $data['is_frozen'],
            'frozen_at' => (bool) $data['is_frozen'] ? now() : null,
            'freeze_reason' => (bool) $data['is_frozen'] ? ($data['freeze_reason'] ?? null) : null,
        ];

        $user = User::query()->create($payload);

        $this->ensureRolesExist();
        $user->syncRoles([$role]);

        if ($request->input('action') === 'save_and_another') {
            return redirect()->route('admin.user.create')->with('status', 'User berhasil ditambahkan. Silahkan tambah user lainnya.');
        }

        return redirect()->route('admin.user.index')->with('status', 'User berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', ['user' => $user]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $this->validatePayload($request, $user->id);
        $role = $data['role'] ?? 'user';

        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
            'is_admin' => $role === 'admin',
            'is_frozen' => (bool) $data['is_frozen'],
            'frozen_at' => (bool) $data['is_frozen'] ? ($user->frozen_at ?: now()) : null,
            'freeze_reason' => (bool) $data['is_frozen'] ? ($data['freeze_reason'] ?? null) : null,
        ];

        if (!empty($data['password'])) {
            $payload['password'] = Hash::make((string) $data['password']);
        }

        $user->update($payload);
        $this->ensureRolesExist();
        $user->syncRoles([$role]);

        return redirect()->route('admin.user.index')->with('status', 'User berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Akun yang sedang digunakan tidak bisa dihapus.');
        }

        $user->delete();

        return redirect()->route('admin.user.index')->with('status', 'User berhasil dihapus.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'selected_ids' => ['required', 'array', 'min:1'],
            'selected_ids.*' => ['integer', 'distinct', 'exists:users,id'],
        ]);

        $currentUserId = (int) auth()->id();
        $targetIds = collect($validated['selected_ids'])
            ->map(fn($id) => (int) $id)
            ->filter(fn($id) => $id !== $currentUserId)
            ->values();

        if ($targetIds->isEmpty()) {
            return redirect()->route('admin.user.index')->with('error', 'Tidak ada user yang bisa dihapus.');
        }

        $deleted = User::query()
            ->whereIn('id', $targetIds)
            ->delete();

        $skipped = count($validated['selected_ids']) - $deleted;
        $message = "{$deleted} user berhasil dihapus.";
        if ($skipped > 0) {
            $message .= " {$skipped} user dilewati (akun yang sedang digunakan).";
        }

        return redirect()->route('admin.user.index')->with('status', $message);
    }

    public function freeze(Request $request, User $user): RedirectResponse
    {
        if ($request->user()->id === $user->id) {
            return back()->with('error', 'Akun yang sedang digunakan tidak bisa dibekukan.');
        }

        $reason = trim((string) $request->input('freeze_reason', ''));

        $user->update([
            'is_frozen' => true,
            'frozen_at' => now(),
            'freeze_reason' => $reason !== '' ? $reason : null,
        ]);

        return back()->with('status', 'Akun user berhasil dibekukan.');
    }

    public function unfreeze(User $user): RedirectResponse
    {
        $user->update([
            'is_frozen' => false,
            'frozen_at' => null,
            'freeze_reason' => null,
        ]);

        return back()->with('status', 'Akun user sudah diaktifkan kembali.');
    }

    public function exportCsv()
    {
        $filename = 'users-export-' . now()->format('Y-m-d-His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Name', 'Email', 'Role', 'Status']);

            User::query()->chunk(100, function ($users) use ($file) {
                foreach ($users as $user) {
                    fputcsv($file, [
                        $user->name,
                        $user->email,
                        $user->hasRole('admin') ? 'admin' : 'user',
                        $user->is_frozen ? 'frozen' : 'active',
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importCsv(Request $request): RedirectResponse
    {
        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        
        // Skip header
        $header = fgetcsv($handle);
        
        $imported = 0;
        $skipped = 0;
        $errors = [];

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 3) {
                $skipped++;
                continue;
            }

            $name = $row[0];
            $email = $row[1];
            $password = $row[2];
            $role = $row[3] ?? 'user';
            $status = $row[4] ?? 'active';

            if (User::where('email', $email)->exists()) {
                $skipped++;
                continue;
            }

            try {
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make($password),
                    'is_admin' => $role === 'admin',
                    'is_frozen' => $status === 'frozen',
                ]);

                $this->ensureRolesExist();
                $user->assignRole($role);
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Error importing $email: " . $e->getMessage();
                $skipped++;
            }
        }

        fclose($handle);

        $message = "Import selesai. Berhasil: $imported, Dilewati: $skipped.";
        if (count($errors) > 0) {
            return redirect()->route('admin.user.index')->with('status', $message)->with('error_list', $errors);
        }

        return redirect()->route('admin.user.index')->with('status', $message);
    }

    private function validatePayload(Request $request, ?int $ignoreId = null): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($ignoreId)],
            'role' => ['required', 'string', Rule::in(['admin', 'user'])],
            'is_frozen' => ['required', 'boolean'],
            'freeze_reason' => ['nullable', 'string', 'max:255'],
        ];

        if ($ignoreId) {
            $rules['password'] = ['nullable', 'string', 'min:8'];
        } else {
            $rules['password'] = ['required', 'string', 'min:8'];
        }

        return $request->validate($rules);
    }

    private function ensureRolesExist(): void
    {
        Role::findOrCreate('admin', 'web');
        Role::findOrCreate('user', 'web');
    }
}
