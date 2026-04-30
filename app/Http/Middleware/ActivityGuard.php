<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ActivityGuard
{
    /**
     * Static property agar data log bertahan lintas instance.
     * Ini diperlukan karena Laravel bisa membuat instance baru
     * untuk handle() dan terminate() dalam beberapa konfigurasi.
     */
    private static ?array $pendingLog = null;

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Reset dulu agar tidak ada data stale dari request sebelumnya
        self::$pendingLog = null;

        // Hanya log GET request halaman — abaikan endpoint internal Livewire
        $shouldLog = $request->isMethod('GET')
            && ! str_starts_with($request->path(), 'livewire/');

        if (! auth()->check()) {
            if (in_array('guest', $roles)) {
                if ($shouldLog) {
                    self::$pendingLog = [
                        'username'   => 'guest',
                        'role'       => 'guest',
                        'activity'   => $this->describeActivity($request),
                        'ip_address' => $request->ip(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                return $next($request);
            }
            abort(403, 'Unauthorized access.');
        }

        $user     = auth()->user();
        $userRole = $user->roles->first()->name ?? ($user->is_admin ? 'admin' : 'member');

        if (! in_array($userRole, $roles)) {
            abort(403, 'Unauthorized role access.');
        }

        if ($shouldLog) {
            self::$pendingLog = [
                'username'   => $user->username ?? $user->name ?? $user->email,
                'role'       => $userRole,
                'activity'   => $this->describeActivity($request),
                'ip_address' => $request->ip(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        return $next($request);
    }

    /**
     * Dipanggil otomatis oleh Laravel SETELAH response dikirim ke browser.
     * Dengan static property, data log tetap tersedia meski instance berbeda.
     */
    public function terminate(Request $request, Response $response): void
    {
        if (self::$pendingLog === null) {
            return;
        }

        $data             = self::$pendingLog;
        self::$pendingLog = null; // Reset segera agar tidak dobel

        try {
            DB::table('activity_logs')->insert($data);
        } catch (\Throwable $e) {
            Log::warning('ActivityGuard: gagal menyimpan log', [
                'error'    => $e->getMessage(),
                'activity' => $data['activity'] ?? '-',
            ]);
        }
    }

    private function describeActivity(Request $request): string
    {
        $path     = $request->path();
        $segments = explode('/', $path);

        $pageMap = [
            ''              => 'mengunjungi Beranda',
            'produk'        => 'melihat Katalog Produk',
            'kategori'      => 'melihat Kategori',
            'blog'          => 'melihat Blog',
            'masuk'         => 'membuka halaman Login',
            'daftar'        => 'membuka halaman Registrasi',
            'dashboard'     => 'mengakses Dashboard Member',
            'admin'         => 'mengakses Panel Admin',
        ];

        $firstSegment  = $segments[0] ?? '';
        $secondSegment = $segments[1] ?? '';

        // Admin panel sub-pages: admin/dashboard, admin/produk, dll
        if ($firstSegment === 'admin') {
            $adminPageMap = [
                'dashboard'      => 'mengakses Dashboard Admin',
                'produk'         => 'mengelola Produk',
                'kategori'       => 'mengelola Kategori',
                'blog'           => 'mengelola Blog',
                'user'           => 'mengelola Pengguna',
                'setting'        => 'mengakses Pengaturan',
                'logs'           => 'melihat Log Aktivitas',
                'voucher'        => 'mengelola Voucher',
                'banner'         => 'mengelola Banner',
                'statistics'     => 'melihat Statistik',
                'halaman-statis' => 'mengelola Halaman Statis',
            ];
            return $adminPageMap[$secondSegment] ?? "mengakses admin/{$secondSegment}";
        }

        // Detail produk: produk/{slug}
        if ($firstSegment === 'produk' && $secondSegment !== '') {
            return "melihat detail produk: {$secondSegment}";
        }

        // Detail blog: blog/{slug}
        if ($firstSegment === 'blog' && $secondSegment !== '') {
            return "membaca artikel: {$secondSegment}";
        }

        // Detail kategori: kategori/{slug}
        if ($firstSegment === 'kategori' && $secondSegment !== '') {
            return "melihat kategori: {$secondSegment}";
        }

        return $pageMap[$firstSegment] ?? "mengakses halaman: /{$path}";
    }
}
