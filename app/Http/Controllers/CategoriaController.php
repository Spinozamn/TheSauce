<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Inertia\Inertia;
use Inertia\Response;

class CategoriaController extends Controller
{
    public function index(): Response
    {
        $categorias = Categoria::withCount('innovaciones')->orderBy('id')->paginate(10);

        return Inertia::render('Categorias/Index', [
            'categorias' => $categorias,
        ]);
    }
}
