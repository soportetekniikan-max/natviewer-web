<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Database\Seeders\BrandSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProductVariantTest extends TestCase
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

    public function test_admin_can_create_variant(): void
    {
        $admin = $this->createAdmin();
        $product = $this->getProduct();

        $response = $this
            ->actingAs($admin)
            ->post(
                route(
                    'admin.products.variants.store',
                    $product
                ),
                [
                    'sku' => 'NV-FALCO-12X50',

                    'name_es' =>
                        'Falco 12×50 UD',

                    'name_en' =>
                        'Falco 12×50 UD',

                    'price' => 900000,

                    'currency' => 'COP',

                    'manage_stock' => 1,

                    'stock_quantity' => 5,

                    'stock_status' =>
                        ProductVariant::STOCK_IN_STOCK,

                    'is_default' => 0,

                    'is_active' => 1,

                    'sort_order' => 30,

                    'specifications' => [
                        [
                            'key' => 'magnification',
                            'value' => '12x',
                        ],
                        [
                            'key' => 'objective',
                            'value' => '50 mm',
                        ],
                    ],
                ]
            );

        $variant = ProductVariant::query()
            ->where(
                'sku',
                'NV-FALCO-12X50'
            )
            ->firstOrFail();

        $response->assertRedirect(
            route(
                'admin.products.variants.edit',
                [
                    $product,
                    $variant,
                ]
            )
        );

        $this->assertSame(
            $product->id,
            $variant->product_id
        );

        $this->assertSame(
            '12x',
            $variant->specifications[
                'magnification'
            ]
        );

        $this->assertTrue(
            $variant->is_active
        );
    }

    public function test_admin_can_update_variant(): void
    {
        $admin = $this->createAdmin();
        $product = $this->getProduct();

        $variant = $product
            ->variants()
            ->firstOrFail();

        $response = $this
            ->actingAs($admin)
            ->put(
                route(
                    'admin.products.variants.update',
                    [
                        $product,
                        $variant,
                    ]
                ),
                [
                    'sku' =>
                        'NV-FALCO-UPDATED',

                    'name_es' =>
                        'Falco actualizado',

                    'name_en' =>
                        'Updated Falco',

                    'price' => 777000,

                    'currency' => 'COP',

                    'manage_stock' => 1,

                    'stock_quantity' => 8,

                    'stock_status' =>
                        ProductVariant::STOCK_IN_STOCK,

                    'is_default' =>
                        $variant->is_default
                            ? 1
                            : 0,

                    'is_active' => 1,

                    'sort_order' => 15,

                    'specifications' => [
                        [
                            'key' => 'weight',
                            'value' => '650 g',
                        ],
                    ],
                ]
            );

        $response->assertRedirect(
            route(
                'admin.products.variants.edit',
                [
                    $product,
                    $variant,
                ]
            )
        );

        $variant->refresh();

        $this->assertSame(
            'NV-FALCO-UPDATED',
            $variant->sku
        );

        $this->assertSame(
            8,
            $variant->stock_quantity
        );

        $this->assertSame(
            '650 g',
            $variant->specifications['weight']
        );
    }

    public function test_admin_can_change_default_variant(): void
    {
        $admin = $this->createAdmin();
        $product = $this->getProduct();

        $variants = $product
            ->variants()
            ->orderBy('id')
            ->get();

        $first = $variants->first();
        $second = $variants->last();

        $first->update([
            'is_default' => true,
        ]);

        $second->update([
            'is_default' => false,
        ]);

        $response = $this
            ->actingAs($admin)
            ->put(
                route(
                    'admin.products.variants.update',
                    [
                        $product,
                        $second,
                    ]
                ),
                [
                    'sku' => $second->sku,
                    'name_es' => $second->name_es,
                    'name_en' => $second->name_en,
                    'price' => $second->price,
                    'currency' => $second->currency,

                    'manage_stock' =>
                        $second->manage_stock
                            ? 1
                            : 0,

                    'stock_quantity' =>
                        $second->stock_quantity,

                    'stock_status' =>
                        $second->stock_status,

                    'is_default' => 1,
                    'is_active' => 1,
                    'sort_order' => $second->sort_order,

                    'specifications' => [],
                ]
            );

        $response->assertRedirect();

        $this->assertFalse(
            $first->fresh()->is_default
        );

        $this->assertTrue(
            $second->fresh()->is_default
        );
    }

    public function test_disabling_default_variant_promotes_another_active_variant(): void
    {
        $admin = $this->createAdmin();
        $product = $this->getProduct();

        $variants = $product
            ->variants()
            ->orderBy('id')
            ->get();

        $first = $variants->first();
        $second = $variants->last();

        $first->update([
            'is_default' => true,
            'is_active' => true,
            'sort_order' => 10,
        ]);

        $second->update([
            'is_default' => false,
            'is_active' => true,
            'sort_order' => 20,
        ]);

        $response = $this
            ->actingAs($admin)
            ->put(
                route(
                    'admin.products.variants.update',
                    [
                        $product,
                        $first,
                    ]
                ),
                [
                    'sku' => $first->sku,
                    'name_es' => $first->name_es,
                    'name_en' => $first->name_en,
                    'price' => $first->price,
                    'currency' => $first->currency,

                    'manage_stock' =>
                        $first->manage_stock
                            ? 1
                            : 0,

                    'stock_quantity' =>
                        $first->stock_quantity,

                    'stock_status' =>
                        $first->stock_status,

                    'is_default' => 0,
                    'is_active' => 0,
                    'sort_order' => 10,

                    'specifications' => [],
                ]
            );

        $response->assertRedirect();

        $this->assertFalse(
            $first->fresh()->is_active
        );

        $this->assertFalse(
            $first->fresh()->is_default
        );

        $this->assertTrue(
            $second->fresh()->is_default
        );
    }

    public function test_duplicate_sku_is_rejected(): void
    {
        $admin = $this->createAdmin();
        $product = $this->getProduct();

        $existingVariant = $product
            ->variants()
            ->firstOrFail();

        $response = $this
            ->actingAs($admin)
            ->post(
                route(
                    'admin.products.variants.store',
                    $product
                ),
                [
                    'sku' =>
                        $existingVariant->sku,

                    'name_es' =>
                        'Variante duplicada',

                    'price' => null,

                    'currency' => 'COP',

                    'manage_stock' => 0,

                    'stock_quantity' => null,

                    'stock_status' =>
                        ProductVariant::STOCK_UNKNOWN,

                    'is_default' => 0,

                    'is_active' => 1,

                    'sort_order' => 50,

                    'specifications' => [],
                ]
            );

        $response->assertSessionHasErrors(
            'sku'
        );
    }

    public function test_variant_from_another_product_cannot_be_edited(): void
    {
        $admin = $this->createAdmin();
        $product = $this->getProduct();

        $secondProduct = $product->replicate();

        $secondProduct->slug =
            'segundo-producto';

        $secondProduct->name_es =
            'Segundo producto';

        $secondProduct->save();

        $variant = new ProductVariant();

        $variant->product_id =
            $secondProduct->id;

        $variant->sku =
            'SECOND-VARIANT';

        $variant->name_es =
            'Otra variante';

        $variant->currency =
            'COP';

        $variant->stock_status =
            ProductVariant::STOCK_UNKNOWN;

        $variant->manage_stock =
            false;

        $variant->is_default =
            true;

        $variant->is_active =
            true;

        $variant->sort_order =
            10;

        $variant->specifications =
            [];

        $variant->save();

        $response = $this
            ->actingAs($admin)
            ->get(
                route(
                    'admin.products.variants.edit',
                    [
                        $product,
                        $variant,
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
}