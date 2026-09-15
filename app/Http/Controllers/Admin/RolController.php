<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use Inertia\Inertia;

class RolController extends Controller
{
    public function index()
    {
        $roles = Rol::orderBy('id', 'desc')->paginate(10);
        
        return Inertia::render('admin/roles/Index', [
            'roles' => $roles
        ]);
    }
}