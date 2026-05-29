<?php

namespace App\Services;

use App\Models\Cita;
use App\Models\Horario;
use App\Models\HorarioEmpleado;
use App\Models\Servicio;
use Carbon\Carbon;

class CitaDisponibilidadService
{
    /**
     * Genera los slots disponibles para una fecha.
     * Si se pasan $empleadoIds, los slots se calculan dentro del horario
     * de CADA empleado (intersección) y excluyendo sus citas existentes.
     */
    public function slotsDisponibles(string $fecha, ?int $excluirCitaId = null, array $empleadoIds = []): array
    {
        $dia     = Carbon::parse($fecha);
        $horario = Horario::delDia($dia->dayOfWeekIso);

        if (! $horario || ! $horario->activo) {
            return [];
        }

        $apertura  = Carbon::parse($fecha.' '.$horario->hora_apertura);
        $cierre    = Carbon::parse($fecha.' '.$horario->hora_cierre);
        $intervalo = config('salon.intervalo_minutos', 30);

        // Restringir la ventana al horario de los empleados seleccionados
        foreach ($empleadoIds as $empleadoId) {
            $he = HorarioEmpleado::where('empleado_id', $empleadoId)
                ->where('dia', $dia->dayOfWeekIso)
                ->first();

            if (! $he || ! $he->activo) {
                return []; // Este empleado no trabaja este día
            }

            $aperEmp    = Carbon::parse($fecha.' '.$he->hora_apertura);
            $cierreEmp  = Carbon::parse($fecha.' '.$he->hora_cierre);

            // Intersección: tomamos la ventana más restrictiva
            if ($aperEmp->gt($apertura)) $apertura = $aperEmp;
            if ($cierreEmp->lt($cierre))  $cierre   = $cierreEmp;
        }

        if ($apertura->gte($cierre)) {
            return [];
        }

        $ocupados = $this->bloquesOcupados($fecha, $excluirCitaId, $empleadoIds);
        $slots    = [];

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

    public function hayDisponibilidad(string $fecha, string $hora, array $servicioIds, ?int $excluirCitaId = null, array $empleadoIds = []): bool
    {
        $duracion = Servicio::whereIn('id', $servicioIds)->sum('duracion') ?: 60;
        $inicio   = Carbon::parse($fecha.' '.$hora);
        $fin      = $inicio->copy()->addMinutes($duracion);

        $diaIso  = Carbon::parse($fecha)->dayOfWeekIso;
        $horario = Horario::delDia($diaIso);

        if (! $horario || ! $horario->activo) {
            return false;
        }

        $apertura = Carbon::parse($fecha.' '.$horario->hora_apertura);
        $cierre   = Carbon::parse($fecha.' '.$horario->hora_cierre);

        // Verificar ventana de empleados
        foreach ($empleadoIds as $empleadoId) {
            $he = HorarioEmpleado::where('empleado_id', $empleadoId)
                ->where('dia', $diaIso)
                ->first();

            if (! $he || ! $he->activo) {
                return false;
            }

            $aperEmp   = Carbon::parse($fecha.' '.$he->hora_apertura);
            $cierreEmp = Carbon::parse($fecha.' '.$he->hora_cierre);

            if ($aperEmp->gt($apertura)) $apertura = $aperEmp;
            if ($cierreEmp->lt($cierre))  $cierre   = $cierreEmp;
        }

        if ($inicio->lt($apertura) || $fin->gt($cierre) || $inicio->lt(now())) {
            return false;
        }

        foreach ($this->bloquesOcupados($fecha, $excluirCitaId, $empleadoIds) as $bloque) {
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

    /**
     * Devuelve los bloques ocupados.
     * Si hay empleadoIds, solo considera citas que involucran esos empleados,
     * lo que permite citas simultáneas con diferentes empleados.
     */
    protected function bloquesOcupados(string $fecha, ?int $excluirCitaId = null, array $empleadoIds = []): array
    {
        $query = Cita::with('servicios')
            ->activas()
            ->whereDate('fecha', $fecha);

        if ($excluirCitaId) {
            $query->where('id', '!=', $excluirCitaId);
        }

        if (! empty($empleadoIds)) {
            // Solo bloquear slots donde alguno de estos empleados ya está ocupado
            $query->whereHas('servicios', function ($q) use ($empleadoIds) {
                $q->whereIn('cita_servicio.empleado_id', $empleadoIds);
            });
        }

        return $query->get()->map(function (Cita $cita) {
            return [
                'inicio' => $cita->horaInicio(),
                'fin'    => $cita->horaFin(),
            ];
        })->all();
    }

    protected function seSolapan(Carbon $aInicio, Carbon $aFin, Carbon $bInicio, Carbon $bFin): bool
    {
        return $aInicio->lt($bFin) && $aFin->gt($bInicio);
    }
}
