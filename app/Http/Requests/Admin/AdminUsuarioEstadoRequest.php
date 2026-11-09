<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdminUsuarioEstadoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'estado' => ['required', 'string', 'in:activo,inactivo,suspendido'],
            'rol_id' => ['nullable', 'integer', 'exists:roles,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'estado.required' => 'El estado del usuario es obligatorio.',
            'estado.in'       => 'El estado debe ser activo, inactivo o suspendido.',
            'rol_id.exists'   => 'El rol seleccionado no es valido.',
        ];
    }
}
