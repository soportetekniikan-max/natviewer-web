<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreBrandRequest extends FormRequest
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

            'sort_order' => $this->filled('sort_order')
                ? (int) $this->input('sort_order')
                : 0,
        ]);
    }

    public function rules(): array
    {
        return [
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('brands', 'slug'),
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

    public function messages(): array
    {
        return [
            'name.required' =>
                'El nombre de la marca es obligatorio.',

            'slug.unique' =>
                'Ya existe una marca con este slug.',

            'logo.image' =>
                'El logo debe ser una imagen válida.',

            'logo.mimes' =>
                'El logo debe ser JPG, JPEG, PNG o WebP.',

            'logo.max' =>
                'El logo puede pesar máximo 4 MB.',
        ];
    }
}