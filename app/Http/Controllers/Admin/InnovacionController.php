<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Innovacion;
use Inertia\Inertia;

class InnovacionController extends Controller
{
    public function index()
    {
        $innovaciones = Innovacion::with(['usuario', 'categoria', 'etiquetas'])
            ->orderBy('id', 'desc')
            ->paginate(10);
        
        return Inertia::render('admin/innovaciones/Index', [
            'innovaciones' => $innovaciones
        ]);
    }
}