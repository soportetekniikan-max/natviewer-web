<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateContactSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin === true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'whatsapp_enabled' =>
                $this->boolean('whatsapp_enabled'),

            'default_locale' =>
                strtolower(
                    trim(
                        (string) $this->input(
                            'default_locale',
                            'es'
                        )
                    )
                ),

            'default_currency' =>
                strtoupper(
                    trim(
                        (string) $this->input(
                            'default_currency',
                            'COP'
                        )
                    )
                ),

            'whatsapp_number' =>
                $this->filled('whatsapp_number')
                    ? trim(
                        (string) $this->input(
                            'whatsapp_number'
                        )
                    )
                    : null,

            'email' =>
                $this->filled('email')
                    ? strtolower(
                        trim(
                            (string) $this->input(
                                'email'
                            )
                        )
                    )
                    : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'company_name' => [
                'required',
                'string',
                'max:255',
            ],

            'whatsapp_number' => [
                'nullable',
                'string',
                'max:40',
                'regex:/^\+?[0-9\s\-\(\)]+$/',
            ],

            'whatsapp_enabled' => [
                'required',
                'boolean',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'default_locale' => [
                'required',
                Rule::in([
                    'es',
                    'en',
                ]),
            ],

            'default_currency' => [
                'required',
                'string',
                'size:3',
                'regex:/^[A-Z]{3}$/',
            ],

            'quote_message_es' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'quote_message_en' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if (
                    $this->boolean('whatsapp_enabled')
                    && ! $this->filled('whatsapp_number')
                ) {
                    $validator
                        ->errors()
                        ->add(
                            'whatsapp_number',
                            'Debes indicar un número de WhatsApp si WhatsApp está activado.'
                        );
                }
            },
        ];
    }

    public function messages(): array
    {
        return [
            'company_name.required' =>
                'El nombre de la empresa es obligatorio.',

            'whatsapp_number.regex' =>
                'El número de WhatsApp no tiene un formato válido.',

            'email.email' =>
                'Ingresa un correo electrónico válido.',

            'default_locale.in' =>
                'El idioma predeterminado debe ser español o inglés.',

            'default_currency.size' =>
                'La moneda debe tener exactamente 3 caracteres.',

            'default_currency.regex' =>
                'La moneda debe utilizar el formato ISO, por ejemplo COP o USD.',

            'quote_message_es.max' =>
                'El mensaje en español puede tener máximo 2000 caracteres.',

            'quote_message_en.max' =>
                'El mensaje en inglés puede tener máximo 2000 caracteres.',
        ];
    }
}