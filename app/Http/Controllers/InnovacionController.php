<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInnovacionRequest;
use App\Http\Requests\UpdateInnovacionRequest;
use App\Models\Categoria;
use App\Models\Innovacion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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

        return redirect()->route('admin.innovaciones.index')->with('toast', [
            'type'    => 'success',
            'message' => '¡Innovación registrada exitosamente!',
        ]);
    }

    public function show(string|int $id): Response|RedirectResponse
    {
        $innovacion = Innovacion::with([
            'categoria',
            'usuario',
            'etiquetas',
            'hitosFinancieros',
            'comentarios.usuario',
            'contribuciones.usuario',
        ])->find($id);

        if (!$innovacion) {
            return redirect()->route('admin.innovaciones.index')->with('toast', [
                'type'    => 'error',
                'message' => "El registro con ID #{$id} no existe.",
            ]);
        }

        return Inertia::render('admin/innovaciones/show', [
            'innovacion' => $innovacion,
        ]);
    }

    public function edit(string|int $id): Response|RedirectResponse
    {
        $innovacion = Innovacion::find($id);

        if (!$innovacion) {
            return redirect()->route('admin.innovaciones.index')->with('toast', [
                'type'    => 'error',
                'message' => "El registro con ID #{$id} no existe o fue eliminado.",
            ]);
        }

        $categorias = Categoria::select('id', 'nombre')->orderBy('nombre')->get();

        return Inertia::render('admin/innovaciones/edit', [
            'innovacion' => $innovacion,
            'categorias' => $categorias,
        ]);
    }

    public function update(UpdateInnovacionRequest $request, string|int $id): RedirectResponse
    {
        $innovacion = Innovacion::find($id);

        if (!$innovacion) {
            return redirect()->route('admin.innovaciones.index')->with('toast', [
                'type'    => 'error',
                'message' => "No se puede actualizar. La innovación con ID #{$id} no existe.",
            ]);
        }

        $validated = $request->validated();

        DB::transaction(function () use ($request, $validated, $innovacion) {
            $innovacion->update([
                'categoria_id'    => $validated['categoria_id'],
                'titulo'          => $validated['titulo'],
                'descripcion'     => $validated['descripcion'],
                'meta_financiera' => $validated['meta_financiera'],
                'estado'          => $validated['estado'] ?? $innovacion->estado,
                'fecha_inicio'    => $validated['fecha_inicio'],
                'fecha_fin'       => $validated['fecha_fin'],
            ]);

            if ($request->hasFile('imagen')) {
                if ($innovacion->imagen_portada) {
                    $rutaRelativa = str_replace('/storage/', '', $innovacion->imagen_portada);
                    if (Storage::disk('public')->exists($rutaRelativa)) {
                        Storage::disk('public')->delete($rutaRelativa);
                    }
                }

                $archivo = $request->file('imagen');
                $extension = $archivo->getClientOriginalExtension();
                $nombreArchivo = "Innovacion_{$innovacion->id}_1.{$extension}";

                $archivo->storeAs('innovaciones', $nombreArchivo, 'public');

                $innovacion->update([
                    'imagen_portada' => "/storage/innovaciones/{$nombreArchivo}",
                ]);
            }
        });

        return redirect()->route('admin.innovaciones.index')->with('toast', [
            'type'    => 'success',
            'message' => '¡Innovación actualizada exitosamente!',
        ]);
    }

    public function destroy(string|int $id): RedirectResponse
    {
        $innovacion = Innovacion::find($id);

        if (!$innovacion) {
            return redirect()->route('admin.innovaciones.index')->with('toast', [
                'type'    => 'error',
                'message' => "No se puede eliminar. El registro con ID #{$id} no existe.",
            ]);
        }

        $innovacion->delete();

        return redirect()->route('admin.innovaciones.index')->with('toast', [
            'type'    => 'success',
            'message' => '¡Innovación enviada a la papelera exitosamente!',
        ]);
    }

    public function trash(): Response
    {
        $innovaciones = Innovacion::onlyTrashed()
            ->with(['categoria', 'usuario', 'etiquetas'])
            ->orderBy('deleted_at', 'desc')
            ->paginate(10);

        return Inertia::render('admin/innovaciones/trash', [
            'innovaciones' => $innovaciones,
        ]);
    }

    public function restore(string|int $id): RedirectResponse
    {
        $innovacion = Innovacion::onlyTrashed()->find($id);

        if (!$innovacion) {
            return redirect()->route('admin.innovaciones.trash')->with('toast', [
                'type'    => 'error',
                'message' => "No se encontró el registro con ID #{$id} en la papelera.",
            ]);
        }

        $innovacion->restore();

        return redirect()->route('admin.innovaciones.trash')->with('toast', [
            'type'    => 'success',
            'message' => '¡Innovación restaurada exitosamente!',
        ]);
    }

    public function forceDelete(string|int $id): RedirectResponse
    {
        $innovacion = Innovacion::onlyTrashed()->find($id);

        if (!$innovacion) {
            return redirect()->route('admin.innovaciones.trash')->with('toast', [
                'type'    => 'error',
                'message' => "El registro no existe en la papelera o ya fue eliminado definitivamente.",
            ]);
        }

        $dependencias = [];
        if ($innovacion->contribuciones()->exists()) {
            $dependencias[] = 'contribuciones financieras';
        }
        if ($innovacion->comentarios()->exists()) {
            $dependencias[] = 'comentarios';
        }
        if ($innovacion->hitosFinancieros()->exists()) {
            $dependencias[] = 'hitos financieros';
        }
        if ($innovacion->carritoDetalles()->exists()) {
            $dependencias[] = 'registros en carritos';
        }
        if ($innovacion->listaDeseos()->exists()) {
            $dependencias[] = 'listas de deseos';
        }

        if (!empty($dependencias)) {
            $motivo = implode(', ', $dependencias);
            return redirect()->route('admin.innovaciones.trash')->with('toast', [
                'type'    => 'error',
                'message' => "No se puede eliminar definitivamente. El registro tiene dependencias activas: {$motivo}.",
            ]);
        }

        DB::transaction(function () use ($innovacion) {
            if ($innovacion->imagen_portada) {
                $rutaRelativa = str_replace('/storage/', '', $innovacion->imagen_portada);
                if (Storage::disk('public')->exists($rutaRelativa)) {
                    Storage::disk('public')->delete($rutaRelativa);
                }
            }

            $innovacion->etiquetas()->detach();
            $innovacion->forceDelete();
        });

        return redirect()->route('admin.innovaciones.trash')->with('toast', [
            'type'    => 'success',
            'message' => '¡Innovación y su imagen asociada fueron eliminadas definitivamente!',
        ]);
    }
}