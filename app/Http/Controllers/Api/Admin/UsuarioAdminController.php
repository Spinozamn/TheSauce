<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminUsuarioEstadoRequest;
use App\Http\Resources\Admin\UsuarioAdminResource;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UsuarioAdminController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Usuario::with('rol');

        if ($request->filled('search')) {
            $termino = $request->query('search');
            $query->where(function ($q) use ($termino) {
                $q->where('nombre', 'like', "%{$termino}%")
                  ->orWhere('apellido_paterno', 'like', "%{$termino}%")
                  ->orWhere('email', 'like', "%{$termino}%");
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->query('estado'));
        }

        if ($request->filled('rol_id')) {
            $query->where('rol_id', $request->query('rol_id'));
        }

        $perPage = (int) $request->query('per_page', 10);
        $usuarios = $query->orderBy('id', 'asc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Listado general de usuarios consultado exitosamente.',
            'data'    => UsuarioAdminResource::collection($usuarios)->response()->getData(true),
            'errors'  => null,
        ]);
    }

    public function show($id): JsonResponse
    {
        $usuario = Usuario::with('rol')->find($id);

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'El usuario solicitado no existe.',
                'data'    => null,
                'errors'  => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detalle del usuario consultado exitosamente.',
            'data'    => new UsuarioAdminResource($usuario),
            'errors'  => null,
        ]);
    }

    public function cambiarEstado(AdminUsuarioEstadoRequest $request, $id): JsonResponse
    {
        $usuario = Usuario::with('rol')->find($id);

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'El usuario solicitado no existe.',
                'data'    => null,
                'errors'  => null,
            ], 404);
        }

        $datos = $request->validated();
        $usuario->estado = $datos['estado'];

        if (isset($datos['rol_id'])) {
            $usuario->rol_id = $datos['rol_id'];
        }

        $usuario->save();
        $usuario->load('rol');

        return response()->json([
            'success' => true,
            'message' => 'Estado del usuario actualizado exitosamente.',
            'data'    => new UsuarioAdminResource($usuario),
            'errors'  => null,
        ]);
    }
}
