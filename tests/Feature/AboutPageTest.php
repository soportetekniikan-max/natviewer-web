<?php

namespace Tests\Feature;

use Tests\TestCase;

class AboutPageTest extends TestCase
{
    public function test_spanish_about_page_is_accessible(): void
    {
        $response = $this->get(
            route('about.es')
        );

        $response
            ->assertOk()
            ->assertViewIs('pages.about')
            ->assertViewHas(
                'locale',
                'es'
            )
            ->assertSeeText(
                'Más cerca de lo que quieres observar.'
            )
            ->assertSeeText(
                'Quiénes somos'
            );
    }

    public function test_english_about_page_is_accessible(): void
    {
        $response = $this->get(
            route('about.en')
        );

        $response
            ->assertOk()
            ->assertViewIs('pages.about')
            ->assertViewHas(
                'locale',
                'en'
            )
            ->assertSeeText(
                'Closer to what you want to observe.'
            )
            ->assertSeeText(
                'About us'
            );
    }

    public function test_spanish_about_page_has_expected_seo_metadata(): void
    {
        $response = $this->get(
            route('about.es')
        );

        $response
            ->assertOk()
            ->assertSee(
                '<title>Quiénes somos | Natviewer</title>',
                false
            )
            ->assertSee(
                'href="' .
                route('about.es') .
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

    public function test_english_about_page_has_expected_seo_metadata(): void
    {
        $response = $this->get(
            route('about.en')
        );

        $response
            ->assertOk()
            ->assertSee(
                '<title>About us | Natviewer</title>',
                false
            )
            ->assertSee(
                'href="' .
                route('about.en') .
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

    public function test_about_pages_use_english_as_x_default(): void
    {
        $response = $this->get(
            route('about.es')
        );

        $response
            ->assertOk()
            ->assertSee(
                'hreflang="x-default"',
                false
            )
            ->assertSee(
                'href="' .
                route('about.en') .
                '"',
                false
            );
    }

    public function test_spanish_about_page_switches_to_english(): void
    {
        $response = $this->get(
            route('about.es')
        );

        $response
            ->assertOk()
            ->assertSee(
                route('about.en'),
                false
            );
    }

    public function test_english_about_page_switches_to_spanish(): void
    {
        $response = $this->get(
            route('about.en')
        );

        $response
            ->assertOk()
            ->assertSee(
                route('about.es'),
                false
            );
    }

    public function test_about_page_renders_all_four_principles(): void
    {
        $spanishResponse = $this->get(
            route('about.es')
        );

        $spanishResponse
            ->assertOk()
            ->assertSeeText('Claridad')
            ->assertSeeText('Funcionalidad')
            ->assertSeeText('Cercanía')
            ->assertSeeText('Naturaleza');

        $englishResponse = $this->get(
            route('about.en')
        );

        $englishResponse
            ->assertOk()
            ->assertSeeText('Clarity')
            ->assertSeeText('Functionality')
            ->assertSeeText('Accessibility')
            ->assertSeeText('Nature');
    }
}