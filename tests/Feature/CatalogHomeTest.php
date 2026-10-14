<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductImage;
use Database\Seeders\BrandSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ContactSettingSeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CatalogHomeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            CategorySeeder::class,
            BrandSeeder::class,
            ProductSeeder::class,
            ContactSettingSeeder::class,
        ]);
    }

    public function test_spanish_home_displays_seeded_catalog(): void
    {
        $response = $this->get('/es');

        $response
            ->assertOk()
            ->assertSee(
                'Natviewer Falco 8×42 UD'
            )
            ->assertSee(
                'Natviewer Falco 10×42 UD'
            )
            ->assertSee(
                'Binoculares terrestres'
            )
            ->assertSee(
                $this->spanishProduct8Url(),
                false
            )
            ->assertSee(
                $this->spanishProduct10Url(),
                false
            );
    }

    public function test_english_home_displays_seeded_catalog(): void
    {
        $response = $this->get('/en');

        $response
            ->assertOk()
            ->assertSee(
                'Natviewer Falco 8×42 UD'
            )
            ->assertSee(
                'Natviewer Falco 10×42 UD'
            )
            ->assertSee(
                'Terrestrial binoculars'
            )
            ->assertSee(
                $this->englishProduct8Url(),
                false
            )
            ->assertSee(
                $this->englishProduct10Url(),
                false
            );
    }

    public function test_catalog_has_expected_initial_records(): void
    {
        $this->assertDatabaseCount(
            'categories',
            1
        );

        $this->assertDatabaseCount(
            'brands',
            1
        );

        $this->assertDatabaseCount(
            'products',
            2
        );

        $this->assertDatabaseCount(
            'product_variants',
            2
        );

        $this->assertDatabaseCount(
            'contact_settings',
            1
        );

        $product8Id = DB::table(
            'products'
        )
            ->where(
                'slug',
                'natviewer-falco-8x42-ud'
            )
            ->value('id');

        $product10Id = DB::table(
            'products'
        )
            ->where(
                'slug',
                'natviewer-falco-10x42-ud'
            )
            ->value('id');

        $this->assertNotNull(
            $product8Id
        );

        $this->assertNotNull(
            $product10Id
        );

        $this->assertNotSame(
            $product8Id,
            $product10Id
        );

        $this->assertDatabaseHas(
            'products',
            [
                'id' => $product8Id,

                'slug' => 'natviewer-falco-8x42-ud',

                'status' => Product::STATUS_PUBLISHED,
            ]
        );

        $this->assertDatabaseHas(
            'products',
            [
                'id' => $product10Id,

                'slug' => 'natviewer-falco-10x42-ud',

                'status' => Product::STATUS_PUBLISHED,
            ]
        );

        $this->assertDatabaseHas(
            'product_variants',
            [
                'product_id' => $product8Id,

                'sku' => 'NV-FALCO-8X42-UD',

                'is_default' => true,
            ]
        );

        $this->assertDatabaseHas(
            'product_variants',
            [
                'product_id' => $product10Id,

                'sku' => 'NV-FALCO-10X42-UD',

                'is_default' => true,
            ]
        );
    }

    public function test_home_does_not_expose_internal_price_or_stock_data(): void
    {
        DB::table('product_variants')
            ->where(
                'sku',
                'NV-FALCO-8X42-UD'
            )
            ->update([
                'price' => 1299000,

                'currency' => 'COP',

                'manage_stock' => true,

                'stock_quantity' => 5,

                'stock_status' => 'in_stock',
            ]);

        $response = $this->get('/es');

        $response
            ->assertOk()
            ->assertSee(
                'Natviewer Falco 8×42 UD'
            )
            ->assertSee(
                'Solicitar cotización'
            )
            ->assertDontSee(
                '1299000'
            )
            ->assertDontSee(
                '1.299.000'
            )
            ->assertDontSee(
                '5 unidades disponibles'
            )
            ->assertDontSee(
                'Precio por confirmar'
            )
            ->assertDontSee(
                'Disponibilidad por confirmar'
            );
    }

    public function test_home_excludes_draft_products(): void
    {
        DB::table('products')
            ->where(
                'slug',
                'natviewer-falco-8x42-ud'
            )
            ->update([
                'status' => Product::STATUS_DRAFT,
            ]);

        $response = $this->get('/es');

        $response
            ->assertOk()
            ->assertDontSee(
                $this->spanishProduct8Url(),
                false
            )
            ->assertSee(
                $this->spanishProduct10Url(),
                false
            )
            ->assertSee(
                'Natviewer Falco 10×42 UD'
            );
    }

    public function test_home_excludes_archived_products(): void
    {
        DB::table('products')
            ->where(
                'slug',
                'natviewer-falco-8x42-ud'
            )
            ->update([
                'status' => Product::STATUS_ARCHIVED,
            ]);

        $response = $this->get('/es');

        $response
            ->assertOk()
            ->assertDontSee(
                $this->spanishProduct8Url(),
                false
            )
            ->assertSee(
                $this->spanishProduct10Url(),
                false
            )
            ->assertSee(
                'Natviewer Falco 10×42 UD'
            );
    }

    public function test_home_excludes_product_with_inactive_category(): void
    {
        $categoryId = DB::table(
            'products'
        )
            ->where(
                'slug',
                'natviewer-falco-8x42-ud'
            )
            ->value(
                'category_id'
            );

        DB::table('categories')
            ->where(
                'id',
                $categoryId
            )
            ->update([
                'is_active' => false,
            ]);

        $response = $this->get('/es');

        $response
            ->assertOk()
            ->assertDontSee(
                $this->spanishProduct8Url(),
                false
            )
            ->assertDontSee(
                $this->spanishProduct10Url(),
                false
            )
            ->assertSee(
                'No hay productos disponibles actualmente.'
            );
    }

    public function test_home_excludes_product_with_inactive_brand(): void
    {
        $brandId = DB::table(
            'products'
        )
            ->where(
                'slug',
                'natviewer-falco-8x42-ud'
            )
            ->value(
                'brand_id'
            );

        DB::table('brands')
            ->where(
                'id',
                $brandId
            )
            ->update([
                'is_active' => false,
            ]);

        $response = $this->get('/es');

        $response
            ->assertOk()
            ->assertDontSee(
                $this->spanishProduct8Url(),
                false
            )
            ->assertDontSee(
                $this->spanishProduct10Url(),
                false
            )
            ->assertSee(
                'No hay productos disponibles actualmente.'
            );
    }

    public function test_home_excludes_product_when_its_only_variant_is_inactive(): void
    {
        DB::table('product_variants')
            ->where(
                'sku',
                'NV-FALCO-10X42-UD'
            )
            ->update([
                'is_active' => false,
            ]);

        $response = $this->get('/es');

        $response
            ->assertOk()
            ->assertSee(
                $this->spanishProduct8Url(),
                false
            )
            ->assertDontSee(
                $this->spanishProduct10Url(),
                false
            )
            ->assertSee(
                'Natviewer Falco 8×42 UD'
            )
            ->assertDontSee(
                'Natviewer Falco 10×42 UD'
            );
    }

    public function test_single_variant_products_do_not_show_variant_selector(): void
    {
        $spanishResponse =
            $this->get('/es');

        $spanishResponse
            ->assertOk()
            ->assertDontSeeText(
                'Versiones disponibles'
            );

        $englishResponse =
            $this->get('/en');

        $englishResponse
            ->assertOk()
            ->assertDontSeeText(
                'Available versions'
            );
    }

    public function test_home_renders_exactly_four_benefit_cards(): void
    {
        $response = $this->get('/es');

        $response->assertOk();

        $html =
            $response->getContent();

        $benefitCardCount =
            preg_match_all(
                '/class="[^"]*\bnv-benefit-card\b[^"]*"/',
                $html
            );

        $this->assertSame(
            4,
            $benefitCardCount
        );

        $hasBenefitFive =
            preg_match(
                '/<span\s+class="nv-benefit-number">\s*05\s*<\/span>/',
                $html
            );

        $this->assertSame(
            0,
            $hasBenefitFive
        );
    }

    public function test_hero_carousel_uses_only_featured_product_photos_with_primary_first(): void
    {
        $product = Product::query()->where('slug', 'natviewer-falco-8x42-ud')->firstOrFail();
        ProductImage::create(['product_id' => $product->id, 'disk' => 'public', 'path' => 'products/side.jpg', 'alt_es' => 'Vista lateral', 'alt_en' => 'Side view', 'sort_order' => 1]);
        ProductImage::create(['product_id' => $product->id, 'disk' => 'public', 'path' => 'products/main.jpg', 'is_primary' => true, 'sort_order' => 2]);
        $other = Product::query()->where('slug', 'natviewer-falco-10x42-ud')->firstOrFail();
        ProductImage::create(['product_id' => $other->id, 'disk' => 'public', 'path' => 'products/other.jpg']);

        foreach (['es' => 'Vista lateral', 'en' => 'Side view'] as $locale => $alt) {
            $this->get('/'.$locale)->assertOk()
                ->assertSee('data-hero-carousel=', false)
                ->assertSee('data-carousel-controls hidden', false)
                ->assertViewHas('hero', function (array $hero) use ($alt): bool {
                    $this->assertCount(2, $hero['images']);
                    $this->assertStringEndsWith('/products/main.jpg', $hero['images'][0]['url']);
                    $this->assertStringEndsWith('/products/side.jpg', $hero['images'][1]['url']);
                    $this->assertSame($hero['image_url'], $hero['images'][0]['url']);
                    $this->assertSame($alt, $hero['images'][1]['alt']);

                    return true;
                });
        }
    }

    public function test_hero_with_one_photo_keeps_static_image_without_carousel_controls(): void
    {
        $product = Product::query()->where('slug', 'natviewer-falco-8x42-ud')->firstOrFail();
        ProductImage::create(['product_id' => $product->id, 'disk' => 'public', 'path' => 'products/only.jpg']);

        $this->get('/es')->assertOk()->assertDontSee('data-hero-carousel=', false)
            ->assertDontSee('data-carousel-controls', false)
            ->assertViewHas('hero', fn (array $hero): bool => count($hero['images']) === 1);
    }

    public function test_hero_without_photos_keeps_placeholder_without_carousel_controls(): void
    {
        $this->get('/es')->assertOk()->assertSee('nv-hero-product-placeholder', false)
            ->assertDontSee('data-carousel-controls', false)
            ->assertViewHas('hero', fn (array $hero): bool => $hero['images'] === []);
    }

    private function spanishProduct8Url(): string
    {
        return route(
            'products.show.es',
            [
                'product' => 'natviewer-falco-8x42-ud',
            ]
        );
    }

    private function spanishProduct10Url(): string
    {
        return route(
            'products.show.es',
            [
                'product' => 'natviewer-falco-10x42-ud',
            ]
        );
    }

    private function englishProduct8Url(): string
    {
        return route(
            'products.show.en',
            [
                'product' => 'natviewer-falco-8x42-ud',
            ]
        );
    }

    private function englishProduct10Url(): string
    {
        return route(
            'products.show.en',
            [
                'product' => 'natviewer-falco-10x42-ud',
            ]
        );
    }
}
