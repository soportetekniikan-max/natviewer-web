<?php

namespace App\Http\Requests\Admin;

use App\Models\ProductVariant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateProductVariantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin === true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'currency' => strtoupper(
                trim((string) $this->input('currency', 'COP'))
            ),

            'manage_stock' => $this->boolean('manage_stock'),
            'is_default' => $this->boolean('is_default'),
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    public function rules(): array
    {
        /** @var ProductVariant|null $variant */
        $variant = $this->route('variant');

        return [
            'sku' => [
                'required',
                'string',
                'max:100',

                Rule::unique(
                    'product_variants',
                    'sku'
                )->ignore($variant),
            ],

            'name_es' => [
                'required',
                'string',
                'max:255',
            ],

            'name_en' => [
                'nullable',
                'string',
                'max:255',
            ],

            'price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'currency' => [
                'required',
                'string',
                'size:3',
                'regex:/^[A-Z]{3}$/',
            ],

            'manage_stock' => [
                'required',
                'boolean',
            ],

            'stock_quantity' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'stock_status' => [
                'required',

                Rule::in([
                    ProductVariant::STOCK_IN_STOCK,
                    ProductVariant::STOCK_OUT_OF_STOCK,
                    ProductVariant::STOCK_BACKORDER,
                    ProductVariant::STOCK_UNKNOWN,
                ]),
            ],

            'is_default' => [
                'required',
                'boolean',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
                'max:9999',
            ],

            'specifications' => [
                'nullable',
                'array',
                'max:30',
            ],

            'specifications.*.key' => [
                'nullable',
                'string',
                'max:100',
            ],

            'specifications.*.value' => [
                'nullable',
                'string',
                'max:500',
            ],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if (
                    $this->boolean('is_default')
                    && ! $this->boolean('is_active')
                ) {
                    $validator->errors()->add(
                        'is_default',
                        'Una variante predeterminada debe estar activa.'
                    );
                }
            },
        ];
    }

    public function messages(): array
    {
        return [
            'sku.required' =>
                'El SKU es obligatorio.',

            'sku.unique' =>
                'Ya existe otra variante con este SKU.',

            'name_es.required' =>
                'El nombre en español es obligatorio.',

            'price.numeric' =>
                'El precio debe ser numérico.',

            'stock_quantity.integer' =>
                'El stock debe ser un número entero.',
        ];
    }
}