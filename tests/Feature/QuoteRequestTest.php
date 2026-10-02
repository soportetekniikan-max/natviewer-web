<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\ContactSetting;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\QuoteRequest;
use Database\Seeders\BrandSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ContactSettingSeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuoteRequestTest extends TestCase
{
    use RefreshDatabase;

    protected Product $product;

    protected ProductVariant $variant;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            CategorySeeder::class,
            BrandSeeder::class,
            ProductSeeder::class,
            ContactSettingSeeder::class,
        ]);

        $this->product = Product::where(
            'slug',
            'natviewer-falco-8x42-ud'
        )->firstOrFail();

        $this->variant = ProductVariant::where(
            'sku',
            'NV-FALCO-8X42-UD'
        )->firstOrFail();
    }

    public function test_quote_request_is_saved_when_whatsapp_is_disabled(): void
    {
        $response = $this
            ->from('/es')
            ->post(
                '/es/quotes',
                $this->validPayload()
            );

        $response
            ->assertRedirect('/es')
            ->assertSessionHas(
                'quote_success'
            );

        $this->assertDatabaseCount(
            'quote_requests',
            1
        );

        $this->assertDatabaseHas(
            'quote_requests',
            [
                'status' =>
                    QuoteRequest::STATUS_NEW,

                'locale' =>
                    'es',

                'product_id' =>
                    $this->product->id,

                'product_variant_id' =>
                    $this->variant->id,

                'product_name_snapshot' =>
                    'Natviewer Falco 8×42 UD',

                'variant_name_snapshot' =>
                    'Falco 8×42 UD',

                'currency' =>
                    'COP',

                'quantity' =>
                    1,

                'customer_name' =>
                    'Cliente Prueba',

                'customer_phone' =>
                    '3001234567',

                'customer_email' =>
                    'cliente@example.com',
            ]
        );

        $quote = QuoteRequest::firstOrFail();

        $this->assertStringStartsWith(
            'NVQ-',
            $quote->reference
        );

        $this->assertNull(
            $quote->whatsapp_opened_at
        );
    }

    public function test_10x42_quote_uses_its_independent_product(): void
    {
        $product10 = Product::where(
            'slug',
            'natviewer-falco-10x42-ud'
        )->firstOrFail();

        $variant10 = ProductVariant::where(
            'sku',
            'NV-FALCO-10X42-UD'
        )->firstOrFail();

        $payload = $this->validPayload();

        $payload['product_id'] =
            $product10->id;

        $payload['product_variant_id'] =
            $variant10->id;

        $response = $this
            ->from('/es')
            ->post(
                '/es/quotes',
                $payload
            );

        $response
            ->assertRedirect('/es')
            ->assertSessionHas(
                'quote_success'
            );

        $this->assertDatabaseHas(
            'quote_requests',
            [
                'product_id' =>
                    $product10->id,

                'product_variant_id' =>
                    $variant10->id,

                'product_name_snapshot' =>
                    'Natviewer Falco 10×42 UD',

                'variant_name_snapshot' =>
                    'Falco 10×42 UD',
            ]
        );
    }

    public function test_quote_request_stores_utm_data(): void
    {
        $payload =
            $this->validPayload();

        $payload['utm_source'] =
            'google';

        $payload['utm_medium'] =
            'cpc';

        $payload['utm_campaign'] =
            'falco';

        $this
            ->from('/es')
            ->post(
                '/es/quotes',
                $payload
            );

        $quote =
            QuoteRequest::firstOrFail();

        $this->assertSame(
            [
                'utm_source' =>
                    'google',

                'utm_medium' =>
                    'cpc',

                'utm_campaign' =>
                    'falco',
            ],
            $quote->utm_data
        );
    }

    public function test_quote_requires_a_valid_product(): void
    {
        $payload =
            $this->validPayload();

        $payload['product_id'] =
            999999;

        $response = $this
            ->from('/es')
            ->post(
                '/es/quotes',
                $payload
            );

        $response
            ->assertRedirect('/es')
            ->assertSessionHasErrors(
                'product_id'
            );

        $this->assertDatabaseCount(
            'quote_requests',
            0
        );
    }

    public function test_quote_requires_a_valid_variant(): void
    {
        $payload =
            $this->validPayload();

        $payload['product_variant_id'] =
            999999;

        $response = $this
            ->from('/es')
            ->post(
                '/es/quotes',
                $payload
            );

        $response
            ->assertRedirect('/es')
            ->assertSessionHasErrors(
                'product_variant_id'
            );

        $this->assertDatabaseCount(
            'quote_requests',
            0
        );
    }

    public function test_quote_requires_customer_name(): void
    {
        $payload =
            $this->validPayload();

        unset(
            $payload['customer_name']
        );

        $response = $this
            ->from('/es')
            ->post(
                '/es/quotes',
                $payload
            );

        $response
            ->assertRedirect('/es')
            ->assertSessionHasErrors(
                'customer_name'
            );

        $this->assertDatabaseCount(
            'quote_requests',
            0
        );
    }

    public function test_quote_requires_customer_phone(): void
    {
        $payload =
            $this->validPayload();

        unset(
            $payload['customer_phone']
        );

        $response = $this
            ->from('/es')
            ->post(
                '/es/quotes',
                $payload
            );

        $response
            ->assertRedirect('/es')
            ->assertSessionHasErrors(
                'customer_phone'
            );

        $this->assertDatabaseCount(
            'quote_requests',
            0
        );
    }

    public function test_variant_must_belong_to_selected_product(): void
    {
        $product10 = Product::where(
            'slug',
            'natviewer-falco-10x42-ud'
        )->firstOrFail();

        $variant10 = ProductVariant::where(
            'sku',
            'NV-FALCO-10X42-UD'
        )->firstOrFail();

        $this->assertSame(
            $product10->id,
            $variant10->product_id
        );

        $payload =
            $this->validPayload();

        /*
         * Intentamos enviar la variante 10×42
         * utilizando el producto 8×42.
         */
        $payload['product_variant_id'] =
            $variant10->id;

        $response = $this->post(
            '/es/quotes',
            $payload
        );

        $response->assertNotFound();

        $this->assertDatabaseCount(
            'quote_requests',
            0
        );
    }

    public function test_enabled_whatsapp_redirects_after_saving_quote(): void
    {
        ContactSetting::query()
            ->firstOrFail()
            ->update([
                'whatsapp_number' =>
                    '573001234567',

                'whatsapp_enabled' =>
                    true,
            ]);

        $response = $this->post(
            '/es/quotes',
            $this->validPayload()
        );

        $location = $response
            ->headers
            ->get('Location');

        $this->assertNotNull(
            $location
        );

        $this->assertStringStartsWith(
            'https://wa.me/573001234567?text=',
            $location
        );

        $quote =
            QuoteRequest::firstOrFail();

        $this->assertNotNull(
            $quote->whatsapp_opened_at
        );
    }

    private function validPayload(): array
    {
        return [
            'product_id' =>
                $this->product->id,

            'product_variant_id' =>
                $this->variant->id,

            'quantity' =>
                1,

            'customer_name' =>
                'Cliente Prueba',

            'customer_phone' =>
                '3001234567',

            'customer_email' =>
                'cliente@example.com',

            'customer_message' =>
                'Quiero más información.',
        ];
    }
}