<?php

namespace Tests\Feature;

use Tests\TestCase;

class FaqPageTest extends TestCase
{
    public function test_spanish_faq_page_is_accessible(): void
    {
        $response = $this->get(
            route('faq.es')
        );

        $response
            ->assertOk()
            ->assertViewIs('pages.faq')
            ->assertViewHas(
                'locale',
                'es'
            )
            ->assertSeeText(
                'Preguntas frecuentes'
            );
    }

    public function test_english_faq_page_is_accessible(): void
    {
        $response = $this->get(
            route('faq.en')
        );

        $response
            ->assertOk()
            ->assertViewIs('pages.faq')
            ->assertViewHas(
                'locale',
                'en'
            )
            ->assertSeeText(
                'Frequently asked questions'
            );
    }

    public function test_spanish_faq_page_has_expected_seo_metadata(): void
    {
        $response = $this->get(
            route('faq.es')
        );

        $response
            ->assertOk()
            ->assertSee(
                '<title>',
                false
            )
            ->assertSee(
                'Natviewer',
                false
            )
            ->assertSee(
                'href="' .
                route('faq.es') .
                '"',
                false
            )
            ->assertSee(
                'hreflang="es"',
                false
            )
            ->assertSee(
                'hreflang="en"',
                false
            );
    }

    public function test_english_faq_page_has_expected_seo_metadata(): void
    {
        $response = $this->get(
            route('faq.en')
        );

        $response
            ->assertOk()
            ->assertSee(
                '<title>',
                false
            )
            ->assertSee(
                'Natviewer',
                false
            )
            ->assertSee(
                'href="' .
                route('faq.en') .
                '"',
                false
            )
            ->assertSee(
                'hreflang="es"',
                false
            )
            ->assertSee(
                'hreflang="en"',
                false
            );
    }

    public function test_faq_pages_use_english_as_x_default(): void
    {
        $response = $this->get(
            route('faq.es')
        );

        $response
            ->assertOk()
            ->assertSee(
                'hreflang="x-default"',
                false
            )
            ->assertSee(
                'href="' .
                route('faq.en') .
                '"',
                false
            );
    }

    public function test_spanish_faq_page_switches_to_english(): void
    {
        $response = $this->get(
            route('faq.es')
        );

        $response
            ->assertOk()
            ->assertSee(
                route('faq.en'),
                false
            );
    }

    public function test_english_faq_page_switches_to_spanish(): void
    {
        $response = $this->get(
            route('faq.en')
        );

        $response
            ->assertOk()
            ->assertSee(
                route('faq.es'),
                false
            );
    }

    public function test_faq_renders_all_twenty_four_question_items(): void
    {
        $spanishResponse = $this->get(
            route('faq.es')
        );

        $englishResponse = $this->get(
            route('faq.en')
        );

        $spanishResponse->assertOk();
        $englishResponse->assertOk();

        $spanishContent = $spanishResponse
            ->getContent();

        $englishContent = $englishResponse
            ->getContent();

        $this->assertSame(
            24,
            substr_count(
                $spanishContent,
                'class="nv-faq-item"'
            ),
            'The Spanish FAQ must render all 24 official questions.'
        );

        $this->assertSame(
            24,
            substr_count(
                $englishContent,
                'class="nv-faq-item"'
            ),
            'The English FAQ must render all 24 official questions.'
        );
    }
}