<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
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

        $variant = $product
            ->variants
            ->first();

        $payload =
            $this->productPayload(
                $product
            );

        $payload[
            'variants'
        ][
            $variant->id
        ][
            'specifications'
        ][] = [
            'key' =>
                'weight',

            'value' =>
                '650 g',
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
            $variant
                ->specifications[
                    'weight'
                ]
        );
    }

    public function test_first_uploaded_image_becomes_primary(): void
    {
        Storage::fake('public');

        $admin = $this->createAdmin();
        $product = $this->getProduct();

        $variant = $product
            ->variants
            ->first();

        $response = $this
            ->actingAs($admin)
            ->post(
                route(
                    'admin.products.images.store',
                    $product
                ),
                [
                    'images' => [
                        UploadedFile::fake()
                            ->image(
                                'falco.jpg',
                                1200,
                                900
                            ),
                    ],

                    'variant_id' =>
                        $variant->id,

                    'alt_es' =>
                        'Binocular Natviewer Falco',

                    'alt_en' =>
                        'Natviewer Falco binocular',

                    'sort_order' =>
                        10,

                    'make_primary' =>
                        0,
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
            ->assertExists(
                $image->path
            );
    }

    public function test_admin_can_upload_multiple_images_at_once(): void
    {
        Storage::fake('public');

        $admin = $this->createAdmin();
        $product = $this->getProduct();

        $variant = $product
            ->variants
            ->first();

        $response = $this
            ->actingAs($admin)
            ->post(
                route(
                    'admin.products.images.store',
                    $product
                ),
                [
                    'images' => [
                        UploadedFile::fake()
                            ->image(
                                'falco-01.jpg',
                                1200,
                                900
                            ),

                        UploadedFile::fake()
                            ->image(
                                'falco-02.jpg',
                                1200,
                                900
                            ),

                        UploadedFile::fake()
                            ->image(
                                'falco-03.jpg',
                                1200,
                                900
                            ),
                    ],

                    'variant_id' =>
                        $variant->id,

                    'alt_es' =>
                        'Natviewer Falco',

                    'alt_en' =>
                        'Natviewer Falco',

                    'sort_order' =>
                        10,

                    'make_primary' =>
                        0,
                ]
            );

        $response->assertRedirect(
            route(
                'admin.products.edit',
                $product
            )
        );

        $images = ProductImage::query()
            ->where(
                'product_id',
                $product->id
            )
            ->orderBy(
                'sort_order'
            )
            ->get();

        $this->assertCount(
            3,
            $images
        );

        $this->assertSame(
            [
                10,
                20,
                30,
            ],
            $images
                ->pluck(
                    'sort_order'
                )
                ->all()
        );

        $this->assertTrue(
            $images[0]->is_primary
        );

        $this->assertFalse(
            $images[1]->is_primary
        );

        $this->assertFalse(
            $images[2]->is_primary
        );

        foreach ($images as $image) {
            $this->assertSame(
                $variant->id,
                $image->variant_id
            );

            Storage::disk('public')
                ->assertExists(
                    $image->path
                );
        }
    }

    public function test_marking_batch_as_primary_only_promotes_first_new_image(): void
    {
        Storage::fake('public');

        $admin = $this->createAdmin();
        $product = $this->getProduct();

        $variant = $product
            ->variants
            ->first();

        $existingImage = $product
            ->images()
            ->create([
                'variant_id' =>
                    $variant->id,

                'disk' =>
                    'public',

                'path' =>
                    'products/'
                    . $product->id
                    . '/existing.jpg',

                'alt_es' =>
                    'Imagen existente',

                'alt_en' =>
                    'Existing image',

                'is_primary' =>
                    true,

                'sort_order' =>
                    10,
            ]);

        Storage::disk('public')
            ->put(
                $existingImage->path,
                'test'
            );

        $response = $this
            ->actingAs($admin)
            ->post(
                route(
                    'admin.products.images.store',
                    $product
                ),
                [
                    'images' => [
                        UploadedFile::fake()
                            ->image(
                                'new-01.jpg',
                                1200,
                                900
                            ),

                        UploadedFile::fake()
                            ->image(
                                'new-02.jpg',
                                1200,
                                900
                            ),
                    ],

                    'variant_id' =>
                        $variant->id,

                    'make_primary' =>
                        1,
                ]
            );

        $response->assertRedirect(
            route(
                'admin.products.edit',
                $product
            )
        );

        $existingImage->refresh();

        $this->assertFalse(
            $existingImage->is_primary
        );

        $newImages = ProductImage::query()
            ->where(
                'product_id',
                $product->id
            )
            ->where(
                'id',
                '!=',
                $existingImage->id
            )
            ->orderBy(
                'sort_order'
            )
            ->get();

        $this->assertCount(
            2,
            $newImages
        );

        $this->assertTrue(
            $newImages[0]->is_primary
        );

        $this->assertFalse(
            $newImages[1]->is_primary
        );

        $this->assertSame(
            1,
            ProductImage::query()
                ->where(
                    'product_id',
                    $product->id
                )
                ->where(
                    'is_primary',
                    true
                )
                ->count()
        );
    }

    public function test_admin_can_update_image_metadata(): void
    {
        Storage::fake('public');

        $admin = $this->createAdmin();
        $product = $this->getProduct();

        $firstVariant = $product
            ->variants()
            ->firstOrFail();

        $secondVariant =
            $this->createAdditionalVariant(
                $product
            );

        $image = $product
            ->images()
            ->create([
                'variant_id' =>
                    $firstVariant->id,

                'disk' =>
                    'public',

                'path' =>
                    'products/'
                    . $product->id
                    . '/test.jpg',

                'alt_es' =>
                    'Imagen anterior',

                'alt_en' =>
                    'Previous image',

                'is_primary' =>
                    true,

                'sort_order' =>
                    10,
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

                    'sort_order' =>
                        30,
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
                'disk' =>
                    'public',

                'path' =>
                    'products/'
                    . $product->id
                    . '/one.jpg',

                'is_primary' =>
                    true,

                'sort_order' =>
                    10,
            ]);

        $secondImage = $product
            ->images()
            ->create([
                'disk' =>
                    'public',

                'path' =>
                    'products/'
                    . $product->id
                    . '/two.jpg',

                'is_primary' =>
                    false,

                'sort_order' =>
                    20,
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

        $firstPath =
            'products/'
            . $product->id
            . '/one.jpg';

        $secondPath =
            'products/'
            . $product->id
            . '/two.jpg';

        Storage::disk('public')
            ->put(
                $firstPath,
                'test'
            );

        Storage::disk('public')
            ->put(
                $secondPath,
                'test'
            );

        $firstImage = $product
            ->images()
            ->create([
                'disk' =>
                    'public',

                'path' =>
                    $firstPath,

                'is_primary' =>
                    true,

                'sort_order' =>
                    10,
            ]);

        $secondImage = $product
            ->images()
            ->create([
                'disk' =>
                    'public',

                'path' =>
                    $secondPath,

                'is_primary' =>
                    false,

                'sort_order' =>
                    20,
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
                'id' =>
                    $firstImage->id,
            ]
        );

        $this->assertTrue(
            $secondImage
                ->fresh()
                ->is_primary
        );

        Storage::disk('public')
            ->assertMissing(
                $firstPath
            );
    }

    public function test_image_from_another_product_cannot_be_modified(): void
    {
        $admin = $this->createAdmin();

        $firstProduct =
            $this->getProduct();

        $secondProduct =
            Product::query()
                ->where(
                    'slug',
                    'natviewer-falco-10x42-ud'
                )
                ->firstOrFail();

        $image = $secondProduct
            ->images()
            ->create([
                'disk' =>
                    'public',

                'path' =>
                    'products/'
                    . $secondProduct->id
                    . '/image.jpg',

                'is_primary' =>
                    true,

                'sort_order' =>
                    10,
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

    private function createAdditionalVariant(
        Product $product
    ): ProductVariant {
        return ProductVariant::create([
            'product_id' =>
                $product->id,

            'sku' =>
                'NV-FALCO-MEDIA-TEST',

            'name_es' =>
                'Variante multimedia de prueba',

            'name_en' =>
                'Media test variant',

            'price' =>
                null,

            'currency' =>
                'COP',

            'manage_stock' =>
                false,

            'stock_quantity' =>
                null,

            'stock_status' =>
                ProductVariant::STOCK_UNKNOWN,

            'specifications' =>
                [],

            'is_default' =>
                false,

            'is_active' =>
                true,

            'sort_order' =>
                20,
        ]);
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

            'is_admin' =>
                true,
        ]);
    }

    private function getProduct(): Product
    {
        return Product::query()
            ->where(
                'slug',
                'natviewer-falco-8x42-ud'
            )
            ->with('variants')
            ->firstOrFail();
    }

    private function productPayload(
        Product $product
    ): array {
        $product->load(
            'variants'
        );

        $variants = [];

        foreach (
            $product->variants
            as $variant
        ) {
            $specifications = collect(
                $variant->specifications
                ?? []
            )
                ->map(
                    fn (
                        $value,
                        $key
                    ) => [
                        'key' =>
                            $key,

                        'value' =>
                            $value,
                    ]
                )
                ->values()
                ->all();

            $variants[
                $variant->id
            ] = [
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