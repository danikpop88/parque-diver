<?php

namespace App\Http\Controllers;

use App\Models\Atraccion;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class ReporteController extends Controller
{
    public function atracciones(): Response
    {
        $atracciones = Atraccion::with('sede')
            ->get()
            ->sortBy(fn ($a) => $a->sede->nombre . '|' . $a->nombre);

        $pdf = Pdf::loadView('reportes.atracciones', [
            'atracciones' => $atracciones,
            'generadoPor' => auth()->user()->name,
            'fecha'       => now()->timezone('America/Bogota')->format('d/m/Y H:i'),
        ])->setPaper('a4', 'portrait');

        return $pdf->download('reporte-atracciones.pdf');
    }
}
