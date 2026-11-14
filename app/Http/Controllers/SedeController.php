<?php

namespace App\Http\Controllers;

use App\Models\Sede;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SedeController extends Controller
{
    public function index(): View
    {
        $sedes = Sede::withCount('atracciones')->orderBy('nombre')->get();

        return view('sedes.index', compact('sedes'));
    }

    public function create(): View
    {
        return view('sedes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'nombre'    => ['required', 'string', 'max:30', 'unique:sedes,nombre'],
            'ubicacion' => ['required', 'string', 'max:100'],
        ]);

        Sede::create($datos);

        return redirect()->route('sedes.index')->with('status', 'Sede creada correctamente.');
    }

    public function edit(Sede $sede): View
    {
        return view('sedes.edit', compact('sede'));
    }

    public function update(Request $request, Sede $sede): RedirectResponse
    {
        $datos = $request->validate([
            'nombre'    => ['required', 'string', 'max:30', Rule::unique('sedes', 'nombre')->ignore($sede->id_sede, 'id_sede')],
            'ubicacion' => ['required', 'string', 'max:100'],
        ]);

        $sede->update($datos);

        return redirect()->route('sedes.index')->with('status', 'Sede actualizada correctamente.');
    }

    public function destroy(Sede $sede): RedirectResponse
    {
        if ($sede->atracciones()->exists()) {
            return redirect()->route('sedes.index')
                ->with('error', 'No se puede eliminar la sede porque tiene atracciones asociadas.');
        }

        $sede->delete();

        return redirect()->route('sedes.index')->with('status', 'Sede eliminada correctamente.');
    }
}
