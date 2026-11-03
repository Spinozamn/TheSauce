<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Innovacion;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(Request $request): Response
    {
        $categoriaId = $request->query('categoria');
        $busqueda = $request->query('q');

        $query = Innovacion::with(['categoria', 'usuario'])
            ->whereIn('estado', ['activo', 'financiado']);

        if ($categoriaId) {
            $query->where('categoria_id', $categoriaId);
        }

        if ($busqueda) {
            $query->where(function ($q) use ($busqueda) {
                $q->where('titulo', 'like', "%{$busqueda}%")
                    ->orWhere('descripcion', 'like', "%{$busqueda}%");
            });
        }

        $innovaciones = $query
            ->latest('id')
            ->paginate(9)
            ->withQueryString();

        $destacado = Innovacion::with(['categoria', 'usuario'])
            ->whereIn('estado', ['activo', 'financiado'])
            ->orderByDesc('monto_recaudado')
            ->first();

        $categorias = Categoria::where('estado', 'activa')
            ->orderBy('nombre')
            ->get(['id', 'nombre', 'icono']);

        return Inertia::render('welcome', [
            'innovaciones' => $innovaciones,
            'destacado' => $destacado,
            'categorias' => $categorias,
            'filtros' => [
                'categoria' => $categoriaId ? (int) $categoriaId : null,
                'q' => $busqueda ?? '',
            ],
        ]);
    }

    public function show(string|int $id): Response
    {
        $innovacion = Innovacion::with([
            'categoria',
            'usuario',
            'etiquetas',
            'hitosFinancieros',
            'comentarios.usuario',
            'contribuciones.usuario',
        ])->findOrFail($id);

        $relacionados = Innovacion::with(['categoria', 'usuario'])
            ->where('id', '!=', $innovacion->id)
            ->where('categoria_id', $innovacion->categoria_id)
            ->whereIn('estado', ['activo', 'financiado'])
            ->limit(3)
            ->get();

        return Inertia::render('proyectos/show', [
            'proyecto' => $innovacion,
            'relacionados' => $relacionados,
        ]);
    }
}
