<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HitoFinanciero;
use Inertia\Inertia;

class HitoFinancieroController extends Controller
{
    public function index()
    {
        $hitos = HitoFinanciero::with('innovacion')
            ->orderBy('id', 'desc')
            ->paginate(10);
        
        return Inertia::render('admin/hitos-financieros/Index', [
            'hitos' => $hitos
        ]);
    }
}