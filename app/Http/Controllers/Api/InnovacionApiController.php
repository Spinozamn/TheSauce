<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Innovacion;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class InnovacionApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        Gate::authorize('consultar');

        $query = Innovacion::with(['categoria', 'usuario', 'etiquetas']);

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
            'data'    => $innovaciones,
        ]);
    }

    public function show(string|int $id): JsonResponse
    {
        Gate::authorize('consultar');

        $innovacion = Innovacion::with([
            'categoria',
            'usuario',
            'etiquetas',
            'hitosFinancieros',
            'comentarios.usuario',
            'contribuciones.usuario',
        ])->find($id);

        if (!$innovacion) {
            return response()->json([
                'success' => false,
                'message' => "La innovacion con ID #{$id} no existe.",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $innovacion,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        Gate::authorize('crear');

        $validated = $request->validate([
            'titulo'          => ['required', 'string', 'min:5', 'max:200'],
            'categoria_id'    => ['required', 'integer', 'exists:categorias,id'],
            'descripcion'     => ['required', 'string', 'min:20', 'max:2000'],
            'meta_financiera' => ['required', 'numeric', 'min:1000', 'max:10000000'],
            'fecha_inicio'    => ['required', 'date'],
            'fecha_fin'       => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'imagen'          => ['nullable', 'file', 'image', 'mimes:jpeg,png,webp,jpg', 'max:2048'],
            'imagen_portada'  => ['nullable', 'string', 'max:255'],
        ], [
            'titulo.required'          => 'El titulo del proyecto es obligatorio.',
            'titulo.min'               => 'El titulo debe tener al menos 5 caracteres.',
            'categoria_id.required'    => 'Debes seleccionar una categoria valida.',
            'categoria_id.exists'      => 'La categoria seleccionada no existe.',
            'descripcion.min'          => 'La descripcion debe contener un minimo de 20 caracteres.',
            'meta_financiera.required' => 'La meta financiera es requerida.',
            'meta_financiera.min'      => 'La meta debe ser de al menos 1000.',
            'fecha_fin.after_or_equal' => 'La fecha de finalizacion debe ser igual o posterior a la fecha de inicio.',
        ]);

        $actor = Auth::user();
        $usuarioId = $actor instanceof Usuario
            ? $actor->id
            : (Usuario::where('email', $actor?->email)->value('id') ?? Usuario::value('id'));
        if (!$usuarioId) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede registrar la innovacion sin un usuario asignado.',
            ], 422);
        }

        $innovacion = DB::transaction(function () use ($request, $validated, $usuarioId) {
            $nuevo = Innovacion::create([
                'usuario_id'      => $usuarioId,
                'categoria_id'    => $validated['categoria_id'],
                'titulo'          => $validated['titulo'],
                'descripcion'     => $validated['descripcion'],
                'meta_financiera' => $validated['meta_financiera'],
                'monto_recaudado' => 0.00,
                'estado'          => 'borrador',
                'fecha_inicio'    => $validated['fecha_inicio'],
                'fecha_fin'       => $validated['fecha_fin'],
                'imagen_portada'  => $validated['imagen_portada'] ?? null,
            ]);

            if ($request->hasFile('imagen')) {
                $archivo = $request->file('imagen');
                $extension = $archivo->getClientOriginalExtension();
                $nombreArchivo = "innovacion_{$nuevo->id}.{$extension}";
                $archivo->storeAs('innovaciones', $nombreArchivo, 'public');

                $nuevo->update([
                    'imagen_portada' => "/storage/innovaciones/{$nombreArchivo}",
                ]);
            }

            return $nuevo;
        });

        return response()->json([
            'success' => true,
            'message' => 'Innovacion registrada exitosamente.',
            'data'    => $innovacion->load(['categoria', 'usuario']),
        ], 201);
    }

    public function update(Request $request, string|int $id): JsonResponse
    {
        Gate::authorize('editar');

        $innovacion = Innovacion::find($id);

        if (!$innovacion) {
            return response()->json([
                'success' => false,
                'message' => "No se puede actualizar. La innovacion con ID #{$id} no existe.",
            ], 404);
        }

        $validated = $request->validate([
            'categoria_id'    => ['sometimes', 'required', 'integer', 'exists:categorias,id'],
            'titulo'          => ['sometimes', 'required', 'string', 'min:5', 'max:200'],
            'descripcion'     => ['sometimes', 'required', 'string', 'min:20', 'max:2000'],
            'meta_financiera' => ['sometimes', 'required', 'numeric', 'min:1000', 'max:10000000'],
            'estado'          => ['nullable', 'string', 'in:borrador,activo,financiado,cancelado'],
            'fecha_inicio'    => ['sometimes', 'required', 'date'],
            'fecha_fin'       => ['sometimes', 'required', 'date', 'after_or_equal:fecha_inicio'],
            'imagen'          => ['nullable', 'file', 'image', 'mimes:jpeg,png,webp,jpg', 'max:2048'],
            'imagen_portada'  => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($request, $validated, $innovacion) {
            $campos = array_intersect_key($validated, array_flip([
                'categoria_id', 'titulo', 'descripcion', 'meta_financiera', 'estado', 'fecha_inicio', 'fecha_fin', 'imagen_portada'
            ]));

            $innovacion->update($campos);

            if ($request->hasFile('imagen')) {
                if ($innovacion->imagen_portada) {
                    $rutaRelativa = str_replace('/storage/', '', $innovacion->imagen_portada);
                    if (Storage::disk('public')->exists($rutaRelativa)) {
                        Storage::disk('public')->delete($rutaRelativa);
                    }
                }

                $archivo = $request->file('imagen');
                $extension = $archivo->getClientOriginalExtension();
                $nombreArchivo = "innovacion_{$innovacion->id}.{$extension}";
                $archivo->storeAs('innovaciones', $nombreArchivo, 'public');

                $innovacion->update([
                    'imagen_portada' => "/storage/innovaciones/{$nombreArchivo}",
                ]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Innovacion actualizada exitosamente.',
            'data'    => $innovacion->fresh(['categoria', 'usuario']),
        ]);
    }

    public function destroy(string|int $id): JsonResponse
    {
        Gate::authorize('eliminar');

        $innovacion = Innovacion::find($id);

        if (!$innovacion) {
            return response()->json([
                'success' => false,
                'message' => "No se puede eliminar. El registro con ID #{$id} no existe.",
            ], 404);
        }

        $innovacion->delete();

        return response()->json([
            'success' => true,
            'message' => 'Innovacion enviada a la papelera exitosamente.',
        ]);
    }

    public function trash(): JsonResponse
    {
        Gate::authorize('eliminar');

        $innovaciones = Innovacion::onlyTrashed()
            ->with(['categoria', 'usuario', 'etiquetas'])
            ->orderBy('deleted_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data'    => $innovaciones,
        ]);
    }

    public function restore(string|int $id): JsonResponse
    {
        Gate::authorize('eliminar');

        $innovacion = Innovacion::onlyTrashed()->find($id);

        if (!$innovacion) {
            return response()->json([
                'success' => false,
                'message' => "No se encontro el registro con ID #{$id} en la papelera.",
            ], 404);
        }

        $innovacion->restore();

        return response()->json([
            'success' => true,
            'message' => 'Innovacion restaurada exitosamente.',
            'data'    => $innovacion,
        ]);
    }

    public function forceDelete(string|int $id): JsonResponse
    {
        Gate::authorize('eliminar');

        $innovacion = Innovacion::onlyTrashed()->find($id);

        if (!$innovacion) {
            return response()->json([
                'success' => false,
                'message' => "El registro no existe en la papelera o ya fue eliminado definitivamente.",
            ], 404);
        }

        $tieneDependencias = $innovacion->contribuciones()->exists()
            || $innovacion->comentarios()->exists()
            || $innovacion->hitosFinancieros()->exists()
            || $innovacion->carritoDetalles()->exists()
            || $innovacion->listaDeseos()->exists();

        if ($tieneDependencias) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar definitivamente una innovacion con dependencias activas.',
            ], 422);
        }

        DB::transaction(function () use ($innovacion) {
            if ($innovacion->imagen_portada) {
                $rutaRelativa = str_replace('/storage/', '', $innovacion->imagen_portada);
                if (Storage::disk('public')->exists($rutaRelativa)) {
                    Storage::disk('public')->delete($rutaRelativa);
                }
            }

            $innovacion->etiquetas()->detach();
            $innovacion->forceDelete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Innovacion y su imagen asociada fueron eliminadas definitivamente.',
        ]);
    }
}
