<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Inertia\Inertia;
use Inertia\Response;

class RolController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Roles/Index', [
            'roles' => Rol::orderBy('id')->paginate(10),
        ]);
    }
}
