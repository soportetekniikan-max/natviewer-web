<?php

namespace App\Http\Requests\Admin;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin === true;
    }

    public function rules(): array
    {
        /** @var Product|null $product */
        $product = $this->route('product');

        return [
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
                'required',
                'integer',
                'min:0',
                'max:9999',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'variant_id.exists' =>
                'La variante seleccionada no pertenece a este producto.',

            'sort_order.required' =>
                'Indica el orden de la imagen.',

            'sort_order.integer' =>
                'El orden debe ser un número entero.',
        ];
    }
}