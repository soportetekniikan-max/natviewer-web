<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    public const STOCK_IN_STOCK = 'in_stock';
    public const STOCK_OUT_OF_STOCK = 'out_of_stock';
    public const STOCK_BACKORDER = 'backorder';
    public const STOCK_UNKNOWN = 'unknown';

    protected $fillable = [
        'product_id',
        'sku',
        'name_es',
        'name_en',
        'price',
        'currency',
        'manage_stock',
        'stock_quantity',
        'stock_status',
        'specifications',
        'is_default',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'manage_stock' => 'boolean',
            'stock_quantity' => 'integer',
            'specifications' => 'array',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(
            ProductImage::class,
            'product_variant_id'
        )
            ->orderBy('sort_order')
            ->orderBy('id');
    }

    public function quoteRequests(): HasMany
    {
        return $this->hasMany(
            QuoteRequest::class,
            'product_variant_id'
        );
    }
}