<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Atraccion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AtraccionApiController extends Controller
{
    private function reglas(): array
    {
        return [
            'nombre'          => ['required', 'string', 'max:150'],
            'edad_minima'     => ['required', 'integer', 'min:0', 'max:120'],
            'estatura_minima' => ['required', 'numeric', 'min:0', 'max:3'],
            'id_sede'         => ['required', 'exists:sedes,id_sede'],
        ];
    }

    private function formato(Atraccion $a): array
    {
        return [
            'id_atraccion'    => $a->id_atraccion,
            'nombre'          => $a->nombre,
            'edad_minima'     => $a->edad_minima,
            'estatura_minima' => $a->estatura_minima,
            'imagen_url'      => $a->imagen ? asset('storage/' . $a->imagen) : null,
            'sede'            => $a->relationLoaded('sede') && $a->sede
                ? ['id_sede' => $a->sede->id_sede, 'nombre' => $a->sede->nombre]
                : null,
        ];
    }

    // PÚBLICO: GET /api/atracciones  (opcional ?id_sede=...)
    public function index(Request $request): JsonResponse
    {
        $consulta = Atraccion::with('sede')->orderBy('nombre');

        if ($request->filled('id_sede')) {
            $consulta->where('id_sede', $request->query('id_sede'));
        }

        return response()->json(
            $consulta->get()->map(fn ($a) => $this->formato($a))
        );
    }

    // PÚBLICO: GET /api/atracciones/{id}
    public function show(Atraccion $atraccion): JsonResponse
    {
        return response()->json($this->formato($atraccion->load('sede')));
    }

    // PROTEGIDO: POST /api/atracciones
    public function store(Request $request): JsonResponse
    {
        $atraccion = Atraccion::create($request->validate($this->reglas()));

        return response()->json($this->formato($atraccion->load('sede')), 201);
    }

    // PROTEGIDO: PUT /api/atracciones/{id}
    public function update(Request $request, Atraccion $atraccion): JsonResponse
    {
        $atraccion->update($request->validate($this->reglas()));

        return response()->json($this->formato($atraccion->fresh()->load('sede')));
    }

    // PROTEGIDO: DELETE /api/atracciones/{id}
    public function destroy(Atraccion $atraccion): JsonResponse
    {
        if ($atraccion->imagen) {
            Storage::disk('public')->delete($atraccion->imagen);
        }

        $atraccion->delete();

        return response()->json(['message' => 'Atracción eliminada.']);
    }
}
