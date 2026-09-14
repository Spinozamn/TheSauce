<?php

namespace App\Http\Controllers;

use App\Models\CuentaSocial;
use Inertia\Inertia;
use Inertia\Response;

class CuentaSocialController extends Controller
{
    public function index(): Response
    {
        $cuentasSociales = CuentaSocial::with('usuario')->orderBy('id')->paginate(10);

        return Inertia::render('admin/cuentas-sociales/index', [
            'cuentasSociales' => $cuentasSociales,
        ]);
    }
}
