<?php

namespace App\Http\Requests\Admin;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin === true;
    }

    protected function prepareForValidation(): void
    {
        $variants = collect(
            $this->input('variants', [])
        )
            ->map(function ($variant) {
                if (isset($variant['currency'])) {
                    $variant['currency'] = strtoupper(
                        trim($variant['currency'])
                    );
                }

                return $variant;
            })
            ->all();

        $this->merge([
            'variants' => $variants,
        ]);
    }

    public function rules(): array
    {
        /** @var Product|null $product */
        $product = $this->route('product');

        return [
            'category_id' => [
                'required',
                'integer',
                'exists:categories,id',
            ],

            'brand_id' => [
                'required',
                'integer',
                'exists:brands,id',
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

            'short_description_es' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'short_description_en' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'description_es' => [
                'nullable',
                'string',
            ],

            'description_en' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                Rule::in([
                    Product::STATUS_DRAFT,
                    Product::STATUS_PUBLISHED,
                    Product::STATUS_ARCHIVED,
                ]),
            ],

            'is_featured' => [
                'required',
                'boolean',
            ],

            'variants' => [
                'required',
                'array',
                'min:1',
            ],

            'variants.*.id' => [
                'required',
                'integer',
                'distinct',
                Rule::exists(
                    'product_variants',
                    'id'
                )->where(
                    function ($query) use ($product) {
                        if ($product) {
                            $query->where(
                                'product_id',
                                $product->id
                            );
                        }
                    }
                ),
            ],

            'variants.*.name_es' => [
                'required',
                'string',
                'max:255',
            ],

            'variants.*.name_en' => [
                'nullable',
                'string',
                'max:255',
            ],

            'variants.*.price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'variants.*.currency' => [
                'required',
                'string',
                'size:3',
                'regex:/^[A-Z]{3}$/',
            ],

            'variants.*.manage_stock' => [
                'required',
                'boolean',
            ],

            'variants.*.stock_quantity' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'variants.*.stock_status' => [
                'required',
                Rule::in([
                    ProductVariant::STOCK_IN_STOCK,
                    ProductVariant::STOCK_OUT_OF_STOCK,
                    ProductVariant::STOCK_BACKORDER,
                    ProductVariant::STOCK_UNKNOWN,
                ]),
            ],

            'variants.*.is_active' => [
                'required',
                'boolean',
            ],

            'variants.*.specifications' => [
                'nullable',
                'array',
                'max:30',
            ],

            'variants.*.specifications.*.key' => [
                'nullable',
                'string',
                'max:100',
            ],

            'variants.*.specifications.*.value' => [
                'nullable',
                'string',
                'max:500',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name_es.required' =>
                'El nombre en español es obligatorio.',

            'category_id.required' =>
                'Selecciona una categoría.',

            'brand_id.required' =>
                'Selecciona una marca.',

            'variants.*.name_es.required' =>
                'El nombre de la variante es obligatorio.',

            'variants.*.price.numeric' =>
                'El precio debe ser un valor numérico.',

            'variants.*.price.min' =>
                'El precio no puede ser negativo.',

            'variants.*.stock_quantity.integer' =>
                'El stock debe ser un número entero.',

            'variants.*.stock_quantity.min' =>
                'El stock no puede ser negativo.',

            'variants.*.specifications.max' =>
                'Cada variante puede tener máximo 30 especificaciones.',

            'variants.*.specifications.*.key.max' =>
                'El nombre de la especificación es demasiado largo.',

            'variants.*.specifications.*.value.max' =>
                'El valor de la especificación es demasiado largo.',
        ];
    }
}