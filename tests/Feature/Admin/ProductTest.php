<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected ProductService $service;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create(['is_admin' => true]);
        $this->admin->assignRole('admin'); // Assuming Spatie roles
        
        $this->service = app(ProductService::class);
    }

    public function test_admin_can_view_product_list(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.produk.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_create_product_with_service(): void
    {
        Storage::fake('public');
        $category = Category::factory()->create();
        
        $data = [
            'category_id' => $category->id,
            'name' => 'New Awesome Product',
            'price' => 150000,
            'status' => true,
            'sold_count' => 0,
            'likes_count' => 0,
            'rating_avg' => 5,
            'rating_count' => 0,
            'is_featured' => false,
            'show_in_promo' => false,
            'images' => [
                UploadedFile::fake()->image('product1.jpg')
            ],
            'marketplace_links' => [
                ['marketplace' => 'Shopee', 'url' => 'https://shopee.co.id/test'],
            ]
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.produk.store'), $data);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('products', ['name' => 'New Awesome Product']);
        
        $product = Product::where('name', 'New Awesome Product')->first();
        $this->assertCount(1, $product->images);
        $this->assertCount(1, $product->marketplaceLinks);
    }

    public function test_service_generates_unique_slugs(): void
    {
        $category = Category::factory()->create();
        Product::factory()->create(['name' => 'Test Product', 'slug' => 'test-product']);
        
        $newSlug = $this->service->makeUniqueSlug('Test Product');
        
        $this->assertEquals('test-product-1', $newSlug);
    }
}
