<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ProductDetail\ProductMediaPresenter;
use Database\Seeders\BrandSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductMediaOptimizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_and_gallery_offer_available_responsive_webp_images(): void
    {
        Storage::fake('public');
        $this->seed([CategorySeeder::class, BrandSeeder::class, ProductSeeder::class]);
        $product = Product::query()->where('status', 'published')->firstOrFail();
        $product->images()->create([
            'disk' => 'public',
            'path' => 'products/binocular.jpg',
            'is_primary' => true,
        ]);
        Storage::disk('public')->put('products/binocular.jpg', 'original');
        Storage::disk('public')->put('products/binocular.jpg.480.webp', 'small');
        Storage::disk('public')->put('products/binocular.jpg.960.webp', 'medium');

        $home = $this->get('/es');
        $detail = $this->get('/es/productos/'.$product->slug);

        foreach ([$home, $detail] as $response) {
            $response->assertOk()
                ->assertSee('binocular.jpg.960.webp', false)
                ->assertSee('binocular.jpg.480.webp 480w', false)
                ->assertSee('binocular.jpg.960.webp 960w', false)
                ->assertDontSee('binocular.jpg.1440.webp', false);
        }
        Storage::disk('public')->assertExists('products/binocular.jpg');
    }

    public function test_images_without_optimized_copies_keep_the_original_url(): void
    {
        Storage::fake('public');
        $image = new ProductImage(['disk' => 'public', 'path' => 'products/new.jpg']);
        $product = new Product;
        $product->setRelation('images', collect([$image]));

        $media = $this->app->make(ProductMediaPresenter::class)->build($product, 'es', 'Falco');

        $this->assertSame(url(Storage::disk('public')->url('products/new.jpg')), $media['primaryImageUrl']);
        $this->assertNull($media['primaryImageSrcset']);
        $this->assertSame($media['primaryImageUrl'], $media['gallery']->first()['url']);
    }

    public function test_products_without_images_keep_the_placeholder(): void
    {
        $product = new Product;
        $product->setRelation('images', collect());

        $media = $this->app->make(ProductMediaPresenter::class)->build($product, 'es', 'Falco');

        $this->assertNull($media['primaryImageUrl']);
        $this->assertNull($media['primaryImageSrcset']);
        $this->assertCount(0, $media['gallery']);
    }
}
