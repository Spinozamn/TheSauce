<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use Inertia\Inertia;
use Inertia\Response;

class CarritoController extends Controller
{
    public function index(): Response
    {
        $carritos = Carrito::with('usuario')->withCount('detalles')->orderBy('id')->paginate(10);

        return Inertia::render('Carritos/Index', [
            'carritos' => $carritos,
        ]);
    }
}
