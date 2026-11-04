<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $categorias = Categoria::withCount('innovaciones')->orderBy('id', 'asc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Coleccion de categorias consultada exitosamente',
            'data'    => $categorias,
            'errors'  => null,
        ], 200);
    }

    public function show(string|int $id): JsonResponse
    {
        $categoria = Categoria::withCount('innovaciones')->find($id);

        if (!$categoria) {
            return response()->json([
                'success' => false,
                'message' => 'La categoria solicitada no existe',
                'data'    => null,
                'errors'  => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Categoria consultada exitosamente',
            'data'    => $categoria,
            'errors'  => null,
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nombre'      => 'required|string|max:100|unique:categorias,nombre',
            'descripcion' => 'nullable|string|max:500',
            'icono'       => 'nullable|string|max:50',
            'estado'      => 'nullable|string|in:activa,inactiva',
        ], [
            'nombre.required' => 'El nombre de la categoria es obligatorio.',
            'nombre.unique'   => 'Ya existe una categoria con ese nombre.',
            'nombre.max'      => 'El nombre no debe superar los 100 caracteres.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Los datos enviados no son validos',
                'data'    => null,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $categoria = Categoria::create([
            'nombre'      => $request->input('nombre'),
            'descripcion' => $request->input('descripcion'),
            'icono'       => $request->input('icono'),
            'estado'      => $request->input('estado', 'activa'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Categoria registrada exitosamente',
            'data'    => $categoria,
            'errors'  => null,
        ], 201);
    }

    public function update(Request $request, string|int $id): JsonResponse
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json([
                'success' => false,
                'message' => 'La categoria solicitada no existe',
                'data'    => null,
                'errors'  => null,
            ], 404);
        }

        $isPut = $request->isMethod('put');

        $validator = Validator::make($request->all(), [
            'nombre'      => ($isPut ? 'required|' : 'sometimes|') . 'string|max:100|unique:categorias,nombre,' . $id,
            'descripcion' => 'nullable|string|max:500',
            'icono'       => 'nullable|string|max:50',
            'estado'      => 'nullable|string|in:activa,inactiva',
        ], [
            'nombre.required' => 'El nombre de la categoria es obligatorio.',
            'nombre.unique'   => 'Ya existe una categoria con ese nombre.',
            'nombre.max'      => 'El nombre no debe superar los 100 caracteres.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Los datos enviados no son validos',
                'data'    => null,
                'errors'  => $validator->errors(),
            ], 422);
        }

        $categoria->fill($request->only(['nombre', 'descripcion', 'icono', 'estado']));
        $categoria->save();

        return response()->json([
            'success' => true,
            'message' => 'Categoria actualizada exitosamente',
            'data'    => $categoria,
            'errors'  => null,
        ], 200);
    }

    public function destroy(string|int $id): JsonResponse
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json([
                'success' => false,
                'message' => 'La categoria solicitada no existe',
                'data'    => null,
                'errors'  => null,
            ], 404);
        }

        if ($categoria->innovaciones()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No es posible eliminar la categoria porque tiene innovaciones vinculadas',
                'data'    => null,
                'errors'  => null,
            ], 400);
        }

        $categoria->delete();

        return response()->json([
            'success' => true,
            'message' => 'Categoria eliminada exitosamente',
            'data'    => null,
            'errors'  => null,
        ], 200);
    }
}
