<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Database\Seeders\BrandSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminProductMediaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            CategorySeeder::class,
            BrandSeeder::class,
            ProductSeeder::class,
        ]);
    }

    public function test_admin_can_update_variant_specifications(): void
    {
        $admin = $this->createAdmin();
        $product = $this->getProduct();

        $variant = $product->variants->first();

        $payload = $this->productPayload($product);

        $payload['variants'][$variant->id]['specifications'][] = [
            'key' => 'weight',
            'value' => '650 g',
        ];

        $response = $this
            ->actingAs($admin)
            ->put(
                route(
                    'admin.products.update',
                    $product
                ),
                $payload
            );

        $response->assertRedirect(
            route(
                'admin.products.edit',
                $product
            )
        );

        $variant->refresh();

        $this->assertSame(
            '650 g',
            $variant->specifications['weight']
        );
    }

    public function test_first_uploaded_image_becomes_primary(): void
    {
        Storage::fake('public');

        $admin = $this->createAdmin();
        $product = $this->getProduct();

        $variant = $product->variants->first();

        $response = $this
            ->actingAs($admin)
            ->post(
                route(
                    'admin.products.images.store',
                    $product
                ),
                [
                    'image' => UploadedFile::fake()
                        ->image(
                            'falco.jpg',
                            1200,
                            900
                        ),

                    'variant_id' => $variant->id,

                    'alt_es' =>
                        'Binocular Natviewer Falco',

                    'alt_en' =>
                        'Natviewer Falco binocular',

                    'sort_order' => 10,

                    'make_primary' => 0,
                ]
            );

        $response->assertRedirect(
            route(
                'admin.products.edit',
                $product
            )
        );

        $image = ProductImage::query()
            ->firstOrFail();

        $this->assertTrue(
            $image->is_primary
        );

        $this->assertSame(
            $variant->id,
            $image->variant_id
        );

        $this->assertSame(
            'Binocular Natviewer Falco',
            $image->alt_es
        );

        Storage::disk('public')
            ->assertExists($image->path);
    }

    public function test_admin_can_update_image_metadata(): void
    {
        Storage::fake('public');

        $admin = $this->createAdmin();
        $product = $this->getProduct();

        $firstVariant =
            $product->variants->first();

        $secondVariant =
            $product->variants->skip(1)->first();

        $image = $product
            ->images()
            ->create([
                'variant_id' =>
                    $firstVariant->id,

                'disk' => 'public',

                'path' =>
                    'products/'.$product->id.'/test.jpg',

                'alt_es' =>
                    'Imagen anterior',

                'alt_en' =>
                    'Previous image',

                'is_primary' => true,

                'sort_order' => 10,
            ]);

        $response = $this
            ->actingAs($admin)
            ->put(
                route(
                    'admin.products.images.update',
                    [
                        $product,
                        $image,
                    ]
                ),
                [
                    'variant_id' =>
                        $secondVariant->id,

                    'alt_es' =>
                        'Nueva descripción ES',

                    'alt_en' =>
                        'New English description',

                    'sort_order' => 30,
                ]
            );

        $response->assertRedirect(
            route(
                'admin.products.edit',
                $product
            )
        );

        $image->refresh();

        $this->assertSame(
            $secondVariant->id,
            $image->variant_id
        );

        $this->assertSame(
            'Nueva descripción ES',
            $image->alt_es
        );

        $this->assertSame(
            'New English description',
            $image->alt_en
        );

        $this->assertSame(
            30,
            $image->sort_order
        );
    }

    public function test_admin_can_change_primary_image(): void
    {
        $admin = $this->createAdmin();
        $product = $this->getProduct();

        $firstImage = $product
            ->images()
            ->create([
                'disk' => 'public',
                'path' => 'products/1/one.jpg',
                'is_primary' => true,
                'sort_order' => 10,
            ]);

        $secondImage = $product
            ->images()
            ->create([
                'disk' => 'public',
                'path' => 'products/1/two.jpg',
                'is_primary' => false,
                'sort_order' => 20,
            ]);

        $response = $this
            ->actingAs($admin)
            ->patch(
                route(
                    'admin.products.images.primary',
                    [
                        $product,
                        $secondImage,
                    ]
                )
            );

        $response->assertRedirect(
            route(
                'admin.products.edit',
                $product
            )
        );

        $this->assertFalse(
            $firstImage
                ->fresh()
                ->is_primary
        );

        $this->assertTrue(
            $secondImage
                ->fresh()
                ->is_primary
        );
    }

    public function test_deleting_primary_image_promotes_next_image(): void
    {
        Storage::fake('public');

        $admin = $this->createAdmin();
        $product = $this->getProduct();

        Storage::disk('public')->put(
            'products/1/one.jpg',
            'test'
        );

        Storage::disk('public')->put(
            'products/1/two.jpg',
            'test'
        );

        $firstImage = $product
            ->images()
            ->create([
                'disk' => 'public',
                'path' => 'products/1/one.jpg',
                'is_primary' => true,
                'sort_order' => 10,
            ]);

        $secondImage = $product
            ->images()
            ->create([
                'disk' => 'public',
                'path' => 'products/1/two.jpg',
                'is_primary' => false,
                'sort_order' => 20,
            ]);

        $response = $this
            ->actingAs($admin)
            ->delete(
                route(
                    'admin.products.images.destroy',
                    [
                        $product,
                        $firstImage,
                    ]
                )
            );

        $response->assertRedirect(
            route(
                'admin.products.edit',
                $product
            )
        );

        $this->assertDatabaseMissing(
            'product_images',
            [
                'id' => $firstImage->id,
            ]
        );

        $this->assertTrue(
            $secondImage
                ->fresh()
                ->is_primary
        );

        Storage::disk('public')
            ->assertMissing(
                'products/1/one.jpg'
            );
    }

    public function test_image_from_another_product_cannot_be_modified(): void
    {
        $admin = $this->createAdmin();

        $firstProduct = $this->getProduct();

        $secondProduct =
            $firstProduct->replicate();

        $secondProduct->slug =
            'producto-secundario';

        $secondProduct->name_es =
            'Producto secundario';

        $secondProduct->save();

        $image = $secondProduct
            ->images()
            ->create([
                'disk' => 'public',

                'path' =>
                    'products/'.
                    $secondProduct->id.
                    '/image.jpg',

                'is_primary' => true,

                'sort_order' => 10,
            ]);

        $response = $this
            ->actingAs($admin)
            ->patch(
                route(
                    'admin.products.images.primary',
                    [
                        $firstProduct,
                        $image,
                    ]
                )
            );

        $response->assertNotFound();
    }

    private function createAdmin(): User
    {
        return User::factory()->create([
            'name' =>
                'Administrador Test',

            'email' =>
                'admin@example.com',

            'password' =>
                'password-seguro',

            'is_admin' => true,
        ]);
    }

    private function getProduct(): Product
    {
        return Product::query()
            ->with('variants')
            ->firstOrFail();
    }

    private function productPayload(
        Product $product
    ): array {
        $product->load('variants');

        $variants = [];

        foreach ($product->variants as $variant) {
            $specifications = collect(
                $variant->specifications ?? []
            )
                ->map(
                    fn ($value, $key) => [
                        'key' => $key,
                        'value' => $value,
                    ]
                )
                ->values()
                ->all();

            $variants[$variant->id] = [
                'id' =>
                    $variant->id,

                'name_es' =>
                    $variant->name_es,

                'name_en' =>
                    $variant->name_en,

                'price' =>
                    $variant->price,

                'currency' =>
                    $variant->currency,

                'manage_stock' =>
                    $variant->manage_stock
                        ? 1
                        : 0,

                'stock_quantity' =>
                    $variant->stock_quantity,

                'stock_status' =>
                    $variant->stock_status,

                'is_active' =>
                    $variant->is_active
                        ? 1
                        : 0,

                'specifications' =>
                    $specifications,
            ];
        }

        return [
            'category_id' =>
                $product->category_id,

            'brand_id' =>
                $product->brand_id,

            'name_es' =>
                $product->name_es,

            'name_en' =>
                $product->name_en,

            'short_description_es' =>
                $product->short_description_es,

            'short_description_en' =>
                $product->short_description_en,

            'description_es' =>
                $product->description_es,

            'description_en' =>
                $product->description_en,

            'status' =>
                $product->status,

            'is_featured' =>
                $product->is_featured
                    ? 1
                    : 0,

            'variants' =>
                $variants,
        ];
    }
}