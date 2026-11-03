<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CategoriaAdminController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        Gate::authorize('consultar');

        $categorias = Categoria::withCount('innovaciones')
            ->orderBy('id', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data'    => $categorias,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        Gate::authorize('crear');

        $validated = $request->validate([
            'nombre'      => ['required', 'string', 'min:3', 'max:100', 'unique:categorias,nombre'],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'icono'       => ['nullable', 'string', 'max:50'],
            'estado'      => ['nullable', 'string', 'in:activa,inactiva'],
        ], [
            'nombre.required' => 'El nombre de la categoria es obligatorio.',
            'nombre.unique'   => 'Ya existe una categoria con ese nombre.',
        ]);

        $categoria = Categoria::create([
            'nombre'      => $validated['nombre'],
            'descripcion' => $validated['descripcion'] ?? null,
            'icono'       => $validated['icono'] ?? null,
            'estado'      => $validated['estado'] ?? 'activa',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Categoria creada exitosamente.',
            'data'    => $categoria,
        ], 201);
    }

    public function update(Request $request, string|int $id): JsonResponse
    {
        Gate::authorize('editar');

        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json([
                'success' => false,
                'message' => "La categoria con ID #{$id} no existe.",
            ], 404);
        }

        $validated = $request->validate([
            'nombre'      => ['sometimes', 'required', 'string', 'min:3', 'max:100', 'unique:categorias,nombre,' . $id],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'icono'       => ['nullable', 'string', 'max:50'],
            'estado'      => ['nullable', 'string', 'in:activa,inactiva'],
        ]);

        $categoria->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Categoria actualizada exitosamente.',
            'data'    => $categoria,
        ]);
    }

    public function destroy(string|int $id): JsonResponse
    {
        Gate::authorize('eliminar');

        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json([
                'success' => false,
                'message' => "La categoria con ID #{$id} no existe.",
            ], 404);
        }

        if ($categoria->innovaciones()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'No es posible eliminar una categoria con iniciativas vinculadas.',
            ], 422);
        }

        $categoria->delete();

        return response()->json([
            'success' => true,
            'message' => 'Categoria eliminada exitosamente.',
        ]);
    }
}
