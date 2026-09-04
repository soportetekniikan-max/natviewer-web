<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_root_redirects_to_spanish_home(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/es');
    }

    public function test_spanish_home_returns_successful_response(): void
    {
        $response = $this->get('/es');

        $response->assertStatus(200);
    }

    public function test_english_home_returns_successful_response(): void
    {
        $response = $this->get('/en');

        $response->assertStatus(200);
    }
}