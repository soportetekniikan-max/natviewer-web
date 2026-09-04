<?php

namespace Database\Seeders;

use App\Models\ContactSetting;
use Illuminate\Database\Seeder;

class ContactSettingSeeder extends Seeder
{
    public function run(): void
    {
        ContactSetting::updateOrCreate(
            [
                'id' => 1,
            ],
            [
                'company_name' => 'Natviewer',

                /*
                 * Pendientes de definición.
                 */
                'whatsapp_number' => null,
                'whatsapp_enabled' => false,
                'email' => null,

                'default_locale' => 'es',
                'default_currency' => 'COP',

                'quote_message_es' => 'Hola, estoy interesado en cotizar el producto :product :variant.',
                'quote_message_en' => 'Hello, I am interested in requesting a quote for :product :variant.',
            ]
        );
    }
}