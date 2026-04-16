<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class LogActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log for authenticated users
        if (Auth::check()) {
            $user = Auth::user();
            $method = $request->method();
            $path = $request->path();
            
            // Only log mutating actions (POST, PUT, PATCH, DELETE)
            // or specific actions if needed
            if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
                // Ignore bulk delete or complex actions if you want, 
                // but let's try to make it smart.
                
                $description = $this->generateDescription($request, $user);
                
                if ($description) {
                    ActivityLog::create([
                        'user_id' => $user->id,
                        'description' => $description,
                        'activity_type' => $this->getActivityType($path),
                        'icon' => $this->getIcon($method),
                        'color' => $this->getColor($method),
                    ]);
                }
            }
        }

        return $response;
    }

    private function generateDescription(Request $request, $user)
    {
        $path = $request->path();
        $segments = explode('/', $path);
        
        $module = $segments[1] ?? 'system';
        $subAction = $segments[2] ?? '';
        $method = $request->method();
        
        // Translate actions
        $action = match ($method) {
            'POST' => 'menambah',
            'PUT', 'PATCH' => 'mengubah',
            'DELETE' => 'menghapus',
            default => 'melakukan aksi pada',
        };

        // Handle specific sub-actions
        if ($subAction === 'bulk-delete') {
            $count = count($request->input('selected_ids', []));
            $action = "menghapus massal {$count}";
        } elseif ($subAction === 'import-csv') {
            $action = "melakukan import data";
        } elseif ($subAction === 'freeze') {
            $action = "membekukan";
        } elseif ($subAction === 'unfreeze') {
            $action = "mengaktifkan kembali";
        }

        // Translate modules
        $moduleMap = [
            'produk' => 'produk',
            'kategori' => 'kategori produk',
            'blog' => 'artikel blog',
            'blog-kategori' => 'kategori blog',
            'banner' => 'banner',
            'user' => 'user',
            'halaman-statis' => 'halaman statis',
            'setting' => 'pengaturan',
            'marketplace-link' => 'link marketplace',
            'voucher' => 'voucher',
            'logs' => 'log sistem',
        ];

        $moduleName = $moduleMap[$module] ?? $module;

        return "{$user->name} {$action} {$moduleName}";
    }

    private function getActivityType($path)
    {
        $segments = explode('/', $path);
        return $segments[1] ?? 'general';
    }

    private function getIcon($method)
    {
        return match ($method) {
            'POST' => 'ti-plus',
            'PUT', 'PATCH' => 'ti-edit',
            'DELETE' => 'ti-trash',
            default => 'ti-info-circle',
        };
    }

    private function getColor($method)
    {
        return match ($method) {
            'POST' => 'emerald',
            'PUT', 'PATCH' => 'blue',
            'DELETE' => 'rose',
            default => 'gray',
        };
    }
}
