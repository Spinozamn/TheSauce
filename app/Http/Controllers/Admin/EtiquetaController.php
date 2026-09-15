<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Etiqueta;
use Inertia\Inertia;

class EtiquetaController extends Controller
{
    public function index()
    {
        $etiquetas = Etiqueta::orderBy('id', 'desc')->paginate(10);
        
        return Inertia::render('admin/etiquetas/Index', [
            'etiquetas' => $etiquetas
        ]);
    }
}