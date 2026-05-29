<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use Illuminate\Http\JsonResponse;

class ServicioApiController extends Controller
{
    public function index(): JsonResponse
    {
        $servicios = Servicio::activos()
            ->with('imagenes')
            ->orderBy('nombre')
            ->get()
            ->map(fn ($s) => [
                'id' => $s->id,
                'nombre' => $s->nombre,
                'precio' => (float) $s->precio,
                'descripcion' => $s->descripcion,
                'duracion' => $s->duracion,
                'activo' => $s->activo,
            ]);

        return response()->json([
            'success' => true,
            'data' => $servicios,
        ]);
    }
}
