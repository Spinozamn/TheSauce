<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use Inertia\Inertia;
use Inertia\Response;

class ComentarioController extends Controller
{
    public function index(): Response
    {
        $comentarios = Comentario::with(['usuario', 'innovacion', 'padre'])->orderBy('id')->paginate(10);

        return Inertia::render('admin/comentarios/index', [
            'comentarios' => $comentarios,
        ]);
    }
}
