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
                $this->boolean('make_primary'),
        ]);
    }

    public function rules(): array
    {
        /** @var Product|null $product */
        $product = $this->route('product');

        return [
            'image' => [
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
            'image.required' =>
                'Selecciona una imagen.',

            'image.image' =>
                'El archivo debe ser una imagen válida.',

            'image.mimes' =>
                'Solo se permiten imágenes JPG, JPEG, PNG o WebP.',

            'image.max' =>
                'La imagen no puede superar 8 MB.',

            'variant_id.exists' =>
                'La variante seleccionada no pertenece a este producto.',
        ];
    }
}