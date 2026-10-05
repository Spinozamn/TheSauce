<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Innovacion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class InnovacionController extends Controller
{
    public function index(Request $solicitud): JsonResponse
    {
        $consulta = Innovacion::query();

        if ($solicitud->boolean('incluir_eliminadas')) {
            $consulta->withTrashed();
        }

        if ($solicitud->filled('categoria_id')) {
            $consulta->where('categoria_id', $solicitud->query('categoria_id'));
        }

        if ($solicitud->filled('estado')) {
            $consulta->where('estado', $solicitud->query('estado'));
        }

        return $this->respuesta(true, 'Innovaciones listadas correctamente.', $consulta->orderBy('id')->paginate(), 200);
    }

    public function show(int $id): JsonResponse
    {
        $innovacion = Innovacion::with([
            'usuario',
            'categoria',
            'etiquetas',
            'hitos',
            'comentarios',
            'contribuciones',
        ])->find($id);

        if (! $innovacion) {
            return $this->respuesta(false, 'La innovacion no existe.', null, 404);
        }

        return $this->respuesta(true, 'Innovacion encontrada correctamente.', $innovacion, 200);
    }

    public function store(Request $solicitud): JsonResponse
    {
        $validador = Validator::make(
            $solicitud->all(),
            $this->reglasValidacion(),
            $this->mensajesValidacion(),
            $this->atributosValidacion(),
        );

        if ($validador->fails()) {
            return $this->respuesta(false, 'Los datos enviados no son validos.', $validador->errors(), 422);
        }

        $innovacion = Innovacion::create($validador->validated());

        return $this->respuesta(true, 'Innovacion creada correctamente.', $innovacion, 201);
    }

    public function update(Request $solicitud, int $id): JsonResponse
    {
        $innovacion = Innovacion::find($id);

        if (! $innovacion) {
            return $this->respuesta(false, 'La innovacion no existe.', null, 404);
        }

        $esParcial = $solicitud->isMethod('patch');
        $validador = Validator::make(
            $solicitud->all(),
            $this->reglasValidacion($esParcial),
            $this->mensajesValidacion(),
            $this->atributosValidacion(),
        );

        if ($validador->fails()) {
            return $this->respuesta(false, 'Los datos enviados no son validos.', $validador->errors(), 422);
        }

        $innovacion->update($validador->validated());

        return $this->respuesta(true, 'Innovacion actualizada correctamente.', $innovacion->refresh(), 200);
    }

    public function destroy(int $id): JsonResponse|Response
    {
        $innovacion = Innovacion::find($id);

        if (! $innovacion) {
            return $this->respuesta(false, 'La innovacion no existe.', null, 404);
        }

        if (! $innovacion->delete()) {
            return $this->respuesta(false, 'No se pudo eliminar la innovacion.', null, 500);
        }

        return response()->noContent();
    }

    public function restore(int $id): JsonResponse
    {
        $innovacion = Innovacion::onlyTrashed()->find($id);

        if (! $innovacion) {
            return $this->respuesta(false, 'La innovacion eliminada no existe.', null, 404);
        }

        if (! $innovacion->restore()) {
            return $this->respuesta(false, 'No se pudo restaurar la innovacion.', null, 500);
        }

        return $this->respuesta(true, 'Innovacion restaurada correctamente.', $innovacion->refresh(), 200);
    }

    public function forceDelete(int $id): JsonResponse|Response
    {
        $innovacion = Innovacion::withTrashed()->find($id);

        if (! $innovacion) {
            return $this->respuesta(false, 'La innovacion no existe.', null, 404);
        }

        if ($innovacion->contribuciones()->exists()) {
            return $this->respuesta(false, 'No se puede eliminar la innovacion porque tiene contribuciones asociadas.', null, 409);
        }

        if (! $innovacion->forceDelete()) {
            return $this->respuesta(false, 'No se pudo eliminar permanentemente la innovacion.', null, 500);
        }

        return response()->noContent();
    }

    private function reglasValidacion(bool $esParcial = false): array
    {
        $obligatorio = $esParcial ? 'sometimes|required' : 'required';

        return [
            'usuario_id' => [$obligatorio, 'integer', 'exists:usuarios,id'],
            'categoria_id' => [$obligatorio, 'integer', 'exists:categorias,id'],
            'titulo' => [$obligatorio, 'string', 'max:200'],
            'descripcion' => [$obligatorio, 'string'],
            'imagen_portada' => ['sometimes', 'nullable', 'string', 'max:255'],
            'meta_financiera' => [$obligatorio, 'numeric', 'min:0'],
            'monto_recaudado' => ['sometimes', 'numeric', 'min:0'],
            'estado' => ['sometimes', 'in:borrador,activo,financiado,cancelado'],
            'fecha_inicio' => ['sometimes', 'nullable', 'date'],
            'fecha_fin' => ['sometimes', 'nullable', 'date'],
        ];
    }

    private function mensajesValidacion(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'integer' => 'El campo :attribute debe ser un numero entero.',
            'exists' => 'El campo :attribute seleccionado no es valido.',
            'string' => 'El campo :attribute debe ser texto.',
            'max' => 'El campo :attribute excede el limite permitido.',
            'numeric' => 'El campo :attribute debe ser numerico.',
            'min' => 'El campo :attribute debe ser mayor o igual a cero.',
            'in' => 'El valor de :attribute no es valido.',
            'date' => 'El campo :attribute debe ser una fecha valida.',
        ];
    }

    private function atributosValidacion(): array
    {
        return [
            'usuario_id' => 'usuario',
            'categoria_id' => 'categoria',
            'titulo' => 'titulo',
            'descripcion' => 'descripcion',
            'imagen_portada' => 'imagen de portada',
            'meta_financiera' => 'meta financiera',
            'monto_recaudado' => 'monto recaudado',
            'estado' => 'estado',
            'fecha_inicio' => 'fecha de inicio',
            'fecha_fin' => 'fecha de fin',
        ];
    }

    private function respuesta(bool $success, string $message, mixed $data, int $status): JsonResponse
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ], $status);
    }
}
