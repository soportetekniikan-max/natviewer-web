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
            'natviewer-falco'
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
            ->post('/es/quotes', $this->validPayload());

        $response
            ->assertRedirect('/es')
            ->assertSessionHas('quote_success');

        $this->assertDatabaseCount('quote_requests', 1);

        $this->assertDatabaseHas('quote_requests', [
            'status' => QuoteRequest::STATUS_NEW,
            'locale' => 'es',
            'product_id' => $this->product->id,
            'product_variant_id' => $this->variant->id,
            'product_name_snapshot' => 'Natviewer Falco',
            'variant_name_snapshot' => 'Falco 8×42 UD',
            'currency' => 'COP',
            'quantity' => 1,
            'customer_name' => 'Cliente Prueba',
            'customer_phone' => '3001234567',
            'customer_email' => 'cliente@example.com',
        ]);

        $quote = QuoteRequest::firstOrFail();

        $this->assertStringStartsWith(
            'NVQ-',
            $quote->reference
        );

        $this->assertNull(
            $quote->whatsapp_opened_at
        );
    }

    public function test_quote_request_stores_utm_data(): void
    {
        $payload = $this->validPayload();

        $payload['utm_source'] = 'google';
        $payload['utm_medium'] = 'cpc';
        $payload['utm_campaign'] = 'falco';

        $this
            ->from('/es')
            ->post('/es/quotes', $payload);

        $quote = QuoteRequest::firstOrFail();

        $this->assertSame([
            'utm_source' => 'google',
            'utm_medium' => 'cpc',
            'utm_campaign' => 'falco',
        ], $quote->utm_data);
    }

    public function test_quote_requires_a_valid_product(): void
    {
        $payload = $this->validPayload();

        $payload['product_id'] = 999999;

        $response = $this
            ->from('/es')
            ->post('/es/quotes', $payload);

        $response
            ->assertRedirect('/es')
            ->assertSessionHasErrors('product_id');

        $this->assertDatabaseCount(
            'quote_requests',
            0
        );
    }

    public function test_quote_requires_a_valid_variant(): void
    {
        $payload = $this->validPayload();

        $payload['product_variant_id'] = 999999;

        $response = $this
            ->from('/es')
            ->post('/es/quotes', $payload);

        $response
            ->assertRedirect('/es')
            ->assertSessionHasErrors('product_variant_id');

        $this->assertDatabaseCount(
            'quote_requests',
            0
        );
    }

    public function test_quote_requires_customer_name(): void
    {
        $payload = $this->validPayload();

        unset($payload['customer_name']);

        $response = $this
            ->from('/es')
            ->post('/es/quotes', $payload);

        $response
            ->assertRedirect('/es')
            ->assertSessionHasErrors('customer_name');

        $this->assertDatabaseCount(
            'quote_requests',
            0
        );
    }

    public function test_quote_requires_customer_phone(): void
    {
        $payload = $this->validPayload();

        unset($payload['customer_phone']);

        $response = $this
            ->from('/es')
            ->post('/es/quotes', $payload);

        $response
            ->assertRedirect('/es')
            ->assertSessionHasErrors('customer_phone');

        $this->assertDatabaseCount(
            'quote_requests',
            0
        );
    }

    public function test_variant_must_belong_to_selected_product(): void
    {
        $category = Category::firstOrFail();
        $brand = Brand::firstOrFail();

        $otherProduct = Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'slug' => 'otro-producto',
            'name_es' => 'Otro producto',
            'name_en' => 'Other product',
            'status' => Product::STATUS_PUBLISHED,
            'is_featured' => false,
        ]);

        $otherVariant = ProductVariant::create([
            'product_id' => $otherProduct->id,
            'sku' => 'NV-OTHER-001',
            'name_es' => 'Otra variante',
            'name_en' => 'Other variant',
            'price' => null,
            'currency' => 'COP',
            'manage_stock' => true,
            'stock_quantity' => null,
            'stock_status' => ProductVariant::STOCK_UNKNOWN,
            'is_default' => true,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $payload = $this->validPayload();

        $payload['product_variant_id'] =
            $otherVariant->id;

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
                'whatsapp_number' => '573001234567',
                'whatsapp_enabled' => true,
            ]);

        $response = $this->post(
            '/es/quotes',
            $this->validPayload()
        );

        $location = $response
            ->headers
            ->get('Location');

        $this->assertNotNull($location);

        $this->assertStringStartsWith(
            'https://wa.me/573001234567?text=',
            $location
        );

        $quote = QuoteRequest::firstOrFail();

        $this->assertNotNull(
            $quote->whatsapp_opened_at
        );
    }

    private function validPayload(): array
    {
        return [
            'product_id' => $this->product->id,
            'product_variant_id' => $this->variant->id,
            'quantity' => 1,
            'customer_name' => 'Cliente Prueba',
            'customer_phone' => '3001234567',
            'customer_email' => 'cliente@example.com',
            'customer_message' => 'Quiero más información.',
        ];
    }
}