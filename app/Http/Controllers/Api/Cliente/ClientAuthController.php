<?php

namespace App\Http\Controllers\Api\Cliente;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cliente\ClientLoginRequest;
use App\Http\Requests\Cliente\ClientPerfilRequest;
use App\Http\Requests\Cliente\ClientRegisterRequest;
use App\Http\Resources\Cliente\ClientPerfilResource;
use App\Models\Rol;
use App\Models\User;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClientAuthController extends Controller
{
    public function register(ClientRegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'name'     => $validated['nombre'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $rolBase = Rol::firstOrCreate(
            ['nombre' => 'Colaborador'],
            ['descripcion' => 'Perfil base de usuario registrado']
        );

        Usuario::firstOrCreate(
            ['email' => $validated['email']],
            [
                'rol_id'           => $rolBase->id,
                'nombre'           => $validated['nombre'],
                'apellido_paterno' => 'Cliente',
                'email'            => $validated['email'],
                'password'         => Hash::make($validated['password']),
                'estado'           => 'activo',
            ]
        );

        $token = $user->createToken('client-token', ['cliente:acceso'])->plainTextToken;

        return response()->json([
            'success'    => true,
            'message'    => 'Registro de cliente completado exitosamente.',
            'token'      => $token,
            'token_type' => 'Bearer',
            'data'       => new ClientPerfilResource($user),
            'errors'     => null,
        ], 201);
    }

    public function login(ClientLoginRequest $request): JsonResponse
    {
        $credenciales = $request->validated();

        $user = User::where('email', $credenciales['email'])->first();

        if (!$user || !Hash::check($credenciales['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Las credenciales proporcionadas son incorrectas.',
                'data'    => null,
                'errors'  => null,
            ], 401);
        }

        $token = $user->createToken('client-token', ['cliente:acceso'])->plainTextToken;

        return response()->json([
            'success'    => true,
            'message'    => 'Autenticacion de cliente exitosa.',
            'token'      => $token,
            'token_type' => 'Bearer',
            'data'       => new ClientPerfilResource($user),
            'errors'     => null,
        ], 200);
    }

    public function perfil(Request $request): JsonResponse
    {
        $actor = $request->user();

        return response()->json([
            'success' => true,
            'message' => 'Perfil de cliente consultado exitosamente.',
            'data'    => new ClientPerfilResource($actor),
            'errors'  => null,
        ], 200);
    }

    public function actualizarPerfil(ClientPerfilRequest $request): JsonResponse
    {
        $actor = $request->user();
        $validated = $request->validated();

        if (isset($validated['nombre'])) {
            $actor->name = $validated['nombre'];
        }

        if (isset($validated['email'])) {
            $actor->email = $validated['email'];
        }

        $actor->save();

        $usuario = Usuario::where('email', $actor->getOriginal('email'))->first();
        if ($usuario) {
            if (isset($validated['nombre'])) {
                $usuario->nombre = $validated['nombre'];
            }
            if (isset($validated['email'])) {
                $usuario->email = $validated['email'];
            }
            if (array_key_exists('telefono', $validated)) {
                $usuario->telefono = $validated['telefono'];
            }
            $usuario->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Perfil de cliente actualizado exitosamente.',
            'data'    => new ClientPerfilResource($actor->fresh()),
            'errors'  => null,
        ], 200);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sesion cerrada exitosamente.',
            'data'    => null,
            'errors'  => null,
        ], 200);
    }
}
