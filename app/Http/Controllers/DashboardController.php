<?php

namespace App\Http\Controllers;

use App\Models\Sede;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $sedes = Sede::withCount('atracciones')->orderBy('nombre')->get();

        return view('dashboard', [
            'totalSedes'       => $sedes->count(),
            'totalAtracciones' => $sedes->sum('atracciones_count'),
            'labels'           => $sedes->pluck('nombre'),
            'datos'            => $sedes->pluck('atracciones_count'),
        ]);
    }
}
