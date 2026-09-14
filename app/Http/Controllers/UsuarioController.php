<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Inertia\Inertia;
use Inertia\Response;

class UsuarioController extends Controller
{
    public function index(): Response
    {
        $usuarios = Usuario::with('rol')->orderBy('id')->paginate(10);

        return Inertia::render('Usuarios/Index', [
            'usuarios' => $usuarios,
        ]);
    }
}
