<?php

namespace App\Http\Controllers;

use App\Models\HitoFinanciero;
use Inertia\Inertia;
use Inertia\Response;

class HitoFinancieroController extends Controller
{
    public function index(): Response
    {
        $hitosFinancieros = HitoFinanciero::with('innovacion')->orderBy('id')->paginate(10);

        return Inertia::render('HitosFinancieros/Index', [
            'hitosFinancieros' => $hitosFinancieros,
        ]);
    }
}
