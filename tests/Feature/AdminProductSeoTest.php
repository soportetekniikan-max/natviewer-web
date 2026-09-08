<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Database\Seeders\BrandSeeder;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProductSeoTest extends TestCase
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

    public function test_admin_can_create_product_with_seo_metadata(): void
    {
        $admin = $this->createAdmin();

        $category = Category::query()
            ->firstOrFail();

        $brand = Brand::query()
            ->firstOrFail();

        $response = $this
            ->actingAs($admin)
            ->post(
                route('admin.products.store'),
                [
                    'category_id' =>
                        $category->id,

                    'brand_id' =>
                        $brand->id,

                    'name_es' =>
                        'Natviewer SEO Test',

                    'name_en' =>
                        'Natviewer SEO Test',

                    'slug' =>
                        'natviewer-seo-test',

                    'short_description_es' =>
                        'Descripción corta SEO.',

                    'short_description_en' =>
                        'SEO short description.',

                    'description_es' =>
                        'Descripción completa SEO.',

                    'description_en' =>
                        'Complete SEO description.',

                    'meta_title_es' =>
                        'Binoculares Natviewer SEO Test',

                    'meta_title_en' =>
                        'Natviewer SEO Test Binoculars',

                    'meta_description_es' =>
                        'Meta descripción en español para el producto de prueba SEO.',

                    'meta_description_en' =>
                        'English meta description for the SEO test product.',

                    'is_featured' =>
                        0,

                    'variants' => [
                        0 => [
                            'sku' =>
                                'NV-SEO-TEST',

                            'name_es' =>
                                'SEO Test 8×42',

                            'name_en' =>
                                'SEO Test 8×42',

                            'price' =>
                                500000,

                            'currency' =>
                                'COP',

                            'manage_stock' =>
                                0,

                            'stock_quantity' =>
                                null,

                            'stock_status' =>
                                ProductVariant::STOCK_UNKNOWN,

                            'is_default' =>
                                1,

                            'is_active' =>
                                1,

                            'sort_order' =>
                                10,

                            'specifications' =>
                                [],
                        ],
                    ],
                ]
            );

        $product = Product::query()
            ->where(
                'slug',
                'natviewer-seo-test'
            )
            ->firstOrFail();

        $response->assertRedirect(
            route(
                'admin.products.edit',
                $product
            )
        );

        $this->assertSame(
            'Binoculares Natviewer SEO Test',
            $product->meta_title_es
        );

        $this->assertSame(
            'Natviewer SEO Test Binoculars',
            $product->meta_title_en
        );

        $this->assertSame(
            'Meta descripción en español para el producto de prueba SEO.',
            $product->meta_description_es
        );

        $this->assertSame(
            'English meta description for the SEO test product.',
            $product->meta_description_en
        );
    }

    public function test_admin_can_update_product_seo_and_slug(): void
    {
        $admin = $this->createAdmin();

        [
            $product,
            $variant,
        ] = $this->createProductWithVariant();

        $response = $this
            ->actingAs($admin)
            ->put(
                route(
                    'admin.products.update',
                    $product
                ),
                $this->updatePayload(
                    $product,
                    $variant,
                    [
                        'slug' =>
                            'Natviewer Falco Premium',

                        'meta_title_es' =>
                            'Natviewer Falco Premium | Binoculares',

                        'meta_title_en' =>
                            'Natviewer Falco Premium | Binoculars',

                        'meta_description_es' =>
                            'Binoculares Natviewer Falco Premium para observación de aves.',

                        'meta_description_en' =>
                            'Natviewer Falco Premium binoculars for birdwatching.',
                    ]
                )
            );

        $response->assertRedirect(
            route(
                'admin.products.edit',
                $product
            )
        );

        $product->refresh();

        $this->assertSame(
            'natviewer-falco-premium',
            $product->slug
        );

        $this->assertSame(
            'Natviewer Falco Premium | Binoculares',
            $product->meta_title_es
        );

        $this->assertSame(
            'Natviewer Falco Premium | Binoculars',
            $product->meta_title_en
        );

        $this->assertSame(
            'Binoculares Natviewer Falco Premium para observación de aves.',
            $product->meta_description_es
        );

        $this->assertSame(
            'Natviewer Falco Premium binoculars for birdwatching.',
            $product->meta_description_en
        );
    }

    public function test_duplicate_product_slug_is_rejected(): void
    {
        $admin = $this->createAdmin();

        [
            $firstProduct,
        ] = $this->createProductWithVariant(
            'producto-existente',
            'Producto existente',
            'NV-SEO-ONE'
        );

        [
            $secondProduct,
            $secondVariant,
        ] = $this->createProductWithVariant(
            'producto-editable',
            'Producto editable',
            'NV-SEO-TWO'
        );

        $response = $this
            ->actingAs($admin)
            ->from(
                route(
                    'admin.products.edit',
                    $secondProduct
                )
            )
            ->put(
                route(
                    'admin.products.update',
                    $secondProduct
                ),
                $this->updatePayload(
                    $secondProduct,
                    $secondVariant,
                    [
                        'slug' =>
                            $firstProduct->slug,
                    ]
                )
            );

        $response
            ->assertRedirect(
                route(
                    'admin.products.edit',
                    $secondProduct
                )
            )
            ->assertSessionHasErrors(
                'slug'
            );

        $this->assertSame(
            'producto-editable',
            $secondProduct
                ->fresh()
                ->slug
        );
    }

    public function test_product_seo_fields_can_be_empty(): void
    {
        $admin = $this->createAdmin();

        [
            $product,
            $variant,
        ] = $this->createProductWithVariant();

        $product->update([
            'meta_title_es' =>
                'Título anterior ES',

            'meta_title_en' =>
                'Previous title EN',

            'meta_description_es' =>
                'Descripción anterior ES',

            'meta_description_en' =>
                'Previous description EN',
        ]);

        $response = $this
            ->actingAs($admin)
            ->put(
                route(
                    'admin.products.update',
                    $product
                ),
                $this->updatePayload(
                    $product,
                    $variant,
                    [
                        'meta_title_es' =>
                            '',

                        'meta_title_en' =>
                            '',

                        'meta_description_es' =>
                            '',

                        'meta_description_en' =>
                            '',
                    ]
                )
            );

        $response->assertRedirect(
            route(
                'admin.products.edit',
                $product
            )
        );

        $product->refresh();

        $this->assertNull(
            $product->meta_title_es
        );

        $this->assertNull(
            $product->meta_title_en
        );

        $this->assertNull(
            $product->meta_description_es
        );

        $this->assertNull(
            $product->meta_description_en
        );
    }

    public function test_seo_values_are_trimmed_before_saving(): void
    {
        $admin = $this->createAdmin();

        [
            $product,
            $variant,
        ] = $this->createProductWithVariant();

        $response = $this
            ->actingAs($admin)
            ->put(
                route(
                    'admin.products.update',
                    $product
                ),
                $this->updatePayload(
                    $product,
                    $variant,
                    [
                        'meta_title_es' =>
                            '  Natviewer Falco SEO  ',

                        'meta_title_en' =>
                            '  Natviewer Falco SEO EN  ',

                        'meta_description_es' =>
                            '  Descripción SEO ES.  ',

                        'meta_description_en' =>
                            '  SEO description EN.  ',
                    ]
                )
            );

        $response->assertRedirect(
            route(
                'admin.products.edit',
                $product
            )
        );

        $product->refresh();

        $this->assertSame(
            'Natviewer Falco SEO',
            $product->meta_title_es
        );

        $this->assertSame(
            'Natviewer Falco SEO EN',
            $product->meta_title_en
        );

        $this->assertSame(
            'Descripción SEO ES.',
            $product->meta_description_es
        );

        $this->assertSame(
            'SEO description EN.',
            $product->meta_description_en
        );
    }

    public function test_edit_page_displays_product_seo_fields(): void
    {
        $admin = $this->createAdmin();

        [
            $product,
        ] = $this->createProductWithVariant();

        $product->update([
            'meta_title_es' =>
                'SEO visible ES',

            'meta_title_en' =>
                'SEO visible EN',

            'meta_description_es' =>
                'Descripción visible ES',

            'meta_description_en' =>
                'Visible description EN',
        ]);

        $response = $this
            ->actingAs($admin)
            ->get(
                route(
                    'admin.products.edit',
                    $product
                )
            );

        $response
            ->assertOk()
            ->assertSee(
                'Meta title ES'
            )
            ->assertSee(
                'Meta description ES'
            )
            ->assertSee(
                'Meta title EN'
            )
            ->assertSee(
                'Meta description EN'
            )
            ->assertSee(
                'SEO visible ES'
            )
            ->assertSee(
                'SEO visible EN'
            );
    }

    private function createProductWithVariant(
        string $slug = 'natviewer-falco',
        string $name = 'Natviewer Falco',
        string $sku = 'NV-FALCO-SEO'
    ): array {
        $category = Category::query()
            ->firstOrFail();

        $brand = Brand::query()
            ->firstOrFail();

        $product = Product::create([
            'category_id' =>
                $category->id,

            'brand_id' =>
                $brand->id,

            'slug' =>
                $slug,

            'name_es' =>
                $name,

            'name_en' =>
                $name,

            'short_description_es' =>
                'Binoculares premium para observación de aves.',

            'short_description_en' =>
                'Premium binoculars for birdwatching.',

            'description_es' =>
                'Descripción completa del producto.',

            'description_en' =>
                'Complete product description.',

            'status' =>
                Product::STATUS_PUBLISHED,

            'is_featured' =>
                true,
        ]);

        $variant =
            new ProductVariant();

        $variant->product_id =
            $product->id;

        $variant->sku =
            $sku;

        $variant->name_es =
            'Falco 8×42 UD';

        $variant->name_en =
            'Falco 8×42 UD';

        $variant->price =
            500000;

        $variant->currency =
            'COP';

        $variant->manage_stock =
            true;

        $variant->stock_quantity =
            5;

        $variant->stock_status =
            ProductVariant::STOCK_IN_STOCK;

        $variant->specifications = [
            'magnification' =>
                '8x',

            'objective_diameter' =>
                '42 mm',

            'glass' =>
                'UD',
        ];

        $variant->is_default =
            true;

        $variant->is_active =
            true;

        $variant->sort_order =
            10;

        $variant->save();

        return [
            $product,
            $variant,
        ];
    }

    private function updatePayload(
        Product $product,
        ProductVariant $variant,
        array $overrides = []
    ): array {
        return array_merge(
            [
                'category_id' =>
                    $product->category_id,

                'brand_id' =>
                    $product->brand_id,

                'slug' =>
                    $product->slug,

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

                'meta_title_es' =>
                    $product->meta_title_es,

                'meta_title_en' =>
                    $product->meta_title_en,

                'meta_description_es' =>
                    $product->meta_description_es,

                'meta_description_en' =>
                    $product->meta_description_en,

                'status' =>
                    $product->status,

                'is_featured' =>
                    $product->is_featured
                        ? 1
                        : 0,

                'variants' => [
                    $variant->id => [
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
                            collect(
                                $variant->specifications
                                ?? []
                            )
                                ->map(
                                    fn ($value, $key) => [
                                        'key' =>
                                            $key,

                                        'value' =>
                                            $value,
                                    ]
                                )
                                ->values()
                                ->all(),
                    ],
                ],
            ],
            $overrides
        );
    }

    private function createAdmin(): User
    {
        return User::factory()->create([
            'name' =>
                'Administrador SEO Test',

            'email' =>
                'admin-seo@example.com',

            'password' =>
                'password-seguro',

            'is_admin' =>
                true,
        ]);
    }
}