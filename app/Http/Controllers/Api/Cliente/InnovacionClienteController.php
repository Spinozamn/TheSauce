<?php

namespace App\Http\Controllers\Api\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Innovacion;
use App\Models\User;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InnovacionClienteController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $actor = $request->user();

        if (!$actor instanceof User) {
            return response()->json([
                'success' => false,
                'message' => 'Acceso restringido a clientes registrados.',
            ], 403);
        }

        $usuario = Usuario::where('email', $actor->email)->first();

        if (!$usuario) {
            return response()->json([
                'success' => true,
                'data'    => [],
            ]);
        }

        $innovaciones = Innovacion::where('usuario_id', $usuario->id)
            ->with(['categoria'])
            ->latest('id')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data'    => $innovaciones,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $actor = $request->user();

        if (!$actor instanceof User) {
            return response()->json([
                'success' => false,
                'message' => 'Acceso restringido a clientes registrados.',
            ], 403);
        }

        $validated = $request->validate([
            'titulo'          => ['required', 'string', 'min:5', 'max:200'],
            'categoria_id'    => ['required', 'integer', 'exists:categorias,id'],
            'descripcion'     => ['required', 'string', 'min:20', 'max:2000'],
            'meta_financiera' => ['required', 'numeric', 'min:1000', 'max:10000000'],
            'fecha_inicio'    => ['required', 'date'],
            'fecha_fin'       => ['required', 'date', 'after_or_equal:fecha_inicio'],
        ], [
            'titulo.required'          => 'El titulo del proyecto es obligatorio.',
            'titulo.min'               => 'El titulo debe tener al menos 5 caracteres.',
            'categoria_id.required'    => 'Debes seleccionar una categoria valida.',
            'categoria_id.exists'      => 'La categoria seleccionada no existe.',
            'descripcion.min'          => 'La descripcion debe contener un minimo de 20 caracteres.',
            'meta_financiera.required' => 'La meta financiera es requerida.',
            'meta_financiera.min'      => 'La meta debe ser de al menos 1000.',
            'fecha_fin.after_or_equal' => 'La fecha de finalizacion debe ser igual o posterior a la fecha de inicio.',
        ]);

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

        $innovacion = Innovacion::create([
            'usuario_id'      => $usuario->id,
            'categoria_id'    => $validated['categoria_id'],
            'titulo'          => $validated['titulo'],
            'descripcion'     => $validated['descripcion'],
            'meta_financiera' => $validated['meta_financiera'],
            'monto_recaudado' => 0.00,
            'estado'          => 'borrador',
            'fecha_inicio'    => $validated['fecha_inicio'],
            'fecha_fin'       => $validated['fecha_fin'],
            'imagen_portada'  => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Iniciativa registrada en borrador exitosamente.',
            'data'    => $innovacion->load('categoria'),
        ], 201);
    }

    public function update(Request $request, string|int $id): JsonResponse
    {
        $actor = $request->user();

        if (!$actor instanceof User) {
            return response()->json([
                'success' => false,
                'message' => 'Acceso restringido a clientes registrados.',
            ], 403);
        }

        $innovacion = Innovacion::find($id);

        if (!$innovacion) {
            return response()->json([
                'success' => false,
                'message' => "La iniciativa solicitada no existe.",
            ], 404);
        }

        $usuario = Usuario::where('email', $actor->email)->first();

        if (!$usuario || (int) $innovacion->usuario_id !== (int) $usuario->id) {
            return response()->json([
                'success' => false,
                'message' => 'No tiene autorizacion para modificar este proyecto.',
            ], 403);
        }

        $validated = $request->validate([
            'titulo'          => ['sometimes', 'required', 'string', 'min:5', 'max:200'],
            'categoria_id'    => ['sometimes', 'required', 'integer', 'exists:categorias,id'],
            'descripcion'     => ['sometimes', 'required', 'string', 'min:20', 'max:2000'],
            'meta_financiera' => ['sometimes', 'required', 'numeric', 'min:1000', 'max:10000000'],
        ]);

        $innovacion->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Iniciativa actualizada exitosamente.',
            'data'    => $innovacion->fresh('categoria'),
        ]);
    }
}
