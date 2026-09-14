<?php

namespace App\Http\Controllers;

use App\Models\Innovacion;
use Inertia\Inertia;
use Inertia\Response;

class InnovacionController extends Controller
{
    public function index(): Response
    {
        $innovaciones = Innovacion::with(['usuario', 'categoria', 'etiquetas'])->orderBy('id')->paginate(10);

        return Inertia::render('Innovaciones/Index', [
            'innovaciones' => $innovaciones,
        ]);
    }
}
