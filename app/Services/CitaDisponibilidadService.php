<?php

namespace App\Services;

use App\Models\Cita;
use App\Models\Servicio;
use Carbon\Carbon;

class CitaDisponibilidadService
{
    public function slotsDisponibles(string $fecha, ?int $excluirCitaId = null): array
    {
        $dia = Carbon::parse($fecha);
        if (! in_array($dia->dayOfWeekIso, config('salon.dias_laborales', [1, 2, 3, 4, 5, 6]))) {
            return [];
        }

        $apertura = Carbon::parse($fecha.' '.config('salon.hora_apertura'));
        $cierre = Carbon::parse($fecha.' '.config('salon.hora_cierre'));
        $intervalo = config('salon.intervalo_minutos', 30);

        $ocupados = $this->bloquesOcupados($fecha, $excluirCitaId);
        $slots = [];

        for ($slot = $apertura->copy(); $slot->lt($cierre); $slot->addMinutes($intervalo)) {
            $finSlot = $slot->copy()->addMinutes($intervalo);
            if ($finSlot->gt($cierre)) {
                break;
            }

            $libre = true;
            foreach ($ocupados as $bloque) {
                if ($this->seSolapan($slot, $finSlot, $bloque['inicio'], $bloque['fin'])) {
                    $libre = false;
                    break;
                }
            }

            if ($libre && $slot->gte(now())) {
                $slots[] = $slot->format('H:i');
            }
        }

        return $slots;
    }

    public function hayDisponibilidad(string $fecha, string $hora, array $servicioIds, ?int $excluirCitaId = null): bool
    {
        $duracion = Servicio::whereIn('id', $servicioIds)->sum('duracion') ?: 60;
        $inicio = Carbon::parse($fecha.' '.$hora);
        $fin = $inicio->copy()->addMinutes($duracion);

        $apertura = Carbon::parse($fecha.' '.config('salon.hora_apertura'));
        $cierre = Carbon::parse($fecha.' '.config('salon.hora_cierre'));

        if ($inicio->lt($apertura) || $fin->gt($cierre) || $inicio->lt(now())) {
            return false;
        }

        foreach ($this->bloquesOcupados($fecha, $excluirCitaId) as $bloque) {
            if ($this->seSolapan($inicio, $fin, $bloque['inicio'], $bloque['fin'])) {
                return false;
            }
        }

        return true;
    }

    public function calcularTotal(array $servicioIds): float
    {
        return (float) Servicio::whereIn('id', $servicioIds)->sum('precio');
    }

    protected function bloquesOcupados(string $fecha, ?int $excluirCitaId = null): array
    {
        $query = Cita::with('servicios')
            ->activas()
            ->whereDate('fecha', $fecha);

        if ($excluirCitaId) {
            $query->where('id', '!=', $excluirCitaId);
        }

        return $query->get()->map(function (Cita $cita) {
            return [
                'inicio' => $cita->horaInicio(),
                'fin' => $cita->horaFin(),
            ];
        })->all();
    }

    protected function seSolapan(Carbon $aInicio, Carbon $aFin, Carbon $bInicio, Carbon $bFin): bool
    {
        return $aInicio->lt($bFin) && $aFin->gt($bInicio);
    }
}
