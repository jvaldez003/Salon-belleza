<?php

namespace App\Http\Controllers;

use App\Http\Requests\CitaRequest;
use App\Models\Cita;
use App\Models\Horario;
use App\Models\Servicio;
use App\Models\User;
use App\Notifications\CitaConfirmadaNotification;
use App\Services\CitaDisponibilidadService;
use Illuminate\Http\Request;

class CitaController extends Controller
{
    public function __construct(protected CitaDisponibilidadService $disponibilidad)
    {
    }

    public function index(Request $request)
    {
        $query = Cita::with(['usuario', 'servicios', 'resena'])->orderByDesc('fecha')->orderBy('hora');

        if (! auth()->user()->isAdmin() && ! auth()->user()->isEditor()) {
            $query->where('user_id', auth()->id());
        } else {
            if ($request->filled('buscar')) {
                $buscar = $request->buscar;
                $query->where(function ($q) use ($buscar) {
                    $q->whereHas('usuario', fn ($u) => $u->where('name', 'like', "%{$buscar}%")->orWhere('email', 'like', "%{$buscar}%"))
                        ->orWhere('estado', 'like', "%{$buscar}%");
                });
            }
            if ($request->filled('estado')) {
                $query->where('estado', $request->estado);
            }
            if ($request->filled('fecha')) {
                $query->whereDate('fecha', $request->fecha);
            }
        }

        $citas = $query->paginate(10)->withQueryString();

        return view('citas.index', compact('citas'));
    }

    public function calendario()
    {
        return view('citas.calendario');
    }

    public function eventosCalendario(Request $request)
    {
        $request->validate([
            'start' => 'nullable|date',
            'end' => 'nullable|date',
        ]);

        $query = Cita::with(['usuario', 'servicios'])->activas();

        if (! auth()->user()->isAdmin() && ! auth()->user()->isEditor()) {
            $query->where('user_id', auth()->id());
        }

        if ($request->filled('start') && $request->filled('end')) {
            $query->whereDate('fecha', '>=', $request->start)
                ->whereDate('fecha', '<=', $request->end);
        }

        $colores = [
            'pendiente' => '#f59e0b',
            'confirmada' => '#4f46e5',
            'completada' => '#10b981',
        ];

        $eventos = $query->get()->map(function (Cita $cita) use ($colores) {
            $titulo = auth()->user()->isAdmin() || auth()->user()->isEditor()
                ? ($cita->usuario?->name ?? 'Cliente').' — '.$cita->servicios->pluck('nombre')->implode(', ')
                : $cita->servicios->pluck('nombre')->implode(', ');

            return [
                'id' => $cita->id,
                'title' => $titulo,
                'start' => $cita->horaInicio()->toIso8601String(),
                'end' => $cita->horaFin()->toIso8601String(),
                'url' => route('citas.show', $cita),
                'backgroundColor' => $colores[$cita->estado] ?? '#64748b',
                'borderColor' => $colores[$cita->estado] ?? '#64748b',
                'extendedProps' => [
                    'estado' => $cita->estado,
                    'total' => (float) $cita->total,
                ],
            ];
        });

        return response()->json($eventos);
    }

    public function agenda(Request $request)
    {
        $vista = $request->get('vista', 'dia');
        $fechaBase = \Carbon\Carbon::parse($request->get('fecha', now()->toDateString()));

        if ($vista === 'semana') {
            $inicioSemana = $fechaBase->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
            $finSemana = $fechaBase->copy()->endOfWeek(\Carbon\Carbon::SUNDAY);

            $citas = Cita::with(['usuario', 'servicios'])
                ->activas()
                ->whereBetween('fecha', [$inicioSemana->toDateString(), $finSemana->toDateString()])
                ->orderBy('fecha')
                ->orderBy('hora')
                ->get()
                ->groupBy(fn (Cita $cita) => $cita->fecha->format('Y-m-d'));

            $diasSemana = collect(range(0, 6))->map(function ($offset) use ($inicioSemana) {
                return $inicioSemana->copy()->addDays($offset);
            });

            return view('citas.agenda', compact('vista', 'fechaBase', 'inicioSemana', 'finSemana', 'citas', 'diasSemana'));
        }

        $fecha = $fechaBase->toDateString();
        $citasDia = Cita::with(['usuario', 'servicios'])
            ->activas()
            ->whereDate('fecha', $fecha)
            ->orderBy('hora')
            ->get();

        return view('citas.agenda', [
            'vista' => 'dia',
            'fecha' => $fecha,
            'fechaBase' => $fechaBase,
            'citasDia' => $citasDia,
        ]);
    }

    public function create()
    {
        $servicios = Servicio::activos()->with('empleados')->orderBy('nombre')->get();

        return view('citas.create', compact('servicios'));
    }

