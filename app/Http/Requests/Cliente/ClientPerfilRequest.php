<?php

namespace App\Http\Requests\Cliente;

use Illuminate\Foundation\Http\FormRequest;

class ClientPerfilRequest extends FormRequest
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
        $userId = $this->user()?->id;

        return [
            'nombre'   => ['sometimes', 'required', 'string', 'min:3', 'max:100'],
            'email'    => ['sometimes', 'required', 'string', 'email', 'max:150', 'unique:users,email,' . $userId],
            'telefono' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.min'      => 'El nombre debe contener al menos 3 caracteres.',
            'nombre.max'      => 'El nombre no puede exceder 100 caracteres.',
            'email.required'  => 'El correo electronico es obligatorio.',
            'email.email'     => 'El formato del correo electronico no es valido.',
            'email.unique'    => 'El correo electronico ya esta en uso por otra cuenta.',
            'telefono.max'    => 'El numero de telefono no puede exceder 20 caracteres.',
        ];
    }
}
