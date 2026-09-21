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
            'categoria_id'    => 'required|exists:categorias,id',
            'titulo'          => 'required|string|min:5|max:150',
            'descripcion'     => 'required|string|min:20',
            'meta_financiera' => 'required|numeric|min:1000',
            'fecha_inicio'    => 'required|date',
            'fecha_fin'       => 'required|date|after_or_equal:fecha_inicio',
            'estado'          => 'nullable|string|in:borrador,activo,financiado,cancelado',
            'imagen'          => 'nullable|image|mimes:jpeg,png,webp,jpg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'categoria_id.required'    => 'Debes seleccionar una categoría.',
            'titulo.required'          => 'El título es obligatorio.',
            'titulo.min'               => 'El título debe contener al menos 5 caracteres.',
            'descripcion.required'     => 'La descripción es obligatoria.',
            'descripcion.min'          => 'La descripción debe tener al menos 20 caracteres.',
            'meta_financiera.required' => 'La meta financiera es obligatoria.',
            'meta_financiera.numeric'  => 'La meta financiera debe ser un valor numérico.',
            'meta_financiera.min'      => 'La meta financiera mínima es de $1,000 MXN.',
            'fecha_inicio.required'    => 'La fecha de inicio es requerida.',
            'fecha_fin.required'       => 'La fecha de finalización es requerida.',
            'fecha_fin.after_or_equal' => 'La fecha de finalización debe ser igual o posterior a la fecha de inicio.',
            'imagen.image'             => 'El archivo seleccionado debe ser una imagen válida.',
            'imagen.mimes'             => 'Formatos soportados: JPG, JPEG, PNG o WEBP.',
            'imagen.max'               => 'La imagen no debe superar los 2 MB de tamaño.',
        ];
    }
}