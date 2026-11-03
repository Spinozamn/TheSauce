<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Administrador;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
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

        if ($admin) {
            if (!Hash::check($credenciales['password'], $admin->password)) {
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
                'message'    => 'Autenticacion exitosa como Administrador.',
                'token'      => $token,
                'token_type' => 'Bearer',
                'usuario'    => [
                    'id'       => $admin->id,
                    'nombre'   => $admin->nombre,
                    'usuario'  => $admin->usuario,
                    'email'    => $admin->email,
                    'tipo'     => 'administrador',
                    'nivel'    => $admin->nivel,
                    'activo'   => (bool) $admin->activo,
                    'permisos' => $this->obtenerPermisos($admin),
                ],
            ]);
        }

        $user = User::where('email', $identificador)
            ->orWhere('name', $identificador)
            ->first();

        if ($user) {
            if (!Hash::check($credenciales['password'], $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Las credenciales proporcionadas son incorrectas.',
                ], 401);
            }

            $token = $user->createToken('user-api-token', ['user:access'])->plainTextToken;

            return response()->json([
                'success'    => true,
                'message'    => 'Autenticacion exitosa como Usuario.',
                'token'      => $token,
                'token_type' => 'Bearer',
                'usuario'    => [
                    'id'       => $user->id,
                    'nombre'   => $user->name,
                    'usuario'  => null,
                    'email'    => $user->email,
                    'tipo'     => 'usuario',
                    'nivel'    => 0,
                    'activo'   => true,
                    'permisos' => $this->obtenerPermisos($user),
                ],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Las credenciales proporcionadas son incorrectas.',
        ], 401);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Token revocado y sesion cerrada exitosamente.',
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        $actor = $request->user();
        $esAdmin = $actor instanceof Administrador;

        return response()->json([
            'success' => true,
            'usuario' => [
                'id'       => $actor->id,
                'nombre'   => $esAdmin ? $actor->nombre : $actor->name,
                'usuario'  => $esAdmin ? $actor->usuario : null,
                'email'    => $actor->email,
                'tipo'     => $esAdmin ? 'administrador' : 'usuario',
                'nivel'    => $esAdmin ? $actor->nivel : 0,
                'activo'   => $esAdmin ? (bool) $actor->activo : true,
                'permisos' => $this->obtenerPermisos($actor),
            ],
            'token_abilities' => $actor->currentAccessToken()->abilities,
        ]);
    }

    private function obtenerPermisos($actor): array
    {
        return [
            'consultar'           => Gate::forUser($actor)->allows('consultar'),
            'crear'               => Gate::forUser($actor)->allows('crear'),
            'editar'              => Gate::forUser($actor)->allows('editar'),
            'eliminar'            => Gate::forUser($actor)->allows('eliminar'),
            'administrar_cuentas' => Gate::forUser($actor)->allows('administrar-cuentas'),
        ];
    }
}
