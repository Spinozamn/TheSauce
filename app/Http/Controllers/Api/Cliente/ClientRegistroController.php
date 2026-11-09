<?php

namespace App\Http\Controllers\Api\Cliente;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cliente\ClientRegistroRequest;
use App\Http\Resources\Cliente\ClientInnovacionResource;
use App\Models\Innovacion;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ClientRegistroController extends Controller
{
    public function catalogo(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 10);

        $innovaciones = Innovacion::with(['categoria'])
            ->whereIn('estado', ['activo', 'borrador'])
            ->latest('id')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Catalogo general de recursos consultado exitosamente.',
            'data'    => ClientInnovacionResource::collection($innovaciones)->response()->getData(true),
            'errors'  => null,
        ], 200);
    }

    public function catalogoDetalle(string|int $id): JsonResponse
    {
        $innovacion = Innovacion::with(['categoria'])->find($id);

        if (!$innovacion) {
            return response()->json([
                'success' => false,
                'message' => 'El recurso del catalogo solicitado no existe.',
                'data'    => null,
                'errors'  => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detalle del recurso del catalogo consultado exitosamente.',
            'data'    => new ClientInnovacionResource($innovacion),
            'errors'  => null,
        ], 200);
    }

    public function index(Request $request): JsonResponse
    {
        $actor = $request->user();
        $usuario = Usuario::where('email', $actor->email)->first();

        if (!$usuario) {
            return response()->json([
                'success' => true,
                'message' => 'Listado de registros propios consultado exitosamente.',
                'data'    => [
                    'data'  => [],
                    'links' => [],
                    'meta'  => ['total' => 0],
                ],
                'errors'  => null,
            ], 200);
        }

        $perPage = (int) $request->query('per_page', 10);

        $registros = Innovacion::where('usuario_id', $usuario->id)
            ->with(['categoria'])
            ->latest('id')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Listado de registros propios consultado exitosamente.',
            'data'    => ClientInnovacionResource::collection($registros)->response()->getData(true),
            'errors'  => null,
        ], 200);
    }

    public function store(ClientRegistroRequest $request): JsonResponse
    {
        $actor = $request->user();

        $usuario = Usuario::firstOrCreate(
            ['email' => $actor->email],
            [
                'rol_id'           => 1,
                'nombre'           => $actor->name,
                'apellido_paterno' => 'Cliente',
                'email'            => $actor->email,
                'password'         => $actor->password,
                'estado'           => 'activo',
            ]
        );

        $datos = $request->validated();
        $datos['usuario_id'] = $usuario->id;
        $datos['estado'] = 'borrador';
        $datos['monto_recaudado'] = 0.00;
        $datos['imagen_portada'] = null;

        $registro = Innovacion::create($datos);

        return response()->json([
            'success' => true,
            'message' => 'Registro propio creado exitosamente bajo el usuario autenticado.',
            'data'    => new ClientInnovacionResource($registro->load('categoria')),
            'errors'  => null,
        ], 201);
    }

    public function show(Request $request, string|int $id): JsonResponse
    {
        $registro = Innovacion::with(['categoria'])->find($id);

        if (!$registro) {
            return response()->json([
                'success' => false,
                'message' => 'El registro solicitado no existe.',
                'data'    => null,
                'errors'  => null,
            ], 404);
        }

        $response = Gate::forUser($request->user())->inspect('view', $registro);

        if ($response->denied()) {
            return response()->json([
                'success' => false,
                'message' => $response->message(),
                'data'    => null,
                'errors'  => 'Acceso denegado por politica de propiedad de datos.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detalle del registro propio consultado exitosamente.',
            'data'    => new ClientInnovacionResource($registro),
            'errors'  => null,
        ], 200);
    }

    public function update(ClientRegistroRequest $request, string|int $id): JsonResponse
    {
        $registro = Innovacion::find($id);

        if (!$registro) {
            return response()->json([
                'success' => false,
                'message' => 'El registro solicitado no existe.',
                'data'    => null,
                'errors'  => null,
            ], 404);
        }

        $response = Gate::forUser($request->user())->inspect('update', $registro);

        if ($response->denied()) {
            return response()->json([
                'success' => false,
                'message' => $response->message(),
                'data'    => null,
                'errors'  => 'Acceso denegado por politica de propiedad de datos.',
            ], 403);
        }

        $registro->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Registro propio actualizado exitosamente.',
            'data'    => new ClientInnovacionResource($registro->fresh('categoria')),
            'errors'  => null,
        ], 200);
    }

    public function destroy(Request $request, string|int $id): JsonResponse
    {
        $registro = Innovacion::find($id);

        if (!$registro) {
            return response()->json([
                'success' => false,
                'message' => 'El registro solicitado no existe.',
                'data'    => null,
                'errors'  => null,
            ], 404);
        }

        $response = Gate::forUser($request->user())->inspect('delete', $registro);

        if ($response->denied()) {
            return response()->json([
                'success' => false,
                'message' => $response->message(),
                'data'    => null,
                'errors'  => 'Acceso denegado por politica de propiedad de datos.',
            ], 403);
        }

        $registro->delete();

        return response()->json([
            'success' => true,
            'message' => 'Registro propio cancelado y eliminado exitosamente.',
            'data'    => null,
            'errors'  => null,
        ], 200);
    }
}
