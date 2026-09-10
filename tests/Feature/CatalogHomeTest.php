<?php

namespace Tests\Feature;

use App\Models\Product;
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
            ->assertSee('Natviewer Falco')
            ->assertSee('Falco 8×42 UD')
            ->assertSee('Falco 10×42 UD')
            ->assertSee('Binoculares terrestres')
            ->assertSee(
                $this->spanishProductUrl(),
                false
            );
    }

    public function test_english_home_displays_seeded_catalog(): void
    {
        $response = $this->get('/en');

        $response
            ->assertOk()
            ->assertSee('Natviewer Falco')
            ->assertSee('Falco 8×42 UD')
            ->assertSee('Falco 10×42 UD')
            ->assertSee('Terrestrial binoculars')
            ->assertSee(
                $this->englishProductUrl(),
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
            1
        );

        $this->assertDatabaseCount(
            'product_variants',
            2
        );

        $this->assertDatabaseCount(
            'contact_settings',
            1
        );

        $this->assertDatabaseHas(
            'products',
            [
                'slug' =>
                    'natviewer-falco',

                'status' =>
                    Product::STATUS_PUBLISHED,
            ]
        );

        $this->assertDatabaseHas(
            'product_variants',
            [
                'sku' =>
                    'NV-FALCO-8X42-UD',
            ]
        );

        $this->assertDatabaseHas(
            'product_variants',
            [
                'sku' =>
                    'NV-FALCO-10X42-UD',
            ]
        );
    }

    public function test_home_excludes_draft_products(): void
    {
        DB::table('products')
            ->where(
                'slug',
                'natviewer-falco'
            )
            ->update([
                'status' =>
                    Product::STATUS_DRAFT,
            ]);

        $response = $this->get('/es');

        $response
            ->assertOk()
            ->assertDontSee(
                $this->spanishProductUrl(),
                false
            )
            ->assertSee(
                'No hay productos disponibles actualmente.'
            );
    }

    public function test_home_excludes_archived_products(): void
    {
        DB::table('products')
            ->where(
                'slug',
                'natviewer-falco'
            )
            ->update([
                'status' =>
                    Product::STATUS_ARCHIVED,
            ]);

        $response = $this->get('/es');

        $response
            ->assertOk()
            ->assertDontSee(
                $this->spanishProductUrl(),
                false
            )
            ->assertSee(
                'No hay productos disponibles actualmente.'
            );
    }

    public function test_home_excludes_product_with_inactive_category(): void
    {
        $categoryId = DB::table(
            'products'
        )
            ->where(
                'slug',
                'natviewer-falco'
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
                'is_active' =>
                    false,
            ]);

        $response = $this->get('/es');

        $response
            ->assertOk()
            ->assertDontSee(
                $this->spanishProductUrl(),
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
                'natviewer-falco'
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
                'is_active' =>
                    false,
            ]);

        $response = $this->get('/es');

        $response
            ->assertOk()
            ->assertDontSee(
                $this->spanishProductUrl(),
                false
            )
            ->assertSee(
                'No hay productos disponibles actualmente.'
            );
    }

    public function test_home_excludes_inactive_variants(): void
    {
        DB::table('product_variants')
            ->where(
                'sku',
                'NV-FALCO-10X42-UD'
            )
            ->update([
                'is_active' =>
                    false,
            ]);

        $response = $this->get('/es');

        $response
            ->assertOk()
            ->assertSee(
                'Falco 8×42 UD'
            )
            ->assertDontSee(
                'Falco 10×42 UD'
            )
            ->assertSee(
                $this->spanishProductUrl(),
                false
            );
    }

    private function spanishProductUrl(): string
    {
        return route(
            'products.show.es',
            [
                'product' =>
                    'natviewer-falco',
            ]
        );
    }

    private function englishProductUrl(): string
    {
        return route(
            'products.show.en',
            [
                'product' =>
                    'natviewer-falco',
            ]
        );
    }
}