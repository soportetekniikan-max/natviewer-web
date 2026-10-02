<?php

namespace App\Http\Requests\Admin;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
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
        /** @var Category|null $category */
        $category = $this->route('category');

        return [
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique(
                    'categories',
                    'slug'
                )->ignore($category),
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

            'description_es' => [
                'nullable',
                'string',
            ],

            'description_en' => [
                'nullable',
                'string',
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
            'name_es.required' =>
                'El nombre en español es obligatorio.',

            'slug.unique' =>
                'Ya existe otra categoría con este slug.',
        ];
    }
}