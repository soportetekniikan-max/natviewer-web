<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PublicNavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_spanish_home_switches_to_english_home(): void
    {
        $response = $this->get('/es');

        $response->assertOk();

        $this->assertLanguageSwitch(
            $response->getContent(),
            route(
                'home',
                ['locale' => 'en']
            ),
            'en'
        );

        $this->assertPublicNavigation(
            $response->getContent(),
            route(
                'home',
                ['locale' => 'es']
            )
        );

        $this->assertUniqueContactAnchor(
            $response->getContent()
        );
    }

    public function test_english_home_switches_to_spanish_home(): void
    {
        $response = $this->get('/en');

        $response->assertOk();

        $this->assertLanguageSwitch(
            $response->getContent(),
            route(
                'home',
                ['locale' => 'es']
            ),
            'es'
        );

        $this->assertPublicNavigation(
            $response->getContent(),
            route(
                'home',
                ['locale' => 'en']
            )
        );

        $this->assertUniqueContactAnchor(
            $response->getContent()
        );
    }

    public function test_spanish_product_keeps_same_product_when_switching_to_english(): void
    {
        $this->createPublishedProduct();

        $response = $this->get(
            '/es/productos/natviewer-falco'
        );

        $response->assertOk();

        $this->assertLanguageSwitch(
            $response->getContent(),
            route(
                'products.show.en',
                [
                    'product' =>
                        'natviewer-falco',
                ]
            ),
            'en'
        );

        $this->assertPublicNavigation(
            $response->getContent(),
            route(
                'home',
                ['locale' => 'es']
            )
        );
    }

    public function test_english_product_keeps_same_product_when_switching_to_spanish(): void
    {
        $this->createPublishedProduct();

        $response = $this->get(
            '/en/products/natviewer-falco'
        );

        $response->assertOk();

        $this->assertLanguageSwitch(
            $response->getContent(),
            route(
                'products.show.es',
                [
                    'product' =>
                        'natviewer-falco',
                ]
            ),
            'es'
        );

        $this->assertPublicNavigation(
            $response->getContent(),
            route(
                'home',
                ['locale' => 'en']
            )
        );
    }

    private function assertLanguageSwitch(
        string $html,
        string $expectedUrl,
        string $expectedLocale
    ): void {
        $matched = preg_match(
            '/<a\b[^>]*class="nv-lang-switch"[^>]*>/i',
            $html,
            $matches
        );

        $this->assertSame(
            1,
            $matched,
            'No se encontró el selector de idioma.'
        );

        $anchor = $matches[0];

        $this->assertStringContainsString(
            'href="' . $expectedUrl . '"',
            $anchor
        );

        $this->assertStringContainsString(
            'hreflang="'
            . $expectedLocale
            . '"',
            $anchor
        );

        $this->assertStringContainsString(
            'lang="'
            . $expectedLocale
            . '"',
            $anchor
        );
    }

    private function assertPublicNavigation(
        string $html,
        string $homeUrl
    ): void {
        $this->assertStringContainsString(
            'href="'
            . $homeUrl
            . '#products"',
            $html
        );

        $this->assertStringContainsString(
            'href="'
            . $homeUrl
            . '#benefits"',
            $html
        );

        $this->assertStringContainsString(
            'href="'
            . $homeUrl
            . '#specs"',
            $html
        );

        $this->assertStringContainsString(
            'href="'
            . $homeUrl
            . '#contact"',
            $html
        );
    }

    private function assertUniqueContactAnchor(
        string $html
    ): void {
        $matches = preg_match_all(
            '/\bid=["\']contact["\']/i',
            $html
        );

        $this->assertSame(
            1,
            $matches,
            'La página Home debe contener exactamente un id="contact".'
        );
    }

    private function createPublishedProduct(): int
    {
        $now = now();

        $categoryId = DB::table(
            'categories'
        )->insertGetId([
            'slug' =>
                'binoculares-terrestres',

            'name_es' =>
                'Binoculares terrestres',

            'name_en' =>
                'Terrestrial binoculars',

            'description_es' =>
                null,

            'description_en' =>
                null,

            'is_active' =>
                true,

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
                'natviewer',

            'name' =>
                'Natviewer',

            'description_es' =>
                null,

            'description_en' =>
                null,

            'logo_path' =>
                null,

            'is_active' =>
                true,

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
                'natviewer-falco',

            'name_es' =>
                'Natviewer Falco',

            'name_en' =>
                'Natviewer Falco',

            'short_description_es' =>
                'Binoculares para observación de naturaleza.',

            'short_description_en' =>
                'Binoculars for nature observation.',

            'description_es' =>
                'Descripción Natviewer Falco.',

            'description_en' =>
                'Natviewer Falco description.',

            'status' =>
                Product::STATUS_PUBLISHED,

            'is_featured' =>
                true,

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