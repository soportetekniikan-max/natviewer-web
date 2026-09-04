<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quote_requests', function (Blueprint $table) {
            $table->id();

            $table->string('reference', 40)
                ->unique();

            $table->string('status', 20)
                ->default('new')
                ->index();

            $table->char('locale', 2)
                ->default('es');

            $table->foreignId('product_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('product_variant_id')
                ->nullable()
                ->constrained('product_variants')
                ->nullOnDelete();

            $table->string('product_name_snapshot')
                ->nullable();

            $table->string('variant_name_snapshot')
                ->nullable();

            $table->decimal('price_snapshot', 14, 2)
                ->nullable();

            $table->char('currency', 3)
                ->default('COP');

            $table->unsignedInteger('quantity')
                ->default(1);

            $table->string('customer_name')
                ->nullable();

            $table->string('customer_phone', 40)
                ->nullable();

            $table->string('customer_email')
                ->nullable();

            $table->text('customer_message')
                ->nullable();

            $table->text('source_url')
                ->nullable();

            $table->json('utm_data')
                ->nullable();

            $table->timestamp('whatsapp_opened_at')
                ->nullable();

            $table->text('admin_notes')
                ->nullable();

            $table->timestamps();

            $table->index([
                'status',
                'created_at',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quote_requests');
    }
};