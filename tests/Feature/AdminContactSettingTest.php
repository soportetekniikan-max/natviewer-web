<?php

namespace Tests\Feature;

use App\Models\ContactSetting;
use App\Models\User;
use Database\Seeders\ContactSettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminContactSettingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(
            ContactSettingSeeder::class
        );
    }

    public function test_admin_can_access_contact_settings(): void
    {
        $admin = $this->createAdmin();

        $response = $this
            ->actingAs($admin)
            ->get(
                route(
                    'admin.contact-settings.edit'
                )
            );

        $response
            ->assertOk()
            ->assertSee('Contacto')
            ->assertSee('WhatsApp');
    }

    public function test_guest_cannot_access_contact_settings(): void
    {
        $this
            ->get(
                route(
                    'admin.contact-settings.edit'
                )
            )
            ->assertRedirect(
                route('admin.login')
            );
    }

    public function test_non_admin_cannot_access_contact_settings(): void
    {
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $this
            ->actingAs($user)
            ->get(
                route(
                    'admin.contact-settings.edit'
                )
            )
            ->assertForbidden();
    }

    public function test_admin_can_update_contact_settings(): void
    {
        $admin = $this->createAdmin();

        $response = $this
            ->actingAs($admin)
            ->put(
                route(
                    'admin.contact-settings.update'
                ),
                [
                    'company_name' =>
                        'Natviewer Colombia',

                    'whatsapp_number' =>
                        '+57 300 123 4567',

                    'whatsapp_enabled' =>
                        1,

                    'email' =>
                        'VENTAS@NATVIEWER.COM',

                    'default_locale' =>
                        'es',

                    'default_currency' =>
                        'cop',

                    'quote_message_es' =>
                        'Hola, quiero información sobre :product :variant.',

                    'quote_message_en' =>
                        'Hello, I would like information about :product :variant.',
                ]
            );

        $response->assertRedirect(
            route(
                'admin.contact-settings.edit'
            )
        );

        $settings = ContactSetting::query()
            ->firstOrFail();

        $this->assertSame(
            'Natviewer Colombia',
            $settings->company_name
        );

        $this->assertSame(
            '+57 300 123 4567',
            $settings->whatsapp_number
        );

        $this->assertTrue(
            $settings->whatsapp_enabled
        );

        $this->assertSame(
            'ventas@natviewer.com',
            $settings->email
        );

        $this->assertSame(
            'es',
            $settings->default_locale
        );

        $this->assertSame(
            'COP',
            $settings->default_currency
        );
    }

    public function test_enabled_whatsapp_requires_number(): void
    {
        $admin = $this->createAdmin();

        $settings = ContactSetting::query()
            ->firstOrFail();

        $response = $this
            ->actingAs($admin)
            ->from(
                route(
                    'admin.contact-settings.edit'
                )
            )
            ->put(
                route(
                    'admin.contact-settings.update'
                ),
                [
                    'company_name' =>
                        'Natviewer',

                    'whatsapp_number' =>
                        '',

                    'whatsapp_enabled' =>
                        1,

                    'email' =>
                        'ventas@natviewer.com',

                    'default_locale' =>
                        'es',

                    'default_currency' =>
                        'COP',

                    'quote_message_es' =>
                        $settings->quote_message_es,

                    'quote_message_en' =>
                        $settings->quote_message_en,
                ]
            );

        $response
            ->assertRedirect(
                route(
                    'admin.contact-settings.edit'
                )
            )
            ->assertSessionHasErrors(
                'whatsapp_number'
            );
    }

    public function test_disabled_whatsapp_allows_empty_number(): void
    {
        $admin = $this->createAdmin();

        $response = $this
            ->actingAs($admin)
            ->put(
                route(
                    'admin.contact-settings.update'
                ),
                [
                    'company_name' =>
                        'Natviewer',

                    'whatsapp_number' =>
                        '',

                    'whatsapp_enabled' =>
                        0,

                    'email' =>
                        'ventas@natviewer.com',

                    'default_locale' =>
                        'es',

                    'default_currency' =>
                        'COP',

                    'quote_message_es' =>
                        'Mensaje ES',

                    'quote_message_en' =>
                        'Message EN',
                ]
            );

        $response->assertRedirect(
            route(
                'admin.contact-settings.edit'
            )
        );

        $settings = ContactSetting::query()
            ->firstOrFail();

        $this->assertFalse(
            $settings->whatsapp_enabled
        );

        $this->assertNull(
            $settings->whatsapp_number
        );
    }

    public function test_invalid_locale_and_currency_are_rejected(): void
    {
        $admin = $this->createAdmin();

        $response = $this
            ->actingAs($admin)
            ->from(
                route(
                    'admin.contact-settings.edit'
                )
            )
            ->put(
                route(
                    'admin.contact-settings.update'
                ),
                [
                    'company_name' =>
                        'Natviewer',

                    'whatsapp_number' =>
                        null,

                    'whatsapp_enabled' =>
                        0,

                    'email' =>
                        'ventas@natviewer.com',

                    'default_locale' =>
                        'fr',

                    'default_currency' =>
                        'PESO',

                    'quote_message_es' =>
                        null,

                    'quote_message_en' =>
                        null,
                ]
            );

        $response
            ->assertSessionHasErrors([
                'default_locale',
                'default_currency',
            ]);
    }

    public function test_settings_record_is_created_if_missing(): void
    {
        ContactSetting::query()
            ->delete();

        $this->assertDatabaseCount(
            'contact_settings',
            0
        );

        $admin = $this->createAdmin();

        $this
            ->actingAs($admin)
            ->get(
                route(
                    'admin.contact-settings.edit'
                )
            )
            ->assertOk();

        $this->assertDatabaseHas(
            'contact_settings',
            [
                'company_name' =>
                    'Natviewer',

                'default_locale' =>
                    'es',

                'default_currency' =>
                    'COP',
            ]
        );

        $this->assertDatabaseCount(
            'contact_settings',
            1
        );

        $settings = ContactSetting::query()
            ->firstOrFail();

        $this->assertFalse(
            $settings->whatsapp_enabled
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
}