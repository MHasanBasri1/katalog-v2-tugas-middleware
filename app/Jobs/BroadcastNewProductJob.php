<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\User;
use App\Notifications\GeneralNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class BroadcastNewProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected Product $product
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $title = 'Produk Baru Tersedia!';
        $message = "Halo! Kami baru saja menambahkan produk baru: {$this->product->name}. Cek sekarang sebelum kehabisan!";
        
        // Ambil semua user (bisa difilter jika ada role member khusus)
        // Kita gunakan chunk untuk menghindari memory limit jika user sangat banyak
        User::query()->chunk(100, function ($users) use ($title, $message) {
            Notification::send($users, new GeneralNotification($title, $message, 'success', [
                'product_slug' => $this->product->slug,
                'type' => 'new_product'
            ]));
        });
    }
}
