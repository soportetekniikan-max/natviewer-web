<?php

namespace App\Http\Requests\Admin;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin === true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'make_primary' =>
                $this->boolean(
                    'make_primary'
                ),
        ]);
    }

    public function rules(): array
    {
        /** @var Product|null $product */
        $product = $this->route(
            'product'
        );

        return [
            'images' => [
                'required',
                'array',
                'min:1',
                'max:20',
            ],

            'images.*' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:8192',
            ],

            'variant_id' => [
                'nullable',
                'integer',

                Rule::exists(
                    'product_variants',
                    'id'
                )->where(
                    function (
                        $query
                    ) use (
                        $product
                    ) {
                        if ($product) {
                            $query->where(
                                'product_id',
                                $product->id
                            );
                        }
                    }
                ),
            ],

            'alt_es' => [
                'nullable',
                'string',
                'max:255',
            ],

            'alt_en' => [
                'nullable',
                'string',
                'max:255',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
                'max:9999',
            ],

            'make_primary' => [
                'required',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'images.required' =>
                'Selecciona al menos una imagen.',

            'images.array' =>
                'La selección de imágenes no es válida.',

            'images.min' =>
                'Selecciona al menos una imagen.',

            'images.max' =>
                'Puedes subir máximo 20 imágenes por lote.',

            'images.*.required' =>
                'Cada archivo de imagen es obligatorio.',

            'images.*.image' =>
                'Todos los archivos deben ser imágenes válidas.',

            'images.*.mimes' =>
                'Solo se permiten imágenes JPG, JPEG, PNG o WebP.',

            'images.*.max' =>
                'Cada imagen puede pesar máximo 8 MB.',

            'variant_id.exists' =>
                'La variante seleccionada no pertenece a este producto.',
        ];
    }
}