<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInnovacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'categoria_id'    => ['required', 'integer', 'exists:categorias,id'],
            'titulo'          => ['required', 'string', 'min:5', 'max:200'],
            'descripcion'     => ['required', 'string', 'min:20', 'max:2000'],
            'meta_financiera' => ['required', 'numeric', 'min:1000', 'max:10000000'],
            'fecha_inicio'    => ['required', 'date'],
            'fecha_fin'       => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'estado'          => ['nullable', 'string', 'in:borrador,activo,financiado,cancelado'],
            'imagen'          => ['nullable', 'file', 'image', 'mimes:jpeg,png,webp,jpg', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'categoria_id.required'    => 'Debes seleccionar una categoria valida.',
            'categoria_id.exists'      => 'La categoria seleccionada no existe.',
            'titulo.required'          => 'El titulo es obligatorio.',
            'titulo.min'               => 'El titulo debe contener al menos 5 caracteres.',
            'descripcion.required'     => 'La descripcion es obligatoria.',
            'descripcion.min'          => 'La descripcion debe tener al menos 20 caracteres.',
            'meta_financiera.required' => 'La meta financiera es requerida.',
            'meta_financiera.numeric'  => 'La meta financiera debe ser un valor numerico.',
            'meta_financiera.min'      => 'La meta debe ser de al menos 1000.',
            'fecha_inicio.required'    => 'La fecha de inicio es requerida.',
            'fecha_fin.required'       => 'La fecha de finalizacion es requerida.',
            'fecha_fin.after_or_equal' => 'La fecha de finalizacion debe ser igual o posterior a la fecha de inicio.',
            'imagen.image'             => 'El archivo seleccionado debe ser una imagen valida.',
            'imagen.mimes'             => 'La imagen debe estar en formato JPG, JPEG, PNG o WEBP.',
            'imagen.max'               => 'La imagen no debe superar los 2MB de tamano.',
        ];
    }
}
