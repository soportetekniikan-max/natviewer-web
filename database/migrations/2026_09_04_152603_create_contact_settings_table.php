<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_settings', function (Blueprint $table) {
            $table->id();

            $table->string('company_name')
                ->default('Natviewer');

            $table->string('whatsapp_number')
                ->nullable();

            $table->boolean('whatsapp_enabled')
                ->default(false);

            $table->string('email')
                ->nullable();

            $table->char('default_locale', 2)
                ->default('es');

            $table->char('default_currency', 3)
                ->default('COP');

            $table->text('quote_message_es')
                ->nullable();

            $table->text('quote_message_en')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_settings');
    }
};