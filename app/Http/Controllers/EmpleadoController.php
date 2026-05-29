<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Horario;
use App\Models\HorarioEmpleado;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmpleadoController extends Controller
{
    public function index()
    {
        $empleados = Empleado::with('servicios')->orderBy('nombre')->get();
        return view('empleados.index', compact('empleados'));
    }

    public function create()
    {
        $servicios       = Servicio::activos()->orderBy('nombre')->get();
        $horariosDefault = Horario::orderBy('dia')->get();
        return view('empleados.create', compact('servicios', 'horariosDefault'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'       => 'required|string|max:100',
            'especialidad' => 'nullable|string|max:100',
            'foto'         => 'nullable|image|max:2048',
            'servicios'    => 'nullable|array',
            'servicios.*'  => 'exists:servicios,id',
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('empleados', 'public');
        }

        $data['activo'] = $request->boolean('activo', true);
        unset($data['servicios']);

        $empleado = Empleado::create($data);
        $empleado->servicios()->sync($request->servicios ?? []);
        $this->guardarHorarios($empleado, $request);

        return redirect()->route('empleados.index')->with('success', 'Empleado creado correctamente.');
    }

    public function edit(Empleado $empleado)
    {
        $servicios = Servicio::activos()->orderBy('nombre')->get();
        $empleado->load('servicios', 'horarios');

        // Construir mapa dia => horario para la vista
        $horariosPorDia = $empleado->horarios->keyBy('dia');

        // Si el empleado no tiene horarios aún, usar los del salón como base
        $horariosDefault = Horario::orderBy('dia')->get()->map(function ($h) use ($horariosPorDia) {
            return $horariosPorDia->get($h->dia) ?? $h;
        });

        return view('empleados.edit', compact('empleado', 'servicios', 'horariosDefault'));
    }

    public function update(Request $request, Empleado $empleado)
    {
        $data = $request->validate([
            'nombre'       => 'required|string|max:100',
            'especialidad' => 'nullable|string|max:100',
            'foto'         => 'nullable|image|max:2048',
            'servicios'    => 'nullable|array',
            'servicios.*'  => 'exists:servicios,id',
        ]);

        if ($request->hasFile('foto')) {
            if ($empleado->foto) Storage::disk('public')->delete($empleado->foto);
            $data['foto'] = $request->file('foto')->store('empleados', 'public');
        }

        $data['activo'] = $request->boolean('activo', true);
        unset($data['servicios']);

        $empleado->update($data);
        $empleado->servicios()->sync($request->servicios ?? []);
        $this->guardarHorarios($empleado, $request);

        return redirect()->route('empleados.index')->with('success', 'Empleado actualizado correctamente.');
    }

    private function guardarHorarios(Empleado $empleado, Request $request): void
    {
        for ($dia = 1; $dia <= 7; $dia++) {
            $datos  = $request->input("horarios.$dia", []);
            $activo = ! empty($datos['activo']);

            HorarioEmpleado::updateOrCreate(
                ['empleado_id' => $empleado->id, 'dia' => $dia],
                [
                    'hora_apertura' => $datos['hora_apertura'] ?? '09:00',
                    'hora_cierre'   => $datos['hora_cierre']   ?? '18:00',
                    'activo'        => $activo,
                ]
            );
        }
    }

    public function destroy(Empleado $empleado)
    {
        if ($empleado->foto) Storage::disk('public')->delete($empleado->foto);
        $empleado->delete();
        return redirect()->route('empleados.index')->with('success', 'Empleado eliminado.');
    }
}