    public function store(CitaRequest $request)
    {
        $servicioIds = $request->servicios;
        $userId = auth()->user()->isAdmin() && $request->user_id
            ? $request->user_id
            : auth()->id();

        if (! $this->disponibilidad->hayDisponibilidad($request->fecha, $request->hora, $servicioIds)) {
            return back()->withInput()->withErrors(['hora' => 'El horario seleccionado no está disponible.']);
        }

        $cita = Cita::create([
            'fecha'   => $request->fecha,
            'hora'    => $request->hora,
            'user_id' => $userId,
            'total'   => $this->disponibilidad->calcularTotal($servicioIds),
            'estado'  => $request->estado ?? 'pendiente',
            'notas'   => $request->notas,
        ]);

        $cita->servicios()->sync($this->buildPivot($servicioIds, $request->input('empleados', [])));
        $cita->load('servicios', 'usuario');

        $cita->usuario?->notify(new CitaConfirmadaNotification($cita));

        return redirect()->route('citas.index')->with('success', 'Cita reservada correctamente.');
    }

    public function show(Cita $cita)
    {
        $this->autorizarAcceso($cita);
        $cita->load('servicios', 'usuario');

        return view('citas.show', compact('cita'));
    }

    public function edit(Cita $cita)
    {
        $this->autorizarAcceso($cita);
        $servicios = Servicio::activos()->with('empleados')->orderBy('nombre')->get();
        $cita->load('servicios');

        return view('citas.edit', compact('cita', 'servicios'));
    }

    public function update(CitaRequest $request, Cita $cita)
    {
        $this->autorizarAcceso($cita);

        $servicioIds = $request->servicios;

        if (! $this->disponibilidad->hayDisponibilidad($request->fecha, $request->hora, $servicioIds, $cita->id)) {
            return back()->withInput()->withErrors(['hora' => 'El horario seleccionado no está disponible.']);
        }

        $estado = $request->estado ?? $cita->estado;
        if (! auth()->user()->isAdmin() && ! auth()->user()->isEditor()) {
            $estado = $cita->estado === 'confirmada' ? 'cancelada' : $request->estado ?? 'cancelada';
        }

        $cita->update([
            'fecha'  => $request->fecha,
            'hora'   => $request->hora,
            'total'  => $this->disponibilidad->calcularTotal($servicioIds),
            'estado' => $estado,
            'notas'  => $request->notas,
        ]);

        $cita->servicios()->sync($this->buildPivot($servicioIds, $request->input('empleados', [])));

        return redirect()->route('citas.index')->with('success', 'Cita actualizada correctamente.');
    }

    public function destroy(Cita $cita)
    {
        $this->autorizarAcceso($cita);
        $cita->update(['estado' => 'cancelada']);

        return redirect()->route('citas.index')->with('success', 'Cita cancelada correctamente.');
    }

    public function horariosDisponibles(Request $request)
    {
        $request->validate([
            'fecha'    => 'required|date',
            'cita_id'  => 'nullable|integer',
            'empleados' => 'nullable|array',
        ]);

        $dia = \Carbon\Carbon::parse($request->fecha);
        $horario = Horario::delDia($dia->dayOfWeekIso);
        $esLaboral = $horario && $horario->activo;

        // Extraer IDs únicos de empleados seleccionados (valores no vacíos)
        $empleadoIds = collect($request->input('empleados', []))
            ->filter()
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values()
            ->toArray();

        $slots = $this->disponibilidad->slotsDisponibles(
            $request->fecha,
            $request->cita_id,
            $empleadoIds
        );

        $mensaje = null;
        if (! $esLaboral) {
            $mensaje = 'El salón no atiende este día.';
        } elseif (! empty($empleadoIds) && empty($slots)) {
            $mensaje = $dia->isToday()
                ? 'El empleado no tiene horarios disponibles hoy.'
                : 'El empleado no tiene disponibilidad este día.';
        } elseif (empty($slots)) {
            $mensaje = $dia->isToday()
                ? 'No quedan horarios disponibles para hoy. Prueba otro día.'
                : 'Todos los horarios de este día están ocupados.';
        }

        return response()->json([
            'slots'    => $slots,
            'cerrado'  => ! $esLaboral,
            'mensaje'  => $mensaje,
        ]);
    }

    public function horariosSalon()
    {
        return redirect()->route('horarios.index');
    }

    protected function buildPivot(array $servicioIds, array $empleadosInput): array
    {
        $pivot = [];
        foreach ($servicioIds as $sid) {
            $eid = isset($empleadosInput[$sid]) && $empleadosInput[$sid] !== ''
                ? (int) $empleadosInput[$sid]
                : null;
            $pivot[(int) $sid] = ['empleado_id' => $eid];
        }
        return $pivot;
    }

    protected function autorizarAcceso(Cita $cita): void
    {
        if (auth()->user()->isAdmin() || auth()->user()->isEditor()) {
            return;
        }

        if ($cita->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
