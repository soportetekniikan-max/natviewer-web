<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuoteRequest extends Model
{
    public const STATUS_NEW = 'new';
    public const STATUS_CONTACTED = 'contacted';
    public const STATUS_WON = 'won';
    public const STATUS_LOST = 'lost';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'reference',
        'status',
        'locale',
        'product_id',
        'product_variant_id',
        'product_name_snapshot',
        'variant_name_snapshot',
        'price_snapshot',
        'currency',
        'quantity',
        'customer_name',
        'customer_phone',
        'customer_email',
        'customer_message',
        'source_url',
        'utm_data',
        'whatsapp_opened_at',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'price_snapshot' => 'decimal:2',
            'quantity' => 'integer',
            'utm_data' => 'array',
            'whatsapp_opened_at' => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(
            ProductVariant::class,
            'product_variant_id'
        );
    }
}