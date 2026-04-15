<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends BaseApiController
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $perPage = (int) $request->integer('per_page', 20);
        
        $notifications = $user->notifications()
            ->paginate($perPage);

        return $this->success([
            'notifications' => collect($notifications->items())->map(function ($notification) {
                // Get base name if type is a class path
                $type = $notification->type;
                if (str_contains($type, '\\')) {
                    $parts = explode('\\', $type);
                    $type = end($parts);
                }
                
                // Remove 'Notification' suffix if present
                $type = str_replace('Notification', '', $type);
                $type = strtolower($type);

                return [
                    'id' => $notification->id,
                    'type' => $type,
                    'data' => $notification->data,
                    'read_at' => $notification->read_at ? $notification->read_at->toISOString() : null,
                    'created_at' => $notification->created_at->toISOString(),
                ];
            })->values(),
            'unread_count' => $user->unreadNotifications()->count(),
        ], 'Daftar notifikasi.', 200, [
            'current_page' => $notifications->currentPage(),
            'last_page' => $notifications->lastPage(),
            'per_page' => $notifications->perPage(),
            'total' => $notifications->total(),
        ]);
    }

    public function sendTestNotification(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $user->notify(new \App\Notifications\GeneralNotification(
            'Selamat Datang!',
            'Terima kasih telah bergabung di aplikasi Katalog. Cek produk terbaru kami!',
            'info'
        ));

        return $this->success(null, 'Notifikasi tes berhasil dikirim.');
    }

    public function markAsRead(Request $request, string $id): JsonResponse
    {
        $notification = $request->user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $notification->markAsRead();

        return $this->success(null, 'Notifikasi ditandai sudah dibaca.');
    }

    public function markAllAsRead(Request $request): JsonResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        return $this->success(null, 'Semua notifikasi ditandai sudah dibaca.');
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $notification = $request->user()
            ->notifications()
            ->where('id', $id)
            ->firstOrFail();

        $notification->delete();

        return $this->success(null, 'Notifikasi dihapus.');
    }
}
