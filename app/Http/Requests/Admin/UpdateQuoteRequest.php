<?php

namespace App\Http\Requests\Admin;

use App\Models\QuoteRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin === true;
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                Rule::in([
                    QuoteRequest::STATUS_NEW,
                    QuoteRequest::STATUS_CONTACTED,
                    QuoteRequest::STATUS_WON,
                    QuoteRequest::STATUS_LOST,
                    QuoteRequest::STATUS_CANCELLED,
                ]),
            ],

            'admin_notes' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' =>
                'Selecciona un estado para la cotización.',

            'status.in' =>
                'El estado seleccionado no es válido.',

            'admin_notes.max' =>
                'Las notas internas pueden tener máximo 5000 caracteres.',
        ];
    }
}