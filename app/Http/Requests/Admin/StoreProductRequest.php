<?php

namespace App\Http\Requests\Admin;

use App\Models\ProductVariant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreProductRequest extends FormRequest
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
                        trim(
                            (string) $variant['currency']
                        )
                    );
                }

                return $variant;
            })
            ->all();

        $slug = null;

        if ($this->filled('slug')) {
            $slug = Str::slug(
                (string) $this->input('slug')
            );
        }

        $this->merge([
            'slug' => $slug,

            'is_featured' =>
                $this->boolean('is_featured'),

            'variants' =>
                $variants,
        ]);
    }

    public function rules(): array
    {
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

            'slug' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('products', 'slug'),
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

            'is_featured' => [
                'required',
                'boolean',
            ],

            /*
            |--------------------------------------------------------------------------
            | Variantes
            |--------------------------------------------------------------------------
            */

            'variants' => [
                'required',
                'array',
                'min:1',
                'max:30',
            ],

            'variants.*.sku' => [
                'required',
                'string',
                'max:100',
                'distinct:ignore_case',
                Rule::unique(
                    'product_variants',
                    'sku'
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

            'variants.*.is_default' => [
                'required',
                'boolean',
            ],

            'variants.*.is_active' => [
                'required',
                'boolean',
            ],

            'variants.*.sort_order' => [
                'nullable',
                'integer',
                'min:0',
                'max:9999',
            ],

            /*
            |--------------------------------------------------------------------------
            | Especificaciones
            |--------------------------------------------------------------------------
            */

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

            /*
            |--------------------------------------------------------------------------
            | Imágenes
            |--------------------------------------------------------------------------
            */

            'images' => [
                'nullable',
                'array',
                'max:20',
            ],

            'images.*.file' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:8192',
            ],

            'images.*.alt_es' => [
                'nullable',
                'string',
                'max:255',
            ],

            'images.*.alt_en' => [
                'nullable',
                'string',
                'max:255',
            ],

            'images.*.variant_key' => [
                'nullable',
                'string',
                'max:50',
            ],

            'images.*.sort_order' => [
                'nullable',
                'integer',
                'min:0',
                'max:9999',
            ],

            'images.*.is_primary' => [
                'required',
                'boolean',
            ],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $variantKeys = array_map(
                    'strval',
                    array_keys(
                        $this->input(
                            'variants',
                            []
                        )
                    )
                );

                foreach (
                    $this->input('images', [])
                    as $index => $image
                ) {
                    $variantKey =
                        $image['variant_key']
                        ?? null;

                    if (
                        $variantKey !== null
                        && $variantKey !== ''
                        && ! in_array(
                            (string) $variantKey,
                            $variantKeys,
                            true
                        )
                    ) {
                        $validator
                            ->errors()
                            ->add(
                                "images.$index.variant_key",
                                'La variante seleccionada para la imagen no es válida.'
                            );
                    }
                }
            },
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' =>
                'Selecciona una categoría.',

            'brand_id.required' =>
                'Selecciona una marca.',

            'name_es.required' =>
                'El nombre en español es obligatorio.',

            'slug.unique' =>
                'Ya existe un producto con este slug.',

            'variants.required' =>
                'Debes crear al menos una variante.',

            'variants.min' =>
                'Debes crear al menos una variante.',

            'variants.*.sku.required' =>
                'El SKU de la variante es obligatorio.',

            'variants.*.sku.unique' =>
                'Ya existe una variante con este SKU.',

            'variants.*.sku.distinct' =>
                'No puedes repetir el mismo SKU.',

            'variants.*.name_es.required' =>
                'El nombre de la variante es obligatorio.',

            'variants.*.price.numeric' =>
                'El precio debe ser numérico.',

            'variants.*.stock_quantity.integer' =>
                'La cantidad de stock debe ser un número entero.',

            'images.*.file.required' =>
                'Selecciona un archivo de imagen.',

            'images.*.file.image' =>
                'El archivo debe ser una imagen válida.',

            'images.*.file.mimes' =>
                'Solo se permiten JPG, JPEG, PNG o WebP.',

            'images.*.file.max' =>
                'Cada imagen puede pesar máximo 8 MB.',
        ];
    }
}