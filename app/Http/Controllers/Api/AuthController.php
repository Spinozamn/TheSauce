<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function login(Request $solicitud): JsonResponse
    {
        $validador = Validator::make($solicitud->all(), [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'required' => 'El campo :attribute es obligatorio.',
            'email' => 'El campo correo debe ser una direccion valida.',
            'string' => 'El campo :attribute debe ser texto.',
        ], [
            'email' => 'correo',
            'password' => 'contrasena',
        ]);

        if ($validador->fails()) {
            return $this->respuesta(false, 'Los datos enviados no son validos.', $validador->errors(), 422);
        }

        $datos = $validador->validated();
        $usuario = Usuario::where('email', $datos['email'])->first();

        if (
            ! $usuario
            || ! Hash::check($datos['password'], $usuario->password)
        ) {
            return $this->respuesta(false, 'Credenciales no validas.', null, 401);
        }

        $rol = Rol::find($usuario->rol_id);

        if (mb_strtolower($rol->nombre ?? '') !== 'administrador') {
            return $this->respuesta(false, 'Credenciales no validas.', null, 401);
        }

        $token = $usuario->createToken('api-token');

        return $this->respuesta(true, 'Inicio de sesion correcto.', [
            'token' => $token->plainTextToken,
            'token_type' => 'Bearer',
            'usuario' => $this->datosUsuario($usuario, $rol),
        ], 200);
    }

    public function me(Request $solicitud): JsonResponse
    {
        $usuario = $this->usuarioAutenticado($solicitud);

        if (! $usuario) {
            return $this->respuesta(false, 'No hay una sesion autenticada.', null, 401);
        }

        return $this->respuesta(true, 'Usuario autenticado.', $this->datosUsuario($usuario, Rol::find($usuario->rol_id)), 200);
    }

    public function logout(Request $solicitud): JsonResponse
    {
        $usuario = $this->usuarioAutenticado($solicitud);

        if (! $usuario) {
            return $this->respuesta(false, 'No hay una sesion autenticada.', null, 401);
        }

        $tokenPlano = $solicitud->bearerToken();

        if ($tokenPlano !== null) {
            PersonalAccessToken::findToken($tokenPlano)?->delete();
        }

        return $this->respuesta(true, 'Sesion cerrada correctamente.', null, 200);
    }

    private function datosUsuario(Usuario $usuario, ?Rol $rol): object
    {
        return (object) [
            'id' => $usuario->id,
            'nombre' => $usuario->nombre,
            'apellido_paterno' => $usuario->apellido_paterno,
            'apellido_materno' => $usuario->apellido_materno,
            'email' => $usuario->email,
            'rol' => $rol,
            'permissions' => [],
        ];
    }

    private function usuarioAutenticado(Request $solicitud): ?Usuario
    {
        $usuarioAutenticado = $solicitud->user();
        $tokenPlano = $solicitud->bearerToken();

        if (! $usuarioAutenticado || $tokenPlano === null) {
            return null;
        }

        $token = PersonalAccessToken::findToken($tokenPlano);
        $usuario = $token?->tokenable;

        if (! $usuario instanceof Usuario || $usuario->getAuthIdentifier() !== $usuarioAutenticado->getAuthIdentifier()) {
            return null;
        }

        return $usuario;
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
