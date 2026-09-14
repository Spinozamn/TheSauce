<?php

namespace App\Http\Controllers;

use App\Models\LogSistema;
use Inertia\Inertia;
use Inertia\Response;

class LogSistemaController extends Controller
{
    public function index(): Response
    {
        $logsSistema = LogSistema::with('usuario')->orderBy('id')->paginate(10);

        return Inertia::render('admin/logs-sistema/index', [
            'logsSistema' => $logsSistema,
        ]);
    }
}
