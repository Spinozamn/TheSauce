<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Inertia\Inertia;

class UsuarioController extends Controller
{
    public function index()
    {
        // Cargamos la relación 'rol' para mostrar el nombre del rol en lugar del rol_id
        $usuarios = Usuario::with('rol')
            ->orderBy('id', 'desc')
            ->paginate(10);
        
        return Inertia::render('admin/usuarios/Index', [
            'usuarios' => $usuarios
        ]);
    }
}