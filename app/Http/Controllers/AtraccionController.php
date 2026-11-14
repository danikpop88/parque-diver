<?php

namespace App\Http\Controllers;

use App\Models\Atraccion;
use App\Models\Sede;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AtraccionController extends Controller
{
    private function reglas(): array
    {
        return [
            'nombre'          => ['required', 'string', 'max:150'],
            'edad_minima'     => ['required', 'integer', 'min:0', 'max:120'],
            'estatura_minima' => ['required', 'numeric', 'min:0', 'max:3'],
            'id_sede'         => ['required', 'exists:sedes,id_sede'],
            'imagen'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function index(): View
    {
        $atracciones = Atraccion::with('sede')->orderBy('nombre')->get();

        return view('atracciones.index', compact('atracciones'));
    }

    public function create(): View
    {
        $sedes = Sede::orderBy('nombre')->get();

        return view('atracciones.create', compact('sedes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate($this->reglas());

        if ($request->hasFile('imagen')) {
            $datos['imagen'] = $request->file('imagen')->store('atracciones', 'public');
        }

        Atraccion::create($datos);

        return redirect()->route('atracciones.index')->with('status', 'Atracción creada correctamente.');
    }

    public function edit(Atraccion $atraccion): View
    {
        $sedes = Sede::orderBy('nombre')->get();

        return view('atracciones.edit', compact('atraccion', 'sedes'));
    }

    public function update(Request $request, Atraccion $atraccion): RedirectResponse
    {
        $datos = $request->validate($this->reglas());

        if ($request->hasFile('imagen')) {
            if ($atraccion->imagen) {
                Storage::disk('public')->delete($atraccion->imagen);
            }
            $datos['imagen'] = $request->file('imagen')->store('atracciones', 'public');
        } else {
            unset($datos['imagen']); // conserva la imagen actual
        }

        $atraccion->update($datos);

        return redirect()->route('atracciones.index')->with('status', 'Atracción actualizada correctamente.');
    }

    public function destroy(Atraccion $atraccion): RedirectResponse
    {
        if ($atraccion->imagen) {
            Storage::disk('public')->delete($atraccion->imagen);
        }

        $atraccion->delete();

        return redirect()->route('atracciones.index')->with('status', 'Atracción eliminada correctamente.');
    }
}
