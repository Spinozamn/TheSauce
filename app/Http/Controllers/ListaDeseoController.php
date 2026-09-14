<?php

namespace App\Http\Controllers;

use App\Models\ListaDeseo;
use Inertia\Inertia;
use Inertia\Response;

class ListaDeseoController extends Controller
{
    public function index(): Response
    {
        $listaDeseos = ListaDeseo::with(['usuario', 'innovacion'])->orderBy('id')->paginate(10);

        return Inertia::render('ListaDeseos/Index', [
            'listaDeseos' => $listaDeseos,
        ]);
    }
}
