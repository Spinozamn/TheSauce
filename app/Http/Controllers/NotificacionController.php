<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Inertia\Inertia;
use Inertia\Response;

class NotificacionController extends Controller
{
    public function index(): Response
    {
        $notificaciones = Notificacion::with('usuario')->orderBy('id')->paginate(10);

        return Inertia::render('admin/notificaciones/index', [
            'notificaciones' => $notificaciones,
        ]);
    }
}
