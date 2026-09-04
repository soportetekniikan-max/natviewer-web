<?php

namespace Tests\Feature;

use Database\Seeders\BrandSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ContactSettingSeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogHomeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            CategorySeeder::class,
            BrandSeeder::class,
            ProductSeeder::class,
            ContactSettingSeeder::class,
        ]);
    }

    public function test_spanish_home_displays_seeded_catalog(): void
    {
        $response = $this->get('/es');

        $response
            ->assertStatus(200)
            ->assertSee('Natviewer Falco')
            ->assertSee('Falco 8×42 UD')
            ->assertSee('Falco 10×42 UD')
            ->assertSee('Binoculares terrestres');
    }

    public function test_english_home_displays_seeded_catalog(): void
    {
        $response = $this->get('/en');

        $response
            ->assertStatus(200)
            ->assertSee('Natviewer Falco')
            ->assertSee('Falco 8×42 UD')
            ->assertSee('Falco 10×42 UD')
            ->assertSee('Terrestrial binoculars');
    }

    public function test_catalog_has_expected_initial_records(): void
    {
        $this->assertDatabaseCount('categories', 1);
        $this->assertDatabaseCount('brands', 1);
        $this->assertDatabaseCount('products', 1);
        $this->assertDatabaseCount('product_variants', 2);
        $this->assertDatabaseCount('contact_settings', 1);

        $this->assertDatabaseHas('products', [
            'slug' => 'natviewer-falco',
            'status' => 'published',
        ]);

        $this->assertDatabaseHas('product_variants', [
            'sku' => 'NV-FALCO-8X42-UD',
        ]);

        $this->assertDatabaseHas('product_variants', [
            'sku' => 'NV-FALCO-10X42-UD',
        ]);
    }
}