<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Database\Seeders\BrandSeeder;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminProductCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            CategorySeeder::class,
            BrandSeeder::class,
        ]);
    }

    public function test_admin_can_create_complete_product(): void
    {
        Storage::fake('public');

        $admin = $this->createAdmin();

        $categoryId = \App\Models\Category::query()
            ->firstOrFail()
            ->id;

        $brandId = \App\Models\Brand::query()
            ->firstOrFail()
            ->id;

        $response = $this
            ->actingAs($admin)
            ->post(
                route('admin.products.store'),
                [
                    'category_id' => $categoryId,
                    'brand_id' => $brandId,

                    'name_es' => 'Natviewer Test',
                    'name_en' => 'Natviewer Test',

                    'slug' => '',

                    'short_description_es' =>
                        'Producto de prueba',

                    'short_description_en' =>
                        'Test product',

                    'description_es' =>
                        'Descripción completa de prueba.',

                    'description_en' =>
                        'Complete test description.',

                    'is_featured' => 1,

                    'variants' => [
                        0 => [
                            'sku' =>
                                'NV-TEST-8X42',

                            'name_es' =>
                                'Test 8×42',

                            'name_en' =>
                                'Test 8×42',

                            'price' =>
                                500000,

                            'currency' =>
                                'COP',

                            'manage_stock' =>
                                1,

                            'stock_quantity' =>
                                5,

                            'stock_status' =>
                                'in_stock',

                            'is_default' =>
                                1,

                            'is_active' =>
                                1,

                            'sort_order' =>
                                10,

                            'specifications' => [
                                [
                                    'key' =>
                                        'magnification',

                                    'value' =>
                                        '8x',
                                ],

                                [
                                    'key' =>
                                        'objective',

                                    'value' =>
                                        '42 mm',
                                ],

                                [
                                    'key' =>
                                        'glass',

                                    'value' =>
                                        'UD',
                                ],
                            ],
                        ],

                        1 => [
                            'sku' =>
                                'NV-TEST-10X42',

                            'name_es' =>
                                'Test 10×42',

                            'name_en' =>
                                'Test 10×42',

                            'price' =>
                                550000,

                            'currency' =>
                                'COP',

                            'manage_stock' =>
                                1,

                            'stock_quantity' =>
                                3,

                            'stock_status' =>
                                'in_stock',

                            'is_default' =>
                                0,

                            'is_active' =>
                                1,

                            'sort_order' =>
                                20,

                            'specifications' => [
                                [
                                    'key' =>
                                        'magnification',

                                    'value' =>
                                        '10x',
                                ],

                                [
                                    'key' =>
                                        'objective',

                                    'value' =>
                                        '42 mm',
                                ],

                                [
                                    'key' =>
                                        'glass',

                                    'value' =>
                                        'UD',
                                ],
                            ],
                        ],
                    ],

                    'images' => [
                        0 => [
                            'file' =>
                                UploadedFile::fake()
                                    ->image(
                                        'falco-front.jpg',
                                        1200,
                                        900
                                    ),

                            'alt_es' =>
                                'Natviewer Test 8x42',

                            'alt_en' =>
                                'Natviewer Test 8x42',

                            'variant_key' =>
                                '0',

                            'sort_order' =>
                                10,

                            'is_primary' =>
                                1,
                        ],

                        1 => [
                            'file' =>
                                UploadedFile::fake()
                                    ->image(
                                        'falco-side.jpg',
                                        1200,
                                        900
                                    ),

                            'alt_es' =>
                                'Natviewer Test 10x42',

                            'alt_en' =>
                                'Natviewer Test 10x42',

                            'variant_key' =>
                                '1',

                            'sort_order' =>
                                20,

                            'is_primary' =>
                                0,
                        ],
                    ],
                ]
            );

        $product = Product::query()
            ->where(
                'name_es',
                'Natviewer Test'
            )
            ->firstOrFail();

        $response->assertRedirect(
            route(
                'admin.products.edit',
                $product
            )
        );

        $this->assertSame(
            'natviewer-test',
            $product->slug
        );

        $this->assertSame(
            Product::STATUS_DRAFT,
            $product->status
        );

        $this->assertTrue(
            $product->is_featured
        );

        $this->assertCount(
            2,
            $product->variants
        );

        $this->assertCount(
            2,
            $product->images
        );

        $defaultVariant = $product
            ->variants()
            ->where(
                'is_default',
                true
            )
            ->firstOrFail();

        $this->assertSame(
            'NV-TEST-8X42',
            $defaultVariant->sku
        );

        $this->assertSame(
            '8x',
            $defaultVariant
                ->specifications[
                    'magnification'
                ]
        );

        $primaryImage = $product
            ->images()
            ->where(
                'is_primary',
                true
            )
            ->firstOrFail();

        $this->assertSame(
            $defaultVariant->id,
            $primaryImage->variant_id
        );

        Storage::disk('public')
            ->assertExists(
                $primaryImage->path
            );
    }

    public function test_admin_can_archive_product_without_deleting_it(): void
    {
        $admin = $this->createAdmin();

        $category = \App\Models\Category::query()
            ->firstOrFail();

        $brand = \App\Models\Brand::query()
            ->firstOrFail();

        $product = new Product();

        $product->category_id =
            $category->id;

        $product->brand_id =
            $brand->id;

        $product->slug =
            'producto-archivable';

        $product->name_es =
            'Producto archivable';

        $product->status =
            Product::STATUS_PUBLISHED;

        $product->is_featured =
            true;

        $product->save();

        $response = $this
            ->actingAs($admin)
            ->patch(
                route(
                    'admin.products.archive',
                    $product
                )
            );

        $response->assertRedirect(
            route(
                'admin.products.index'
            )
        );

        $product->refresh();

        $this->assertSame(
            Product::STATUS_ARCHIVED,
            $product->status
        );

        $this->assertFalse(
            $product->is_featured
        );

        $this->assertDatabaseHas(
            'products',
            [
                'id' => $product->id,
            ]
        );
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
}