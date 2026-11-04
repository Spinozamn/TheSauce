<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Innovacion;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class InnovacionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Innovacion::with(['categoria', 'usuario']);

        if ($request->filled('search')) {
            $termino = $request->query('search');
            $query->where(function ($q) use ($termino) {
                $q->where('titulo', 'like', "%{$termino}%")
                  ->orWhere('descripcion', 'like', "%{$termino}%");
            });
        }

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->query('categoria_id'));
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->query('estado'));
        }

        $perPage = (int) $request->query('per_page', 10);
        $innovaciones = $query->orderBy('id', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Coleccion de innovaciones consultada exitosamente',
            'data'    => $innovaciones,
            'errors'  => null,
        ], 200);
    }

    public function show(string|int $id): JsonResponse
    {
        $innovacion = Innovacion::find($id);

        if (!$innovacion) {
            return response()->json([
                'success' => false,
                'message' => 'La innovacion solicitada no existe',
                'data'    => null,
                'errors'  => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Innovacion consultada exitosamente',
            'data'    => $innovacion->load(['categoria', 'usuario']),
            'errors'  => null,
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'titulo'          => 'required|string|max:150',
            'descripcion'     => 'required|string|max:2000',
            'categoria_id'    => 'required|integer|exists:categorias,id',
            'meta_financiera' => 'required|numeric|min:1',
            'monto_recaudado' => 'nullable|numeric|min:0',
            'estado'          => 'nullable|string|in:activo,pausado,finalizado',
            'fecha_inicio'    => 'nullable|date',
            'fecha_fin'       => 'nullable|date|after_or_equal:fecha_inicio',
            'imagen'          => 'nullable|file|image|mimes:jpeg,png,jpg,webp|max:2048',
            'usuario_id'      => 'nullable|integer|exists:usuarios,id',
        ], [
            'titulo.required'          => 'El titulo es obligatorio.',
            'titulo.max'               => 'El titulo no debe exceder los 150 caracteres.',
            'descripcion.required'     => 'La descripcion es obligatoria.',
            'categoria_id.required'    => 'La categoria es obligatoria.',
            'categoria_id.exists'      => 'La categoria seleccionada no existe.',
            'meta_financiera.required' => 'La meta financiera es obligatoria.',
            'meta_financiera.numeric'  => 'La meta financiera debe ser un valor numerico.',
            'meta_financiera.min'      => 'La meta financiera debe ser mayor a cero.',
            'fecha_fin.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la fecha de inicio.',
            'imagen.image'             => 'El archivo debe ser una imagen valida.',
            'imagen.mimes'             => 'La imagen debe tener formato jpeg, png, jpg o webp.',
            'imagen.max'               => 'La imagen no debe superar los 2048 kilobytes.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Los datos enviados no son validos',
                'data'    => null,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $usuarioId = $request->input('usuario_id') ?? Usuario::first()?->id ?? 1;

        $imagenPath = null;
        if ($request->hasFile('imagen')) {
            $imagenPath = $request->file('imagen')->store('innovaciones', 'public');
        }

        $innovacion = Innovacion::create([
            'usuario_id'      => $usuarioId,
            'categoria_id'    => $request->input('categoria_id'),
            'titulo'          => $request->input('titulo'),
            'descripcion'     => $request->input('descripcion'),
            'imagen_portada'  => $imagenPath,
            'meta_financiera' => $request->input('meta_financiera'),
            'monto_recaudado' => $request->input('monto_recaudado', 0),
            'estado'          => $request->input('estado', 'activo'),
            'fecha_inicio'    => $request->input('fecha_inicio', now()->toDateString()),
            'fecha_fin'       => $request->input('fecha_fin', now()->addMonths(2)->toDateString()),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Innovacion registrada exitosamente',
            'data'    => $innovacion->fresh(['categoria', 'usuario']),
            'errors'  => null,
        ], 201);
    }

    public function update(Request $request, string|int $id): JsonResponse
    {
        $innovacion = Innovacion::find($id);

        if (!$innovacion) {
            return response()->json([
                'success' => false,
                'message' => 'La innovacion solicitada no existe',
                'data'    => null,
                'errors'  => null,
            ], 404);
        }

        $isPut = $request->isMethod('put');

        $validator = Validator::make($request->all(), [
            'titulo'          => ($isPut ? 'required|' : 'sometimes|') . 'string|max:150',
            'descripcion'     => ($isPut ? 'required|' : 'sometimes|') . 'string|max:2000',
            'categoria_id'    => ($isPut ? 'required|' : 'sometimes|') . 'integer|exists:categorias,id',
            'meta_financiera' => ($isPut ? 'required|' : 'sometimes|') . 'numeric|min:1',
            'monto_recaudado' => 'nullable|numeric|min:0',
            'estado'          => 'nullable|string|in:activo,pausado,finalizado',
            'fecha_inicio'    => 'nullable|date',
            'fecha_fin'       => 'nullable|date',
            'imagen'          => 'nullable|file|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'titulo.required'          => 'El titulo es obligatorio.',
            'descripcion.required'     => 'La descripcion es obligatoria.',
            'categoria_id.required'    => 'La categoria es obligatoria.',
            'categoria_id.exists'      => 'La categoria seleccionada no existe.',
            'meta_financiera.required' => 'La meta financiera es obligatoria.',
            'imagen.image'             => 'El archivo debe ser una imagen valida.',
            'imagen.mimes'             => 'La imagen debe tener formato jpeg, png, jpg o webp.',
            'imagen.max'               => 'La imagen no debe superar los 2048 kilobytes.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Los datos enviados no son validos',
                'data'    => null,
                'errors'  => $validator->errors(),
            ], 422);
        }

        if ($request->hasFile('imagen')) {
            if ($innovacion->imagen_portada && Storage::disk('public')->exists($innovacion->imagen_portada)) {
                Storage::disk('public')->delete($innovacion->imagen_portada);
            }
            $innovacion->imagen_portada = $request->file('imagen')->store('innovaciones', 'public');
        }

        $innovacion->fill($request->only([
            'titulo',
            'descripcion',
            'categoria_id',
            'meta_financiera',
            'monto_recaudado',
            'estado',
            'fecha_inicio',
            'fecha_fin',
        ]));
        $innovacion->save();

        return response()->json([
            'success' => true,
            'message' => 'Innovacion actualizada exitosamente',
            'data'    => $innovacion->fresh(['categoria', 'usuario']),
            'errors'  => null,
        ], 200);
    }

    public function destroy(string|int $id): JsonResponse
    {
        $innovacion = Innovacion::find($id);

        if (!$innovacion) {
            return response()->json([
                'success' => false,
                'message' => 'La innovacion solicitada no existe',
                'data'    => null,
                'errors'  => null,
            ], 404);
        }

        if ($innovacion->imagen_portada && Storage::disk('public')->exists($innovacion->imagen_portada)) {
            Storage::disk('public')->delete($innovacion->imagen_portada);
        }

        $innovacion->forceDelete();

        return response()->json([
            'success' => true,
            'message' => 'Innovacion eliminada exitosamente',
            'data'    => null,
            'errors'  => null,
        ], 200);
    }

    public function subirImagen(Request $request, string|int $id): JsonResponse
    {
        $innovacion = Innovacion::find($id);

        if (!$innovacion) {
            return response()->json([
                'success' => false,
                'message' => 'La innovacion solicitada no existe',
                'data'    => null,
                'errors'  => null,
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'imagen' => 'required|file|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'imagen.required' => 'El archivo de imagen es obligatorio.',
            'imagen.image'    => 'El archivo debe ser una imagen valida.',
            'imagen.mimes'    => 'La imagen debe tener formato jpeg, png, jpg o webp.',
            'imagen.max'      => 'La imagen no debe superar los 2048 kilobytes.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'El archivo proporcionado no es valido',
                'data'    => null,
                'errors'  => $validator->errors(),
            ], 422);
        }

        if ($innovacion->imagen_portada && Storage::disk('public')->exists($innovacion->imagen_portada)) {
            Storage::disk('public')->delete($innovacion->imagen_portada);
        }

        $path = $request->file('imagen')->store('innovaciones', 'public');
        $innovacion->imagen_portada = $path;
        $innovacion->save();

        return response()->json([
            'success' => true,
            'message' => 'Imagen asociada exitosamente al recurso',
            'data'    => [
                'id'             => $innovacion->id,
                'titulo'         => $innovacion->titulo,
                'imagen_portada' => $innovacion->imagen_portada,
                'imagen_url'     => $innovacion->imagen_url,
            ],
            'errors'  => null,
        ], 200);
    }

    public function eliminarImagen(string|int $id): JsonResponse
    {
        $innovacion = Innovacion::find($id);

        if (!$innovacion) {
            return response()->json([
                'success' => false,
                'message' => 'La innovacion solicitada no existe',
                'data'    => null,
                'errors'  => null,
            ], 404);
        }

        if ($innovacion->imagen_portada && Storage::disk('public')->exists($innovacion->imagen_portada)) {
            Storage::disk('public')->delete($innovacion->imagen_portada);
        }

        $innovacion->imagen_portada = null;
        $innovacion->save();

        return response()->json([
            'success' => true,
            'message' => 'Imagen retirada exitosamente del recurso',
            'data'    => [
                'id'             => $innovacion->id,
                'titulo'         => $innovacion->titulo,
                'imagen_portada' => null,
                'imagen_url'     => null,
            ],
            'errors'  => null,
        ], 200);
    }
}
