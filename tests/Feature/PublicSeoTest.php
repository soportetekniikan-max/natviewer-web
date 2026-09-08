<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
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
        $response = $this->get('/sitemap.xml');

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

        $response = $this->get('/sitemap.xml');

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

    public function test_robots_file_has_expected_rules(): void
    {
        $response = $this->get('/robots.txt');

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
        $response = $this->get('/robots.txt');

        $response
            ->assertOk()
            ->assertSeeText(
                'Sitemap: '.route('sitemap')
            );
    }
}