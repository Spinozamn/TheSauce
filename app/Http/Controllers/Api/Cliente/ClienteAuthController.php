<?php

namespace App\Http\Controllers\Api\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use App\Models\User;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClienteAuthController extends Controller
{
    public function registro(Request $request): JsonResponse
    {
        if ($request->has('name') && !$request->has('nombre')) {
            $request->merge(['nombre' => $request->input('name')]);
        }

        $validated = $request->validate([
            'nombre'   => ['required', 'string', 'min:3', 'max:100'],
            'email'    => ['required', 'string', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'nombre.required'   => 'El nombre es obligatorio.',
            'nombre.min'        => 'El nombre debe contener al menos 3 caracteres.',
            'email.required'    => 'El correo electronico es obligatorio.',
            'email.email'       => 'El formato del correo electronico no es valido.',
            'email.unique'      => 'El correo electronico ya se encuentra registrado.',
            'password.required' => 'La contrasena es obligatoria.',
            'password.min'      => 'La contrasena debe tener al menos 8 caracteres.',
            'password.confirmed'=> 'La confirmacion de contrasena no coincide.',
        ]);

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
                'apellido_paterno' => 'Usuario',
                'email'            => $validated['email'],
                'password'         => Hash::make($validated['password']),
                'estado'           => 'activo',
            ]
        );

        $token = $user->createToken('cliente-token', ['cliente:acceso'])->plainTextToken;

        return response()->json([
            'success'    => true,
            'message'    => 'Registro completado exitosamente.',
            'token'      => $token,
            'token_type' => 'Bearer',
            'cliente'    => [
                'id'     => $user->id,
                'nombre' => $user->name,
                'email'  => $user->email,
            ],
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $credenciales = $request->validate([
            'email'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'email.required'    => 'El correo electronico es obligatorio.',
            'password.required' => 'La contrasena es obligatoria.',
        ]);

        $user = User::where('email', $credenciales['email'])->first();

        if (!$user || !Hash::check($credenciales['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Las credenciales proporcionadas son incorrectas.',
            ], 401);
        }

        $token = $user->createToken('cliente-token', ['cliente:acceso'])->plainTextToken;

        return response()->json([
            'success'    => true,
            'message'    => 'Autenticacion de cliente exitosa.',
            'token'      => $token,
            'token_type' => 'Bearer',
            'cliente'    => [
                'id'     => $user->id,
                'nombre' => $user->name,
                'email'  => $user->email,
            ],
        ]);
    }

    public function perfil(Request $request): JsonResponse
    {
        $actor = $request->user();

        if (!$actor instanceof User) {
            return response()->json([
                'success' => false,
                'message' => 'Acceso restringido a clientes registrados.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'cliente' => [
                'id'     => $actor->id,
                'nombre' => $actor->name,
                'email'  => $actor->email,
            ],
        ]);
    }

    public function actualizarPerfil(Request $request): JsonResponse
    {
        $actor = $request->user();

        if (!$actor instanceof User) {
            return response()->json([
                'success' => false,
                'message' => 'Acceso restringido a clientes registrados.',
            ], 403);
        }

        if ($request->has('name') && !$request->has('nombre')) {
            $request->merge(['nombre' => $request->input('name')]);
        }

        $validated = $request->validate([
            'nombre' => ['sometimes', 'required', 'string', 'min:3', 'max:100'],
            'email'  => ['sometimes', 'required', 'string', 'email', 'max:150', 'unique:users,email,' . $actor->id],
        ]);

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
            $usuario->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Perfil actualizado exitosamente.',
            'cliente' => [
                'id'     => $actor->id,
                'nombre' => $actor->name,
                'email'  => $actor->email,
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sesion cerrada exitosamente.',
        ]);
    }
}
