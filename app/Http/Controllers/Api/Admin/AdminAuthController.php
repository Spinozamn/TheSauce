<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Administrador;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credenciales = $request->validate([
            'usuario'  => ['nullable', 'string'],
            'email'    => ['nullable', 'string'],
            'password' => ['required', 'string'],
        ], [
            'password.required' => 'El password es obligatorio.',
        ]);

        $identificador = $credenciales['usuario'] ?? $credenciales['email'] ?? null;

        if (!$identificador) {
            return response()->json([
                'success' => false,
                'message' => 'Debe ingresar un usuario o correo electronico.',
            ], 422);
        }

        $admin = Administrador::where('usuario', $identificador)
            ->orWhere('email', $identificador)
            ->first();

        if (!$admin || !Hash::check($credenciales['password'], $admin->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Las credenciales proporcionadas son incorrectas.',
            ], 401);
        }

        if (!$admin->activo) {
            return response()->json([
                'success' => false,
                'message' => 'Su cuenta se encuentra inactiva o deshabilitada. Contacte a soporte.',
            ], 403);
        }

        $habilidades = match ($admin->nivel) {
            1 => ['innovaciones:consultar'],
            2 => ['innovaciones:consultar', 'innovaciones:crear', 'innovaciones:editar'],
            default => ['*'],
        };

        $token = $admin->createToken('admin-api-token', $habilidades)->plainTextToken;

        return response()->json([
            'success'    => true,
            'message'    => 'Autenticacion administrativa exitosa.',
            'token'      => $token,
            'token_type' => 'Bearer',
            'administrador' => [
                'id'       => $admin->id,
                'nombre'   => $admin->nombre,
                'usuario'  => $admin->usuario,
                'email'    => $admin->email,
                'nivel'    => $admin->nivel,
                'activo'   => (bool) $admin->activo,
            ],
        ]);
    }

    public function perfil(Request $request): JsonResponse
    {
        $actor = $request->user();

        if (!$actor instanceof Administrador || !$actor->activo) {
            return response()->json([
                'success' => false,
                'message' => 'Acceso restringido a administradores activos.',
            ], 403);
        }

        return response()->json([
            'success'       => true,
            'administrador' => [
                'id'       => $actor->id,
                'nombre'   => $actor->nombre,
                'usuario'  => $actor->usuario,
                'email'    => $actor->email,
                'nivel'    => $actor->nivel,
                'activo'   => (bool) $actor->activo,
                'permisos' => [
                    'consultar' => Gate::forUser($actor)->allows('consultar'),
                    'crear'     => Gate::forUser($actor)->allows('crear'),
                    'editar'    => Gate::forUser($actor)->allows('editar'),
                    'eliminar'  => Gate::forUser($actor)->allows('eliminar'),
                ],
            ],
            'token_abilities' => $actor->currentAccessToken()->abilities,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sesion administrativa cerrada exitosamente.',
        ]);
    }
}
