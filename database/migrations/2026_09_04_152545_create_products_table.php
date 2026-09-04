<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('brand_id')
                ->constrained()
                ->restrictOnDelete();

            $table->string('slug')->unique();

            $table->string('name_es');
            $table->string('name_en')->nullable();

            $table->text('short_description_es')->nullable();
            $table->text('short_description_en')->nullable();

            $table->longText('description_es')->nullable();
            $table->longText('description_en')->nullable();

            $table->string('status', 20)
                ->default('draft')
                ->index();

            $table->boolean('is_featured')
                ->default(false)
                ->index();

            $table->string('meta_title_es')->nullable();
            $table->string('meta_title_en')->nullable();

            $table->text('meta_description_es')->nullable();
            $table->text('meta_description_en')->nullable();

            $table->timestamps();

            $table->index([
                'category_id',
                'status',
            ]);

            $table->index([
                'brand_id',
                'status',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};