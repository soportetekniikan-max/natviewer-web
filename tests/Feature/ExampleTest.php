<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_root_redirects_to_spanish_home(): void
    {
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

    public function test_spanish_home_returns_successful_response(): void
    {
        $response = $this->get('/es');

        $response
            ->assertStatus(200)
            ->assertViewIs('home')
            ->assertViewHas(
                'locale',
                'es'
            )
            ->assertViewHas('hero')
            ->assertViewHas('catalogItems')
            ->assertViewHas('contactSettings');
    }

    public function test_english_home_returns_successful_response(): void
    {
        $response = $this->get('/en');

        $response
            ->assertStatus(200)
            ->assertViewIs('home')
            ->assertViewHas(
                'locale',
                'en'
            )
            ->assertViewHas('hero')
            ->assertViewHas('catalogItems')
            ->assertViewHas('contactSettings');
    }

    public function test_invalid_locale_returns_not_found(): void
    {
        $response = $this->get('/fr');

        $response->assertNotFound();
    }
}