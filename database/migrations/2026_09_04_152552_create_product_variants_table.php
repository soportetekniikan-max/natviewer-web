<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('sku')->unique();

            $table->string('name_es');
            $table->string('name_en')->nullable();

            $table->decimal('price', 14, 2)
                ->nullable();

            $table->char('currency', 3)
                ->default('COP');

            $table->boolean('manage_stock')
                ->default(true);

            $table->unsignedInteger('stock_quantity')
                ->nullable();

            $table->string('stock_status', 20)
                ->default('unknown')
                ->index();

            $table->json('specifications')
                ->nullable();

            $table->boolean('is_default')
                ->default(false);

            $table->boolean('is_active')
                ->default(true)
                ->index();

            $table->unsignedInteger('sort_order')
                ->default(0);

            $table->timestamps();

            $table->index([
                'product_id',
                'is_active',
                'sort_order',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};