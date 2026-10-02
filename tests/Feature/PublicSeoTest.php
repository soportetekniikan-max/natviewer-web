<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PublicSeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_spanish_home_has_expected_seo_metadata(): void
    {
        $response = $this->get('/es');

        $response
            ->assertOk()
            ->assertSee(
                'Natviewer | Binoculares para avistamiento de aves'
            )
            ->assertSee(
                'rel="canonical"',
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
                'hreflang="x-default"',
                false
            )
            ->assertSee(
                'property="og:title"',
                false
            )
            ->assertSee(
                'property="og:description"',
                false
            )
            ->assertSee(
                'name="twitter:card"',
                false
            )
            ->assertSee(
                'application/ld+json',
                false
            )
            ->assertSee(
                '"@type":"Organization"',
                false
            )
            ->assertSee(
                '"@type":"WebSite"',
                false
            );
    }

    public function test_english_home_has_expected_seo_metadata(): void
    {
        $response = $this->get('/en');

        $response
            ->assertOk()
            ->assertSee(
                'Natviewer | Binoculars for Birdwatching'
            )
            ->assertSee(
                'rel="canonical"',
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
                'hreflang="x-default"',
                false
            );
    }

    public function test_spanish_home_has_its_own_canonical(): void
    {
        $spanishUrl = route(
            'home',
            [
                'locale' => 'es',
            ]
        );

        $response = $this->get('/es');

        $response
            ->assertOk()
            ->assertSee(
                'rel="canonical"',
                false
            )
            ->assertSee(
                'href="'.$spanishUrl.'"',
                false
            );
    }

    public function test_english_home_has_its_own_canonical(): void
    {
        $englishUrl = route(
            'home',
            [
                'locale' => 'en',
            ]
        );

        $response = $this->get('/en');

        $response
            ->assertOk()
            ->assertSee(
                'rel="canonical"',
                false
            )
            ->assertSee(
                'href="'.$englishUrl.'"',
                false
            );
    }

    public function test_sitemap_is_valid_xml_response(): void
    {
        $response = $this->get(
            '/sitemap.xml'
        );

        $response->assertOk();

        $this->assertStringContainsString(
            'application/xml',
            (string) $response
                ->headers
                ->get('Content-Type')
        );

        $response
            ->assertSee(
                '<?xml version="1.0" encoding="UTF-8"?>',
                false
            )
            ->assertSee(
                '<urlset',
                false
            );
    }

    public function test_sitemap_contains_localized_home_urls_and_hreflang(): void
    {
        $spanishUrl = route(
            'home',
            [
                'locale' => 'es',
            ]
        );

        $englishUrl = route(
            'home',
            [
                'locale' => 'en',
            ]
        );

        $response = $this->get(
            '/sitemap.xml'
        );

        $response
            ->assertOk()
            ->assertSee(
                $spanishUrl,
                false
            )
            ->assertSee(
                $englishUrl,
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
                'hreflang="x-default"',
                false
            )
            ->assertDontSee(
                '/admin',
                false
            );
    }

    public function test_sitemap_contains_localized_published_product_urls(): void
    {
        $this->createProduct(
            slug: 'natviewer-falco',
            status: Product::STATUS_PUBLISHED
        );

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
            '/sitemap.xml'
        );

        $response
            ->assertOk()
            ->assertSee(
                '<loc>'.$spanishUrl.'</loc>',
                false
            )
            ->assertSee(
                '<loc>'.$englishUrl.'</loc>',
                false
            )
            ->assertSee(
                'href="'.$spanishUrl.'"',
                false
            )
            ->assertSee(
                'href="'.$englishUrl.'"',
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
                'hreflang="x-default"',
                false
            );
    }

    public function test_sitemap_excludes_draft_products(): void
    {
        $this->createProduct(
            slug: 'draft-product',
            status: Product::STATUS_DRAFT
        );

        $response = $this->get(
            '/sitemap.xml'
        );

        $response
            ->assertOk()
            ->assertDontSee(
                route(
                    'products.show.es',
                    [
                        'product' =>
                            'draft-product',
                    ]
                ),
                false
            )
            ->assertDontSee(
                route(
                    'products.show.en',
                    [
                        'product' =>
                            'draft-product',
                    ]
                ),
                false
            );
    }

    public function test_sitemap_excludes_archived_products(): void
    {
        $this->createProduct(
            slug: 'archived-product',
            status: Product::STATUS_ARCHIVED
        );

        $response = $this->get(
            '/sitemap.xml'
        );

        $response
            ->assertOk()
            ->assertDontSee(
                route(
                    'products.show.es',
                    [
                        'product' =>
                            'archived-product',
                    ]
                ),
                false
            )
            ->assertDontSee(
                route(
                    'products.show.en',
                    [
                        'product' =>
                            'archived-product',
                    ]
                ),
                false
            );
    }

    public function test_sitemap_excludes_products_with_inactive_category(): void
    {
        $this->createProduct(
            slug: 'inactive-category-product',
            status: Product::STATUS_PUBLISHED,
            categoryActive: false
        );

        $response = $this->get(
            '/sitemap.xml'
        );

        $response
            ->assertOk()
            ->assertDontSee(
                route(
                    'products.show.es',
                    [
                        'product' =>
                            'inactive-category-product',
                    ]
                ),
                false
            )
            ->assertDontSee(
                route(
                    'products.show.en',
                    [
                        'product' =>
                            'inactive-category-product',
                    ]
                ),
                false
            );
    }

    public function test_sitemap_excludes_products_with_inactive_brand(): void
    {
        $this->createProduct(
            slug: 'inactive-brand-product',
            status: Product::STATUS_PUBLISHED,
            brandActive: false
        );

        $response = $this->get(
            '/sitemap.xml'
        );

        $response
            ->assertOk()
            ->assertDontSee(
                route(
                    'products.show.es',
                    [
                        'product' =>
                            'inactive-brand-product',
                    ]
                ),
                false
            )
            ->assertDontSee(
                route(
                    'products.show.en',
                    [
                        'product' =>
                            'inactive-brand-product',
                    ]
                ),
                false
            );
    }

    public function test_robots_file_has_expected_rules(): void
    {
        $response = $this->get(
            '/robots.txt'
        );

        $response->assertOk();

        $this->assertStringContainsString(
            'text/plain',
            (string) $response
                ->headers
                ->get('Content-Type')
        );

        $response
            ->assertSeeText(
                'User-agent: *'
            )
            ->assertSeeText(
                'Allow: /'
            )
            ->assertSeeText(
                'Disallow: /admin'
            );
    }

    public function test_robots_points_to_sitemap(): void
    {
        $response = $this->get(
            '/robots.txt'
        );

        $response
            ->assertOk()
            ->assertSeeText(
                'Sitemap: '.route('sitemap')
            );
    }

    private function createProduct(
        string $slug,
        string $status,
        bool $categoryActive = true,
        bool $brandActive = true
    ): int {
        $now = now();

        $categoryId = DB::table(
            'categories'
        )->insertGetId([
            'slug' =>
                'category-'.$slug,

            'name_es' =>
                'Categoría '.$slug,

            'name_en' =>
                'Category '.$slug,

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

        $brandId = DB::table(
            'brands'
        )->insertGetId([
            'slug' =>
                'brand-'.$slug,

            'name' =>
                'Brand '.$slug,

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

        return DB::table(
            'products'
        )->insertGetId([
            'category_id' =>
                $categoryId,

            'brand_id' =>
                $brandId,

            'slug' =>
                $slug,

            'name_es' =>
                'Producto '.$slug,

            'name_en' =>
                'Product '.$slug,

            'short_description_es' =>
                'Descripción corta.',

            'short_description_en' =>
                'Short description.',

            'description_es' =>
                'Descripción del producto.',

            'description_en' =>
                'Product description.',

            'status' =>
                $status,

            'is_featured' =>
                false,

            'meta_title_es' =>
                null,

            'meta_title_en' =>
                null,

            'meta_description_es' =>
                null,

            'meta_description_en' =>
                null,

            'created_at' =>
                $now,

            'updated_at' =>
                $now,
        ]);
    }
}