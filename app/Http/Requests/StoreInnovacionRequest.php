<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInnovacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titulo'          => ['required', 'string', 'min:5', 'max:200'],
            'categoria_id'    => ['required', 'integer', 'exists:categorias,id'],
            'descripcion'     => ['required', 'string', 'min:20', 'max:2000'],
            'meta_financiera' => ['required', 'numeric', 'min:1000', 'max:10000000'],
            'fecha_inicio'    => ['required', 'date'],
            'fecha_fin'       => ['required', 'date', 'after:fecha_inicio'],
            'imagen'          => ['required', 'file', 'image', 'mimes:jpeg,png,webp,jpg', 'max:2048'], // Máximo 2MB (2048 KB)
        ];
    }

    public function messages(): array
    {
        return [
            'titulo.required'          => 'El título del proyecto es obligatorio.',
            'titulo.min'               => 'El título debe tener al menos 5 caracteres.',
            'categoria_id.required'    => 'Debes seleccionar una categoría válida.',
            'categoria_id.exists'      => 'La categoría seleccionada no existe.',
            'descripcion.min'          => 'La descripción debe contener un mínimo de 20 caracteres.',
            'meta_financiera.required' => 'La meta financiera es requerida.',
            'meta_financiera.min'      => 'La meta debe ser de al menos $1,000 MXN.',
            'fecha_fin.after'          => 'La fecha de finalización debe ser posterior a la fecha de inicio.',
            'imagen.required'          => 'Es obligatorio subir una imagen de portada.',
            'imagen.image'             => 'El archivo debe ser una imagen.',
            'imagen.mimes'             => 'La imagen debe estar en formato JPG, JPEG, PNG o WEBP.',
            'imagen.max'               => 'La imagen no puede pesar más de 2MB.',
        ];
    }
}