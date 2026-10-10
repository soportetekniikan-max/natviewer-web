<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrivacyPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_spanish_privacy_policy_is_accessible(): void
    {
        $response = $this->get(
            '/es/politica-de-privacidad'
        );

        $response
            ->assertOk()
            ->assertSee(
                'POLÍTICA DE PRIVACIDAD DE NATVIEWER'
            )
            ->assertSee(
                'Fecha de entrada en vigor:'
            )
            ->assertSee(
                '1 de enero de 2026'
            )
            ->assertSee(
                'Última actualización:'
            )
            ->assertSee(
                '3 de mayo de 2026'
            );
    }

    public function test_english_privacy_policy_is_accessible(): void
    {
        $response = $this->get(
            '/en/privacy-policy'
        );

        $response
            ->assertOk()
            ->assertSee(
                'NATVIEWER PRIVACY POLICY'
            )
            ->assertSee(
                'Effective date:'
            )
            ->assertSee(
                'January 1, 2026'
            )
            ->assertSee(
                'Last updated:'
            )
            ->assertSee(
                'May 3, 2026'
            );
    }

    public function test_spanish_privacy_policy_has_expected_canonical_and_alternates(): void
    {
        $spanishUrl = route(
            'privacy-policy.es'
        );

        $englishUrl = route(
            'privacy-policy.en'
        );

        $response = $this->get(
            '/es/politica-de-privacidad'
        );

        $response
            ->assertOk()
            ->assertSee(
                'href="'.$spanishUrl.'"',
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
                'href="'.$englishUrl.'"',
                false
            );
    }

    public function test_english_privacy_policy_has_expected_canonical_and_alternates(): void
    {
        $spanishUrl = route(
            'privacy-policy.es'
        );

        $englishUrl = route(
            'privacy-policy.en'
        );

        $response = $this->get(
            '/en/privacy-policy'
        );

        $response
            ->assertOk()
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
            )
            ->assertSee(
                'href="'.$spanishUrl.'"',
                false
            );
    }

    public function test_spanish_privacy_policy_switches_to_english(): void
    {
        $englishUrl = route(
            'privacy-policy.en'
        );

        $response = $this->get(
            '/es/politica-de-privacidad'
        );

        $response->assertOk();

        $html = $response->getContent();

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
            'href="'.$englishUrl.'"',
            $anchor
        );

        $this->assertStringContainsString(
            'hreflang="en"',
            $anchor
        );

        $this->assertStringContainsString(
            'lang="en"',
            $anchor
        );
    }

    public function test_english_privacy_policy_switches_to_spanish(): void
    {
        $spanishUrl = route(
            'privacy-policy.es'
        );

        $response = $this->get(
            '/en/privacy-policy'
        );

        $response->assertOk();

        $html = $response->getContent();

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
            'href="'.$spanishUrl.'"',
            $anchor
        );

        $this->assertStringContainsString(
            'hreflang="es"',
            $anchor
        );

        $this->assertStringContainsString(
            'lang="es"',
            $anchor
        );
    }

    public function test_footer_uses_localized_privacy_link(): void
    {
        $spanishResponse = $this->get(
            '/es'
        );

        $spanishResponse
            ->assertOk()
            ->assertSee(
                'Política de Privacidad'
            )
            ->assertSee(
                route(
                    'privacy-policy.es'
                ),
                false
            );

        $englishResponse = $this->get(
            '/en'
        );

        $englishResponse
            ->assertOk()
            ->assertSee(
                'Privacy Policy'
            )
            ->assertSee(
                route(
                    'privacy-policy.en'
                ),
                false
            );
    }

    public function test_privacy_policy_renders_all_32_sections_in_both_languages(): void
    {
        $spanishResponse = $this->get(
            '/es/politica-de-privacidad'
        );

        $englishResponse = $this->get(
            '/en/privacy-policy'
        );

        $spanishResponse->assertOk();
        $englishResponse->assertOk();

        $spanishHtml =
            $spanishResponse->getContent();

        $englishHtml =
            $englishResponse->getContent();

        $this->assertSame(
            32,
            preg_match_all(
                '/class="nv-privacy-section"/',
                $spanishHtml
            )
        );

        $this->assertSame(
            32,
            preg_match_all(
                '/class="nv-privacy-section"/',
                $englishHtml
            )
        );
    }
}