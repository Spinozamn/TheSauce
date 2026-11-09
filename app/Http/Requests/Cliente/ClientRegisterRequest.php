<?php

namespace App\Http\Requests\Cliente;

use Illuminate\Foundation\Http\FormRequest;

class ClientRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('name') && !$this->has('nombre')) {
            $this->merge(['nombre' => $this->input('name')]);
        }
    }

    public function rules(): array
    {
        return [
            'nombre'   => ['required', 'string', 'min:3', 'max:100'],
            'email'    => ['required', 'string', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required'    => 'El nombre es obligatorio.',
            'nombre.min'         => 'El nombre debe contener al menos 3 caracteres.',
            'nombre.max'         => 'El nombre no puede exceder 100 caracteres.',
            'email.required'     => 'El correo electronico es obligatorio.',
            'email.email'        => 'El formato del correo electronico no es valido.',
            'email.unique'       => 'El correo electronico ya se encuentra registrado.',
            'password.required'  => 'La contrasena es obligatoria.',
            'password.min'       => 'La contrasena debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmacion de la contrasena no coincide.',
        ];
    }
}
