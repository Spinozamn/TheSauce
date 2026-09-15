<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Inertia\Inertia;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::orderBy('id', 'desc')->paginate(10);
        
        return Inertia::render('admin/categorias/Index', [
            'categorias' => $categorias
        ]);
    }
}