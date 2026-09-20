<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInnovacionRequest;
use App\Models\Categoria;
use App\Models\Innovacion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class InnovacionController extends Controller
{

    public function index(): Response
    {
        $innovaciones = Innovacion::with(['categoria', 'usuario', 'etiquetas'])
            ->orderBy('id', 'desc')
            ->paginate(10);

        return Inertia::render('admin/innovaciones/index', [
            'innovaciones' => $innovaciones,
        ]);
    }

    public function create(): Response
    {
        $categorias = Categoria::select('id', 'nombre')->orderBy('nombre')->get();

        return Inertia::render('admin/innovaciones/create', [
            'categorias' => $categorias,
        ]);
    }

    public function store(StoreInnovacionRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($request, $validated) {
            $innovacion = Innovacion::create([
                'usuario_id'      => Auth::id() ?? 1, 
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

            if ($request->hasFile('imagen')) {
                $archivo = $request->file('imagen');
                $extension = $archivo->getClientOriginalExtension();
                $nombreArchivo = "Innovacion_{$innovacion->id}_1.{$extension}";

                $archivo->storeAs('innovaciones', $nombreArchivo, 'public');

                $innovacion->update([
                    'imagen_portada' => "/storage/innovaciones/{$nombreArchivo}",
                ]);
            }
        });

        Inertia::flash('toast', [
            'type'    => 'success',
            'message' => '¡Innovación registrada exitosamente!',
        ]);

        return redirect()->route('admin.innovaciones.index');
    }
}