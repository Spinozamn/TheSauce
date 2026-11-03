<?php

namespace App\Http\Controllers\Api\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Contribucion;
use App\Models\Innovacion;
use App\Models\User;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContribucionClienteController extends Controller
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

        $contribuciones = Contribucion::where('usuario_id', $usuario->id)
            ->with(['innovacion.categoria'])
            ->latest('id')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data'    => $contribuciones,
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
            'innovacion_id' => ['required', 'integer', 'exists:innovaciones,id'],
            'monto'         => ['required', 'numeric', 'min:10', 'max:500000'],
            'metodo_pago'   => ['required', 'string', 'in:tarjeta,paypal,transferencia'],
        ], [
            'innovacion_id.required' => 'El proyecto al que desea contribuir es obligatorio.',
            'innovacion_id.exists'   => 'El proyecto seleccionado no existe.',
            'monto.required'         => 'El monto de la contribucion es obligatorio.',
            'monto.min'              => 'El monto minimo de contribucion es de 10.',
            'metodo_pago.required'   => 'El metodo de pago es obligatorio.',
            'metodo_pago.in'         => 'El metodo de pago seleccionado no es valido.',
        ]);

        $innovacion = Innovacion::findOrFail($validated['innovacion_id']);

        if (!in_array($innovacion->estado, ['activo', 'financiado'])) {
            return response()->json([
                'success' => false,
                'message' => 'No es posible contribuir a un proyecto que no se encuentra activo.',
            ], 422);
        }

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

        $contribucion = DB::transaction(function () use ($validated, $innovacion, $usuario) {
            $nueva = Contribucion::create([
                'usuario_id'            => $usuario->id,
                'innovacion_id'         => $innovacion->id,
                'monto'                 => $validated['monto'],
                'metodo_pago'           => $validated['metodo_pago'],
                'estado'                => 'aprobada',
                'referencia_transaccion'=> 'TXN-' . strtoupper(bin2hex(random_bytes(4))),
                'fecha_contribucion'    => now(),
            ]);

            $innovacion->increment('monto_recaudado', $validated['monto']);

            return $nueva;
        });

        return response()->json([
            'success' => true,
            'message' => 'Contribucion registrada exitosamente.',
            'data'    => $contribucion->load('innovacion'),
        ], 201);
    }

    public function show(Request $request, string|int $id): JsonResponse
    {
        $actor = $request->user();

        if (!$actor instanceof User) {
            return response()->json([
                'success' => false,
                'message' => 'Acceso restringido a clientes registrados.',
            ], 403);
        }

        $contribucion = Contribucion::with('innovacion.categoria')->find($id);

        if (!$contribucion) {
            return response()->json([
                'success' => false,
                'message' => "La contribucion solicitada no existe.",
            ], 404);
        }

        $usuario = Usuario::where('email', $actor->email)->first();

        if (!$usuario || (int) $contribucion->usuario_id !== (int) $usuario->id) {
            return response()->json([
                'success' => false,
                'message' => 'No tiene autorizacion para acceder a este registro.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data'    => $contribucion,
        ]);
    }
}
