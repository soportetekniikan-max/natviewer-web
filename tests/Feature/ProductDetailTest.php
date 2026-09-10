<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProductDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_spanish_product_detail_is_publicly_accessible(): void
    {
        $data = $this->createCatalog();

        $response = $this->get(
            '/es/productos/natviewer-falco'
        );

        $response
            ->assertOk()
            ->assertViewIs('products.show')
            ->assertViewHas('locale', 'es')
            ->assertSee('Natviewer Falco')
            ->assertSee('Descripción corta en español')
            ->assertSee('Falco 8×42 UD')
            ->assertSee('FALCO-842');
    }

    public function test_english_product_detail_uses_english_content(): void
    {
        $this->createCatalog();

        $response = $this->get(
            '/en/products/natviewer-falco'
        );

        $response
            ->assertOk()
            ->assertViewIs('products.show')
            ->assertViewHas('locale', 'en')
            ->assertSee('Natviewer Falco EN')
            ->assertSee('Short English description')
            ->assertSee('Falco 8×42 UD EN')
            ->assertSee('FALCO-842');
    }

    public function test_product_detail_has_localized_seo_metadata(): void
    {
        $this->createCatalog();

        $spanishUrl = route(
            'products.show.es',
            [
                'product' =>
                    'natviewer-falco',
            ]
        );

        $englishUrl = route(
            'products.show.en',
            [
                'product' =>
                    'natviewer-falco',
            ]
        );

        $response = $this->get(
            '/es/productos/natviewer-falco'
        );

        $response
            ->assertOk()
            ->assertSee(
                'Falco SEO ES',
                false
            )
            ->assertSee(
                'Descripción SEO ES',
                false
            )
            ->assertSee(
                'rel="canonical"',
                false
            )
            ->assertSee(
                $spanishUrl,
                false
            )
            ->assertSee(
                'hreflang="es"',
                false
            )
            ->assertSee(
                'hreflang="en"',
                false
            )
            ->assertSee(
                $englishUrl,
                false
            );
    }

    public function test_english_product_detail_has_english_seo_metadata(): void
    {
        $this->createCatalog();

        $response = $this->get(
            '/en/products/natviewer-falco'
        );

        $response
            ->assertOk()
            ->assertSee(
                'Falco SEO EN',
                false
            )
            ->assertSee(
                'English SEO description',
                false
            );
    }

    public function test_draft_and_archived_products_are_not_public(): void
    {
        foreach (
            [
                Product::STATUS_DRAFT,
                Product::STATUS_ARCHIVED,
            ]
            as $status
        ) {
            $data = $this->createCatalog(
                productStatus: $status,
                suffix: $status
            );

            $response = $this->get(
                '/es/productos/'
                . $data['slug']
            );

            $response->assertNotFound();
        }
    }

    public function test_product_with_inactive_category_is_not_public(): void
    {
        $data = $this->createCatalog(
            categoryActive: false,
            suffix: 'inactive-category'
        );

        $this->get(
            '/es/productos/'
            . $data['slug']
        )->assertNotFound();
    }

    public function test_product_with_inactive_brand_is_not_public(): void
    {
        $data = $this->createCatalog(
            brandActive: false,
            suffix: 'inactive-brand'
        );

        $this->get(
            '/es/productos/'
            . $data['slug']
        )->assertNotFound();
    }

    public function test_inactive_variants_are_not_exposed(): void
    {
        $data = $this->createCatalog();

        $this->createVariant(
            productId: $data['product_id'],
            sku: 'FALCO-HIDDEN',
            nameEs: 'Variante oculta',
            nameEn: 'Hidden variant',
            isActive: false,
            isDefault: false,
            sortOrder: 99
        );

        $response = $this->get(
            '/es/productos/natviewer-falco'
        );

        $response
            ->assertOk()
            ->assertSee('FALCO-842')
            ->assertDontSee('FALCO-HIDDEN')
            ->assertDontSee('Variante oculta');
    }

    public function test_requested_active_variant_can_be_preselected(): void
    {
        $data = $this->createCatalog();

        $secondVariantId =
            $this->createVariant(
                productId:
                    $data['product_id'],

                sku:
                    'FALCO-1042',

                nameEs:
                    'Falco 10×42 UD',

                nameEn:
                    'Falco 10×42 UD EN',

                isActive:
                    true,

                isDefault:
                    false,

                sortOrder:
                    2
            );

        $response = $this->get(
            '/es/productos/natviewer-falco'
            . '?variant='
            . $secondVariantId
        );

        $response
            ->assertOk()
            ->assertViewHas(
                'selectedVariantId',
                $secondVariantId
            )
            ->assertSee(
                'Falco 10×42 UD'
            );
    }

    public function test_product_json_ld_contains_product_and_offer_data(): void
    {
        $this->createCatalog();

        $response = $this->get(
            '/es/productos/natviewer-falco'
        );

        $response
            ->assertOk()
            ->assertSee(
                '"@type":"Product"',
                false
            )
            ->assertSee(
                '"name":"Natviewer Falco"',
                false
            )
            ->assertSee(
                '"@type":"Offer"',
                false
            )
            ->assertSee(
                '"sku":"FALCO-842"',
                false
            )
            ->assertSee(
                '"priceCurrency":"COP"',
                false
            )
            ->assertSee(
                'https://schema.org/InStock',
                false
            );
    }

    public function test_variant_without_price_does_not_create_offer(): void
    {
        $data = $this->createCatalog(
            variantPrice: null
        );

        $response = $this->get(
            '/es/productos/'
            . $data['slug']
        );

        $response
            ->assertOk()
            ->assertSee('FALCO-842')
            ->assertDontSee(
                '"@type":"Offer"',
                false
            );
    }

    private function createCatalog(
        string $productStatus = Product::STATUS_PUBLISHED,
        bool $categoryActive = true,
        bool $brandActive = true,
        ?float $variantPrice = 1299000,
        string $suffix = ''
    ): array {
        $suffixValue =
            $suffix !== ''
                ? '-' . $suffix
                : '';

        $now = now();

        $categoryId =
            DB::table('categories')
                ->insertGetId([
                    'slug' =>
                        'binoculares'
                        . $suffixValue,

                    'name_es' =>
                        'Binoculares terrestres',

                    'name_en' =>
                        'Terrestrial binoculars',

                    'description_es' =>
                        null,

                    'description_en' =>
                        null,

                    'is_active' =>
                        $categoryActive,

                    'sort_order' =>
                        1,

                    'created_at' =>
                        $now,

                    'updated_at' =>
                        $now,
                ]);

        $brandId =
            DB::table('brands')
                ->insertGetId([
                    'slug' =>
                        'natviewer'
                        . $suffixValue,

                    'name' =>
                        'Natviewer',

                    'description_es' =>
                        null,

                    'description_en' =>
                        null,

                    'logo_path' =>
                        null,

                    'is_active' =>
                        $brandActive,

                    'sort_order' =>
                        1,

                    'created_at' =>
                        $now,

                    'updated_at' =>
                        $now,
                ]);

        $slug =
            'natviewer-falco'
            . $suffixValue;

        $productId =
            DB::table('products')
                ->insertGetId([
                    'category_id' =>
                        $categoryId,

                    'brand_id' =>
                        $brandId,

                    'slug' =>
                        $slug,

                    'name_es' =>
                        'Natviewer Falco',

                    'name_en' =>
                        'Natviewer Falco EN',

                    'short_description_es' =>
                        'Descripción corta en español',

                    'short_description_en' =>
                        'Short English description',

                    'description_es' =>
                        'Descripción completa en español.',

                    'description_en' =>
                        'Full English description.',

                    'status' =>
                        $productStatus,

                    'is_featured' =>
                        true,

                    'meta_title_es' =>
                        'Falco SEO ES',

                    'meta_title_en' =>
                        'Falco SEO EN',

                    'meta_description_es' =>
                        'Descripción SEO ES',

                    'meta_description_en' =>
                        'English SEO description',

                    'created_at' =>
                        $now,

                    'updated_at' =>
                        $now,
                ]);

        $variantId =
            $this->createVariant(
                productId:
                    $productId,

                sku:
                    'FALCO-842'
                    . strtoupper(
                        $suffixValue
                    ),

                nameEs:
                    'Falco 8×42 UD',

                nameEn:
                    'Falco 8×42 UD EN',

                price:
                    $variantPrice,

                isActive:
                    true,

                isDefault:
                    true,

                sortOrder:
                    1
            );

        return [
            'category_id' =>
                $categoryId,

            'brand_id' =>
                $brandId,

            'product_id' =>
                $productId,

            'variant_id' =>
                $variantId,

            'slug' =>
                $slug,
        ];
    }

    private function createVariant(
        int $productId,
        string $sku,
        string $nameEs,
        string $nameEn,
        ?float $price = 1299000,
        bool $isActive = true,
        bool $isDefault = false,
        int $sortOrder = 1
    ): int {
        $now = now();

        return DB::table(
            'product_variants'
        )->insertGetId([
            'product_id' =>
                $productId,

            'sku' =>
                $sku,

            'name_es' =>
                $nameEs,

            'name_en' =>
                $nameEn,

            'price' =>
                $price,

            'currency' =>
                'COP',

            'manage_stock' =>
                true,

            'stock_quantity' =>
                5,

            'stock_status' =>
                'in_stock',

            'specifications' =>
                json_encode([
                    'magnification' =>
                        '8x',

                    'objective_diameter' =>
                        '42 mm',

                    'glass' =>
                        'UD',
                ]),

            'is_default' =>
                $isDefault,

            'is_active' =>
                $isActive,

            'sort_order' =>
                $sortOrder,

            'created_at' =>
                $now,

            'updated_at' =>
                $now,
        ]);
    }
}