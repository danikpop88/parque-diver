<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sede;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SedeApiController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            Sede::withCount('atracciones')->orderBy('nombre')->get()
        );
    }

    public function show(Sede $sede): JsonResponse
    {
        return response()->json($sede->load('atracciones'));
    }

    public function store(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'nombre'    => ['required', 'string', 'max:30', 'unique:sedes,nombre'],
            'ubicacion' => ['required', 'string', 'max:100'],
        ]);

        $sede = Sede::create($datos);

        return response()->json($sede->fresh(), 201);
    }

    public function update(Request $request, Sede $sede): JsonResponse
    {
        $datos = $request->validate([
            'nombre'    => ['required', 'string', 'max:30', Rule::unique('sedes', 'nombre')->ignore($sede->id_sede, 'id_sede')],
            'ubicacion' => ['required', 'string', 'max:100'],
        ]);

        $sede->update($datos);

        return response()->json($sede->fresh());
    }

    public function destroy(Sede $sede): JsonResponse
    {
        if ($sede->atracciones()->exists()) {
            return response()->json(
                ['message' => 'No se puede eliminar la sede porque tiene atracciones asociadas.'],
                409
            );
        }

        $sede->delete();

        return response()->json(['message' => 'Sede eliminada.']);
    }
}
