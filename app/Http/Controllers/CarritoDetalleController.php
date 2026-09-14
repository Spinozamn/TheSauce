<?php

namespace App\Http\Controllers;

use App\Models\CarritoDetalle;
use Inertia\Inertia;
use Inertia\Response;

class CarritoDetalleController extends Controller
{
    public function index(): Response
    {
        $carritoDetalles = CarritoDetalle::with(['carrito.usuario', 'innovacion'])->orderBy('id')->paginate(10);

        return Inertia::render('admin/carrito-detalles/index', [
            'carritoDetalles' => $carritoDetalles,
        ]);
    }
}
