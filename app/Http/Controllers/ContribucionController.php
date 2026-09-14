<?php

namespace App\Http\Controllers;

use App\Models\Contribucion;
use Inertia\Inertia;
use Inertia\Response;

class ContribucionController extends Controller
{
    public function index(): Response
    {
        $contribuciones = Contribucion::with(['usuario', 'innovacion'])->orderBy('id')->paginate(10);

        return Inertia::render('admin/contribuciones/index', [
            'contribuciones' => $contribuciones,
        ]);
    }
}
