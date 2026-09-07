<?php

namespace App\Http\Requests\Admin;

use App\Models\Brand;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin === true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'slug' => $this->filled('slug')
                ? Str::slug((string) $this->input('slug'))
                : null,

            'is_active' => $this->boolean('is_active'),

            'remove_logo' =>
                $this->boolean('remove_logo'),

            'sort_order' => $this->filled('sort_order')
                ? (int) $this->input('sort_order')
                : 0,
        ]);
    }

    public function rules(): array
    {
        /** @var Brand|null $brand */
        $brand = $this->route('brand');

        return [
            'slug' => [
                'nullable',
                'string',
                'max:255',

                Rule::unique(
                    'brands',
                    'slug'
                )->ignore($brand),
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'description_es' => [
                'nullable',
                'string',
            ],

            'description_en' => [
                'nullable',
                'string',
            ],

            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],

            'remove_logo' => [
                'required',
                'boolean',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],

            'sort_order' => [
                'required',
                'integer',
                'min:0',
                'max:9999',
            ],
        ];
    }
}