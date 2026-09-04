<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;

class StoreQuoteRequestRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $locale = $this->route('locale');

        if (in_array($locale, ['es', 'en'], true)) {
            App::setLocale($locale);
        }
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => [
                'required',
                'integer',
                'exists:products,id',
            ],

            'product_variant_id' => [
                'required',
                'integer',
                'exists:product_variants,id',
            ],

            'quantity' => [
                'required',
                'integer',
                'min:1',
                'max:99',
            ],

            'customer_name' => [
                'required',
                'string',
                'max:150',
            ],

            'customer_phone' => [
                'required',
                'string',
                'max:40',
            ],

            'customer_email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'customer_message' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'utm_source' => [
                'nullable',
                'string',
                'max:255',
            ],

            'utm_medium' => [
                'nullable',
                'string',
                'max:255',
            ],

            'utm_campaign' => [
                'nullable',
                'string',
                'max:255',
            ],

            'utm_term' => [
                'nullable',
                'string',
                'max:255',
            ],

            'utm_content' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    public function messages(): array
    {
        if ($this->route('locale') === 'en') {
            return [
                'customer_name.required' => 'Please enter your name.',
                'customer_phone.required' => 'Please enter your phone number.',
                'customer_email.email' => 'Please enter a valid email address.',
                'quantity.required' => 'Please select a quantity.',
                'quantity.min' => 'The minimum quantity is 1.',
                'quantity.max' => 'The maximum quantity is 99.',
            ];
        }

        return [
            'customer_name.required' => 'Ingresa tu nombre.',
            'customer_phone.required' => 'Ingresa tu número de teléfono.',
            'customer_email.email' => 'Ingresa un correo electrónico válido.',
            'quantity.required' => 'Selecciona una cantidad.',
            'quantity.min' => 'La cantidad mínima es 1.',
            'quantity.max' => 'La cantidad máxima es 99.',
        ];
    }
}