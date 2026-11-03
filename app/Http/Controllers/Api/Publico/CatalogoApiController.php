<?php

namespace App\Http\Controllers\Api\Publico;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Innovacion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CatalogoApiController extends Controller
{
    public function innovaciones(Request $request): JsonResponse
    {
        $query = Innovacion::with(['categoria', 'usuario'])
            ->whereIn('estado', ['activo', 'financiado']);

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->query('categoria_id'));
        }

        if ($request->filled('search')) {
            $termino = $request->query('search');
            $query->where(function ($q) use ($termino) {
                $q->where('titulo', 'like', "%{$termino}%")
                  ->orWhere('descripcion', 'like', "%{$termino}%");
            });
        }

        $perPage = (int) $request->query('per_page', 9);
        $innovaciones = $query->latest('id')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => $innovaciones,
        ]);
    }

    public function detalleInnovacion(string|int $id): JsonResponse
    {
        $innovacion = Innovacion::with([
            'categoria',
            'usuario',
            'etiquetas',
            'hitosFinancieros',
            'comentarios.usuario',
        ])
        ->whereIn('estado', ['activo', 'financiado'])
        ->find($id);

        if (!$innovacion) {
            return response()->json([
                'success' => false,
                'message' => "La iniciativa solicitada no existe o no se encuentra disponible.",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $innovacion,
        ]);
    }

    public function categorias(): JsonResponse
    {
        $categorias = Categoria::where('estado', 'activa')
            ->withCount('innovaciones')
            ->orderBy('nombre')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $categorias,
        ]);
    }
}
