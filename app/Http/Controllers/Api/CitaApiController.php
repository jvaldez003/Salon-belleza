<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CitaRequest;
use App\Models\Cita;
use App\Notifications\CitaConfirmadaNotification;
use App\Services\CitaDisponibilidadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CitaApiController extends Controller
{
    public function __construct(protected CitaDisponibilidadService $disponibilidad)
    {
    }

    public function porUsuario(int $id): JsonResponse
    {
        if (! auth()->user()->isAdmin() && auth()->id() !== $id) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        $citas = Cita::with('servicios')
            ->where('user_id', $id)
            ->orderByDesc('fecha')
            ->get()
            ->map(fn ($c) => $this->formatearCita($c));

        return response()->json(['success' => true, 'data' => $citas]);
    }

    public function store(CitaRequest $request): JsonResponse
    {
        $servicioIds = $request->servicios;

        if (! $this->disponibilidad->hayDisponibilidad($request->fecha, $request->hora, $servicioIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Horario no disponible',
            ], 422);
        }

        $cita = Cita::create([
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'user_id' => auth()->id(),
            'total' => $this->disponibilidad->calcularTotal($servicioIds),
            'estado' => 'pendiente',
            'notas' => $request->notas,
        ]);

        $cita->servicios()->sync($servicioIds);
        $cita->load('servicios');
        auth()->user()->notify(new CitaConfirmadaNotification($cita));

        return response()->json([
            'success' => true,
            'data' => $this->formatearCita($cita),
        ], 201);
    }

    public function update(CitaRequest $request, Cita $cita): JsonResponse
    {
        if (! auth()->user()->isAdmin() && $cita->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'No autorizado'], 403);
        }

        $servicioIds = $request->servicios;

        if (! $this->disponibilidad->hayDisponibilidad($request->fecha, $request->hora, $servicioIds, $cita->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Horario no disponible',
            ], 422);
        }

        $cita->update([
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'total' => $this->disponibilidad->calcularTotal($servicioIds),
            'estado' => $request->estado ?? $cita->estado,
            'notas' => $request->notas,
        ]);

        $cita->servicios()->sync($servicioIds);
        $cita->load('servicios');

        return response()->json([
            'success' => true,
            'data' => $this->formatearCita($cita),
        ]);
    }

    public function horarios(Request $request): JsonResponse
    {
        $request->validate(['fecha' => 'required|date']);

        return response()->json([
            'success' => true,
            'data' => $this->disponibilidad->slotsDisponibles($request->fecha),
        ]);
    }

    protected function formatearCita(Cita $cita): array
    {
        return [
            'id' => $cita->id,
            'fecha' => $cita->fecha->format('Y-m-d'),
            'hora' => substr((string) $cita->hora, 0, 5),
            'total' => (float) $cita->total,
            'estado' => $cita->estado,
            'servicios' => $cita->servicios->map(fn ($s) => [
                'id' => $s->id,
                'nombre' => $s->nombre,
                'precio' => (float) $s->precio,
            ]),
        ];
    }
}
