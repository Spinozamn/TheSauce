<?php

namespace App\Http\Controllers;

use App\Models\Etiqueta;
use Inertia\Inertia;
use Inertia\Response;

class EtiquetaController extends Controller
{
    public function index(): Response
    {
        $etiquetas = Etiqueta::withCount('innovaciones')->orderBy('id')->paginate(10);

        return Inertia::render('Etiquetas/Index', [
            'etiquetas' => $etiquetas,
        ]);
    }
}
