<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdminLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'usuario'  => ['nullable', 'string', 'max:50'],
            'email'    => ['nullable', 'string', 'email', 'max:150'],
            'password' => ['required', 'string', 'min:6'],
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => 'La contrasena es obligatoria.',
            'password.min'      => 'La contrasena debe contener al menos 6 caracteres.',
            'email.email'       => 'El formato del correo electronico no es valido.',
        ];
    }
}
