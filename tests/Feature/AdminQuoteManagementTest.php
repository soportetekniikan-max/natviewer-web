<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\QuoteRequest;
use App\Models\User;
use Database\Seeders\BrandSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminQuoteManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            CategorySeeder::class,
            BrandSeeder::class,
            ProductSeeder::class,
        ]);
    }

    public function test_admin_can_access_quote_list_and_detail(): void
    {
        $admin = $this->createAdmin();

        $quote = $this->createQuote();

        $this
            ->actingAs($admin)
            ->get(
                route('admin.quotes.index')
            )
            ->assertOk()
            ->assertSee(
                $quote->reference
            );

        $this
            ->actingAs($admin)
            ->get(
                route(
                    'admin.quotes.show',
                    $quote
                )
            )
            ->assertOk()
            ->assertSee(
                $quote->reference
            )
            ->assertSee(
                $quote->customer_name
            );
    }

    public function test_guest_cannot_access_quote_management(): void
    {
        $quote = $this->createQuote();

        $this
            ->get(
                route('admin.quotes.index')
            )
            ->assertRedirect(
                route('admin.login')
            );

        $this
            ->get(
                route(
                    'admin.quotes.show',
                    $quote
                )
            )
            ->assertRedirect(
                route('admin.login')
            );
    }

    public function test_non_admin_user_cannot_access_quote_management(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $quote = $this->createQuote();

        $this
            ->actingAs($user)
            ->get(
                route('admin.quotes.index')
            )
            ->assertForbidden();

        $this
            ->actingAs($user)
            ->get(
                route(
                    'admin.quotes.show',
                    $quote
                )
            )
            ->assertForbidden();
    }

    public function test_admin_can_filter_quotes_by_status(): void
    {
        $admin = $this->createAdmin();

        $newQuote = $this->createQuote([
            'reference' =>
                'NV-QUOTE-NEW-001',

            'status' =>
                QuoteRequest::STATUS_NEW,

            'customer_name' =>
                'Cliente Nueva',
        ]);

        $wonQuote = $this->createQuote([
            'reference' =>
                'NV-QUOTE-WON-001',

            'status' =>
                QuoteRequest::STATUS_WON,

            'customer_name' =>
                'Cliente Ganada',
        ]);

        $response = $this
            ->actingAs($admin)
            ->get(
                route(
                    'admin.quotes.index',
                    [
                        'status' =>
                            QuoteRequest::STATUS_NEW,
                    ]
                )
            );

        $response
            ->assertOk()
            ->assertSee(
                $newQuote->reference
            )
            ->assertDontSee(
                $wonQuote->reference
            );
    }

    public function test_admin_can_search_quote_by_reference(): void
    {
        $admin = $this->createAdmin();

        $expectedQuote = $this->createQuote([
            'reference' =>
                'NV-SEARCH-12345',

            'customer_name' =>
                'Cliente Buscado',
        ]);

        $otherQuote = $this->createQuote([
            'reference' =>
                'NV-OTHER-98765',

            'customer_name' =>
                'Otro Cliente',
        ]);

        $response = $this
            ->actingAs($admin)
            ->get(
                route(
                    'admin.quotes.index',
                    [
                        'q' =>
                            'SEARCH-12345',
                    ]
                )
            );

        $response
            ->assertOk()
            ->assertSee(
                $expectedQuote->reference
            )
            ->assertDontSee(
                $otherQuote->reference
            );
    }

    public function test_admin_can_update_quote_status_and_internal_notes(): void
    {
        $admin = $this->createAdmin();

        $quote = $this->createQuote();

        $response = $this
            ->actingAs($admin)
            ->put(
                route(
                    'admin.quotes.update',
                    $quote
                ),
                [
                    'status' =>
                        QuoteRequest::STATUS_CONTACTED,

                    'admin_notes' =>
                        'Cliente contactado por teléfono. Solicita seguimiento mañana.',
                ]
            );

        $response->assertRedirect(
            route(
                'admin.quotes.show',
                $quote
            )
        );

        $quote->refresh();

        $this->assertSame(
            QuoteRequest::STATUS_CONTACTED,
            $quote->status
        );

        $this->assertSame(
            'Cliente contactado por teléfono. Solicita seguimiento mañana.',
            $quote->admin_notes
        );
    }

    public function test_invalid_quote_status_is_rejected(): void
    {
        $admin = $this->createAdmin();

        $quote = $this->createQuote();

        $response = $this
            ->actingAs($admin)
            ->from(
                route(
                    'admin.quotes.show',
                    $quote
                )
            )
            ->put(
                route(
                    'admin.quotes.update',
                    $quote
                ),
                [
                    'status' =>
                        'invalid-status',

                    'admin_notes' =>
                        'Esta actualización no debe guardarse.',
                ]
            );

        $response
            ->assertRedirect(
                route(
                    'admin.quotes.show',
                    $quote
                )
            )
            ->assertSessionHasErrors(
                'status'
            );

        $quote->refresh();

        $this->assertSame(
            QuoteRequest::STATUS_NEW,
            $quote->status
        );
    }

    private function createAdmin(): User
    {
        return User::factory()->create([
            'name' =>
                'Administrador Test',

            'email' =>
                'admin@example.com',

            'password' =>
                'password-seguro',

            'is_admin' =>
                true,
        ]);
    }

    private function createQuote(
        array $overrides = []
    ): QuoteRequest {
        $product = Product::query()
            ->firstOrFail();

        $variant = ProductVariant::query()
            ->where(
                'product_id',
                $product->id
            )
            ->firstOrFail();

        $quote = new QuoteRequest();

        $quote->reference =
            $overrides['reference']
            ?? 'NV-QUOTE-TEST-001';

        $quote->status =
            $overrides['status']
            ?? QuoteRequest::STATUS_NEW;

        $quote->locale =
            $overrides['locale']
            ?? 'es';

        $quote->product_id =
            $product->id;

        $quote->product_variant_id =
            $variant->id;

        $quote->product_name_snapshot =
            $product->name_es;

        $quote->variant_name_snapshot =
            $variant->name_es;

        $quote->price_snapshot =
            $variant->price;

        $quote->currency =
            $variant->currency
            ?? 'COP';

        $quote->quantity =
            $overrides['quantity']
            ?? 1;

        $quote->customer_name =
            $overrides['customer_name']
            ?? 'Cliente Prueba';

        $quote->customer_phone =
            $overrides['customer_phone']
            ?? '+57 300 000 0000';

        $quote->customer_email =
            $overrides['customer_email']
            ?? 'cliente@example.com';

        $quote->customer_message =
            $overrides['customer_message']
            ?? 'Solicito información adicional sobre este producto.';

        $quote->source_url =
            'http://localhost/es';

        $quote->utm_data = [
            'utm_source' =>
                'automated-test',

            'utm_medium' =>
                'test',
        ];

        $quote->whatsapp_opened_at =
            null;

        $quote->admin_notes =
            $overrides['admin_notes']
            ?? null;

        $quote->save();

        return $quote;
    }
}