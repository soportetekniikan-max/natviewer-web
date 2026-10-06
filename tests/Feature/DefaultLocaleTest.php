<?php

namespace Tests\Feature;

use App\Models\ContactSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DefaultLocaleTest extends TestCase
{
    use RefreshDatabase;

    public function test_root_redirects_to_english_when_no_settings_exist(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(
            route(
                'home',
                [
                    'locale' => 'en',
                ]
            )
        );
    }

    public function test_root_redirects_to_english_when_default_locale_is_english(): void
    {
        $this->createContactSettings(
            'en'
        );

        $response = $this->get('/');

        $response->assertRedirect(
            route(
                'home',
                [
                    'locale' => 'en',
                ]
            )
        );
    }

    public function test_root_redirects_to_spanish_when_default_locale_is_spanish(): void
    {
        $this->createContactSettings(
            'es'
        );

        $response = $this->get('/');

        $response->assertRedirect(
            route(
                'home',
                [
                    'locale' => 'es',
                ]
            )
        );
    }

    private function createContactSettings(
        string $locale
    ): ContactSetting {
        return ContactSetting::query()
            ->create([
                'company_name' =>
                    'Natviewer',

                'whatsapp_number' =>
                    null,

                'whatsapp_enabled' =>
                    false,

                'email' =>
                    null,

                'default_locale' =>
                    $locale,

                'default_currency' =>
                    'COP',

                'quote_message_es' =>
                    'Hola, estoy interesado en cotizar el producto :product :variant.',

                'quote_message_en' =>
                    'Hello, I am interested in requesting a quote for :product :variant.',
            ]);
    }
}