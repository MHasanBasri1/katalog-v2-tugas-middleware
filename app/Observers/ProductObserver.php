<?php

namespace App\Observers;

use App\Models\Product;
use App\Jobs\BroadcastNewProductJob;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        // Hanya broadcast jika status produk aktif/published
        if ($product->status) {
            BroadcastNewProductJob::dispatch($product);
        }
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        // Jika sebelumnya non-aktif lalu diaktifkan, kita bisa anggap ini promo baru
        if ($product->wasChanged('status') && $product->status) {
            BroadcastNewProductJob::dispatch($product);
        }
    }
}
