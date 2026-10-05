<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\JsonResponse;

class CategoriaController extends Controller
{
    public function index(): JsonResponse
    {
        $categorias = Categoria::where('estado', 'activa')
            ->withCount('innovaciones')
            ->orderBy('id')
            ->paginate();

        return $this->respuesta(true, 'Categorias listadas correctamente.', $categorias, 200);
    }

    public function show(int $id): JsonResponse
    {
        $categoria = Categoria::where('estado', 'activa')
            ->withCount('innovaciones')
            ->find($id);

        if (! $categoria) {
            return $this->respuesta(false, 'La categoria no existe.', null, 404);
        }

        return $this->respuesta(true, 'Categoria encontrada correctamente.', $categoria, 200);
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
