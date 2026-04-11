<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductGallery extends Component
{
    use WithFileUploads;

    public Product $product;
    public $newImages = [];

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    public function updatedNewImages()
    {
        $this->validate([
            'newImages.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $currentCount = $this->product->images()->count();
        $uploadCount = count($this->newImages);

        if ($currentCount + $uploadCount > 10) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Maksimal 10 gambar diperbolehkan.'
            ]);
            $this->newImages = [];
            return;
        }

        foreach ($this->newImages as $image) {
            $path = $image->store('products', 'public');
            $this->product->images()->create([
                'image' => $path,
                'is_primary' => !$this->product->images()->exists(),
            ]);
        }

        $this->newImages = [];
        $this->clearProductCaches();

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Gambar berhasil ditambahkan.'
        ]);
    }

    public function setPrimary($imageId)
    {
        $image = ProductImage::find($imageId);
        if (!$image || $image->product_id !== $this->product->id) {
            return;
        }

        $this->product->images()->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);
        
        $this->clearProductCaches();

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Gambar utama berhasil diperbarui.'
        ]);
    }

    public function deleteImage($imageId)
    {
        $image = ProductImage::find($imageId);
        if (!$image || $image->product_id !== $this->product->id) {
            return;
        }

        $isPrimary = $image->is_primary;

        if (Storage::disk('public')->exists($image->image)) {
            Storage::disk('public')->delete($image->image);
        }

        $image->delete();

        if ($isPrimary) {
            $nextImage = $this->product->images()->first();
            if ($nextImage) {
                $nextImage->update(['is_primary' => true]);
            }
        }
        
        $this->clearProductCaches();

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Gambar berhasil dihapus.'
        ]);
    }

    private function clearProductCaches(): void
    {
        Cache::forget('public.home.bestseller_products');
        Cache::forget('public.home.flashsale_products');
        Cache::forget('public.home.new_products');
    }

    public function render()
    {
        return view('livewire.admin.product-gallery', [
            'images' => $this->product->images()->get()
        ]);
    }
}
