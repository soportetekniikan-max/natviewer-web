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
            )
            ->assertSeeText(
                'Información clara antes de elegir.'
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
            )
            ->assertSeeText(
                'Clear information before you choose.'
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
                '<title>Preguntas frecuentes | Natviewer</title>',
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
                '<title>Frequently asked questions | Natviewer</title>',
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

    public function test_spanish_faq_contains_expected_product_questions(): void
    {
        $response = $this->get(
            route('faq.es')
        );

        $response
            ->assertOk()
            ->assertSeeText(
                '¿Qué modelos de binoculares ofrece Natviewer?'
            )
            ->assertSeeText(
                '¿Cuál es la diferencia principal entre 8×42 y 10×42?'
            )
            ->assertSeeText(
                '¿La solicitud de cotización confirma una compra?'
            )
            ->assertSeeText(
                '¿Los precios se muestran públicamente en el sitio web?'
            );
    }

    public function test_english_faq_contains_expected_product_questions(): void
    {
        $response = $this->get(
            route('faq.en')
        );

        $response
            ->assertOk()
            ->assertSeeText(
                'Which binocular models does Natviewer offer?'
            )
            ->assertSeeText(
                'What is the main difference between 8×42 and 10×42?'
            )
            ->assertSeeText(
                'Does requesting a quote confirm a purchase?'
            )
            ->assertSeeText(
                'Are prices displayed publicly on the website?'
            );
    }

    public function test_faq_renders_all_question_items(): void
    {
        $spanishResponse = $this->get(
            route('faq.es')
        );

        $spanishResponse
            ->assertOk()
            ->assertSeeInOrder([
                'Sobre nuestros binoculares',
                'Proceso comercial',
                'Antes de decidir',
                'Navegación y contacto',
            ]);

        $englishResponse = $this->get(
            route('faq.en')
        );

        $englishResponse
            ->assertOk()
            ->assertSeeInOrder([
                'About our binoculars',
                'Commercial process',
                'Before deciding',
                'Navigation and contact',
            ]);

        $spanishContent = $spanishResponse
            ->getContent();

        $englishContent = $englishResponse
            ->getContent();

        $this->assertSame(
            17,
            substr_count(
                $spanishContent,
                'class="nv-faq-item"'
            )
        );

        $this->assertSame(
            17,
            substr_count(
                $englishContent,
                'class="nv-faq-item"'
            )
        );
    }
}