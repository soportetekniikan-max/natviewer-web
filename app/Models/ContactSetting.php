<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSetting extends Model
{
    protected $fillable = [
        'company_name',
        'whatsapp_number',
        'whatsapp_enabled',
        'email',
        'default_locale',
        'default_currency',
        'quote_message_es',
        'quote_message_en',
    ];

    protected function casts(): array
    {
        return [
            'whatsapp_enabled' => 'boolean',
        ];
    }
}