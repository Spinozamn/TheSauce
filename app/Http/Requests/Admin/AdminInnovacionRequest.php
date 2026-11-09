<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdminInnovacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isPost = $this->isMethod('post');

        return [
            'titulo'          => [$isPost ? 'required' : 'sometimes', 'string', 'min:3', 'max:150'],
            'descripcion'     => [$isPost ? 'required' : 'sometimes', 'string', 'min:10', 'max:2000'],
            'categoria_id'    => [$isPost ? 'required' : 'sometimes', 'integer', 'exists:categorias,id'],
            'meta_financiera' => [$isPost ? 'required' : 'sometimes', 'numeric', 'min:1', 'max:99999999.99'],
            'estado'          => ['nullable', 'string', 'in:borrador,activo,cancelado,completado,suspendido'],
            'fecha_inicio'    => ['nullable', 'date'],
            'fecha_fin'       => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
        ];
    }

    public function messages(): array
    {
        return [
            'titulo.required'          => 'El titulo es obligatorio.',
            'titulo.min'               => 'El titulo debe contener al menos 3 caracteres.',
            'titulo.max'               => 'El titulo no puede exceder 150 caracteres.',
            'descripcion.required'     => 'La descripcion es obligatoria.',
            'descripcion.min'          => 'La descripcion debe contener al menos 10 caracteres.',
            'categoria_id.required'    => 'La categoria es obligatoria.',
            'categoria_id.exists'      => 'La categoria seleccionada no es valida o no existe.',
            'meta_financiera.required' => 'La meta financiera es obligatoria.',
            'meta_financiera.numeric'  => 'La meta financiera debe ser un valor numerico.',
            'meta_financiera.min'      => 'La meta financiera debe ser mayor o igual a 1.',
            'estado.in'                => 'El estado especificado no es valido.',
            'fecha_fin.after_or_equal' => 'La fecha de finalizacion debe ser posterior o igual a la de inicio.',
        ];
    }
}
