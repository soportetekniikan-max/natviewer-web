<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreAdminUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isSuperAdmin()
            === true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' =>
                strtolower(
                    trim(
                        (string) $this->input(
                            'email'
                        )
                    )
                ),

            'admin_role' =>
                trim(
                    (string) $this->input(
                        'admin_role'
                    )
                ),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique(
                    'users',
                    'email'
                ),
            ],

            'password' => [
                'required',
                'confirmed',
                Password::min(12)
                    ->letters()
                    ->mixedCase()
                    ->numbers(),
            ],

            'admin_role' => [
                'required',
                Rule::in(
                    User::ADMIN_ROLES
                ),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' =>
                'El nombre es obligatorio.',

            'email.required' =>
                'El correo electrónico es obligatorio.',

            'email.email' =>
                'Ingresa un correo electrónico válido.',

            'email.unique' =>
                'Ya existe un usuario con este correo electrónico.',

            'password.required' =>
                'La contraseña es obligatoria.',

            'password.confirmed' =>
                'La confirmación de contraseña no coincide.',

            'admin_role.required' =>
                'Selecciona un rol administrativo.',

            'admin_role.in' =>
                'El rol seleccionado no es válido.',
        ];
    }
}