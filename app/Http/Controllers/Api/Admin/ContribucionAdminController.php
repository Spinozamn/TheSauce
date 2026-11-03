<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contribucion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ContribucionAdminController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        Gate::authorize('consultar');

        $query = Contribucion::with(['usuario', 'innovacion.categoria']);

        if ($request->filled('innovacion_id')) {
            $query->where('innovacion_id', $request->query('innovacion_id'));
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->query('estado'));
        }

        $perPage = (int) $request->query('per_page', 15);
        $contribuciones = $query->latest('id')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => $contribuciones,
        ]);
    }

    public function show(string|int $id): JsonResponse
    {
        Gate::authorize('consultar');

        $contribucion = Contribucion::with(['usuario', 'innovacion.categoria'])->find($id);

        if (!$contribucion) {
            return response()->json([
                'success' => false,
                'message' => "La contribucion con ID #{$id} no existe.",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $contribucion,
        ]);
    }
}
