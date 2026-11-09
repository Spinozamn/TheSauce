<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminInnovacionRequest;
use App\Http\Resources\Admin\InnovacionAdminResource;
use App\Models\Innovacion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class InnovacionAdminController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        Gate::authorize('viewAny', Innovacion::class);

        $query = Innovacion::withTrashed()->with(['categoria', 'usuario']);

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

        if ($request->query('solo_papelera') === 'true' || $request->query('solo_papelera') === '1') {
            $query->onlyTrashed();
        }

        $perPage = (int) $request->query('per_page', 10);
        $innovaciones = $query->orderBy('id', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Listado administrativo de recursos consultado exitosamente.',
            'data'    => InnovacionAdminResource::collection($innovaciones)->response()->getData(true),
            'errors'  => null,
        ]);
    }

    public function show($id): JsonResponse
    {
        $innovacion = Innovacion::withTrashed()->with(['categoria', 'usuario'])->find($id);

        if (!$innovacion) {
            return response()->json([
                'success' => false,
                'message' => 'El recurso administrativo solicitado no existe.',
                'data'    => null,
                'errors'  => null,
            ], 404);
        }

        Gate::authorize('view', $innovacion);

        return response()->json([
            'success' => true,
            'message' => 'Detalle del recurso consultado exitosamente.',
            'data'    => new InnovacionAdminResource($innovacion),
            'errors'  => null,
        ]);
    }

    public function store(AdminInnovacionRequest $request): JsonResponse
    {
        Gate::authorize('create', Innovacion::class);

        $datos = $request->validated();
        $datos['usuario_id'] = $datos['usuario_id'] ?? 1;
        $datos['monto_recaudado'] = $datos['monto_recaudado'] ?? 0.00;
        $datos['estado'] = $datos['estado'] ?? 'activo';

        $innovacion = Innovacion::create($datos);
        $innovacion->load(['categoria', 'usuario']);

        return response()->json([
            'success' => true,
            'message' => 'Recurso registrado exitosamente desde el area administrativa.',
            'data'    => new InnovacionAdminResource($innovacion),
            'errors'  => null,
        ], 201);
    }

    public function update(AdminInnovacionRequest $request, $id): JsonResponse
    {
        $innovacion = Innovacion::withTrashed()->find($id);

        if (!$innovacion) {
            return response()->json([
                'success' => false,
                'message' => 'El recurso administrativo solicitado no existe.',
                'data'    => null,
                'errors'  => null,
            ], 404);
        }

        Gate::authorize('update', $innovacion);

        $innovacion->update($request->validated());
        $innovacion->load(['categoria', 'usuario']);

        return response()->json([
            'success' => true,
            'message' => 'Recurso actualizado exitosamente desde el area administrativa.',
            'data'    => new InnovacionAdminResource($innovacion),
            'errors'  => null,
        ]);
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        $innovacion = Innovacion::withTrashed()->find($id);

        if (!$innovacion) {
            return response()->json([
                'success' => false,
                'message' => 'El recurso administrativo solicitado no existe.',
                'data'    => null,
                'errors'  => null,
            ], 404);
        }

        $esFisico = $request->query('forzar') === 'true' || $request->query('forzar') === '1';

        if ($esFisico) {
            Gate::authorize('forceDelete', $innovacion);
            $innovacion->forceDelete();

            return response()->json([
                'success' => true,
                'message' => 'Recurso eliminado definitivamente de la base de datos.',
                'data'    => null,
                'errors'  => null,
            ]);
        }

        Gate::authorize('delete', $innovacion);
        $innovacion->delete();

        return response()->json([
            'success' => true,
            'message' => 'Recurso enviado a la papelera de reciclaje exitosamente.',
            'data'    => null,
            'errors'  => null,
        ]);
    }

    public function restore($id): JsonResponse
    {
        $innovacion = Innovacion::onlyTrashed()->find($id);

        if (!$innovacion) {
            return response()->json([
                'success' => false,
                'message' => 'El recurso no se encuentra en la papelera de reciclaje.',
                'data'    => null,
                'errors'  => null,
            ], 404);
        }

        Gate::authorize('restore', $innovacion);
        $innovacion->restore();
        $innovacion->load(['categoria', 'usuario']);

        return response()->json([
            'success' => true,
            'message' => 'Recurso restaurado exitosamente de la papelera.',
            'data'    => new InnovacionAdminResource($innovacion),
            'errors'  => null,
        ]);
    }
}
