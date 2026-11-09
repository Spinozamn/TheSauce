<?php

namespace App\Http\Requests\Cliente;

use Illuminate\Foundation\Http\FormRequest;

class ClientRegistroRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isPost = $this->isMethod('post');

        return [
            'titulo'          => [$isPost ? 'required' : 'sometimes', 'string', 'min:5', 'max:200'],
            'categoria_id'    => [$isPost ? 'required' : 'sometimes', 'integer', 'exists:categorias,id'],
            'descripcion'     => [$isPost ? 'required' : 'sometimes', 'string', 'min:20', 'max:2000'],
            'meta_financiera' => [$isPost ? 'required' : 'sometimes', 'numeric', 'min:1000', 'max:10000000'],
            'fecha_inicio'    => ['nullable', 'date'],
            'fecha_fin'       => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
        ];
    }

    public function messages(): array
    {
        return [
            'titulo.required'          => 'El titulo del proyecto es obligatorio.',
            'titulo.min'               => 'El titulo debe tener al menos 5 caracteres.',
            'titulo.max'               => 'El titulo no puede exceder 200 caracteres.',
            'categoria_id.required'    => 'Debes seleccionar una categoria valida.',
            'categoria_id.exists'      => 'La categoria seleccionada no existe.',
            'descripcion.required'     => 'La descripcion es obligatoria.',
            'descripcion.min'          => 'La descripcion debe contener un minimo de 20 caracteres.',
            'descripcion.max'          => 'La descripcion no puede exceder 2000 caracteres.',
            'meta_financiera.required' => 'La meta financiera es requerida.',
            'meta_financiera.numeric'  => 'La meta financiera debe ser un valor numerico.',
            'meta_financiera.min'      => 'La meta debe ser de al menos 1000.',
            'meta_financiera.max'      => 'La meta no puede exceder 10000000.',
            'fecha_fin.after_or_equal' => 'La fecha de finalizacion debe ser igual o posterior a la fecha de inicio.',
        ];
    }
}
