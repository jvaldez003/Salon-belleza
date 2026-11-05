<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Empleado;
use App\Models\Horario;
use App\Models\HorarioEmpleado;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Validator;

class EmpleadoController extends Controller
{
    public function index()
    {
        $empleados = Empleado::with('servicios', 'horarios')->orderByDesc('activo')->orderBy('nombre')->get();
        $salon     = Horario::orderBy('dia')->get()->keyBy('dia');

        return view('empleados.index', compact('empleados', 'salon'));
    }

    public function create()
    {
        return view('empleados.create', $this->datosFormulario(null));
    }

    public function store(Request $request)
    {
        $data = $this->validar($request);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('empleados', 'public');
        }

        $empleado = Empleado::create($data);
        $empleado->servicios()->sync($request->input('servicios', []));
        $this->guardarHorarios($empleado, $request);

        return redirect()->route('empleados.index')->with('success', "Agregaste a {$empleado->nombre} al equipo.");
    }

    public function edit(Empleado $empleado)
    {
        $empleado->load('servicios', 'horarios');

        return view('empleados.edit', $this->datosFormulario($empleado));
    }

    public function update(Request $request, Empleado $empleado)
    {
        $data = $this->validar($request);

        if ($request->hasFile('foto')) {
            if ($empleado->foto) Storage::disk('public')->delete($empleado->foto);
            $data['foto'] = $request->file('foto')->store('empleados', 'public');
        } elseif ($request->boolean('quitar_foto') && $empleado->foto) {
            Storage::disk('public')->delete($empleado->foto);
            $data['foto'] = null;
        }

        $empleado->update($data);
        $empleado->servicios()->sync($request->input('servicios', []));
        $this->guardarHorarios($empleado, $request);

        return redirect()->route('empleados.index')->with('success', "Guardaste los cambios de {$empleado->nombre}.");
    }

    public function destroy(Empleado $empleado)
    {
        $nombre = $empleado->nombre;
        if ($empleado->foto) Storage::disk('public')->delete($empleado->foto);
        $empleado->delete();

        return redirect()->route('empleados.index')->with('success', "Eliminaste a {$nombre} del equipo.");
    }

    /** Servicios agrupados por categoría (todos, también los ocultos, para no perder asignaciones al guardar). */
    private function datosFormulario(?Empleado $empleado): array
    {
        $servicios  = Servicio::orderBy('nombre')->get();
        $categorias = Categoria::orderBy('nombre')->get();
        $salon      = Horario::orderBy('dia')->get();
        $propios    = $empleado?->horarios->keyBy('dia') ?? collect();

        // Si el empleado aún no tiene horario para un día, se parte del horario del salón.
        $horariosDefault = $salon->map(fn ($h) => $propios->get($h->dia) ?? $h);

        return compact('empleado', 'servicios', 'categorias', 'salon', 'horariosDefault');
    }

    private function validar(Request $request): array
    {
        $validator = validator($request->all(), [
            'nombre'                   => 'required|string|max:100',
            'especialidad'             => 'nullable|string|max:100',
            'foto'                     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'servicios'                => 'nullable|array',
            'servicios.*'              => 'exists:servicios,id',
            'horarios'                 => 'nullable|array',
            'horarios.*.hora_apertura' => 'nullable|date_format:H:i',
            'horarios.*.hora_cierre'   => 'nullable|date_format:H:i',
        ], [
            'nombre.required' => 'Escribe el nombre.',
            'foto.image'      => 'El archivo debe ser una foto.',
            'foto.mimes'      => 'La foto debe ser JPG, PNG o WebP.',
            'foto.max'        => 'La foto pesa más de 5 MB. Elige una más liviana.',
            '*.date_format'   => 'Escribe la hora como 09:00.',
        ]);

        $validator->after(fn (Validator $v) => self::revisarHoras($v, $request->input('horarios', [])));
        $validator->validate();

        return [
            'nombre'       => $request->input('nombre'),
            'especialidad' => $request->input('especialidad'),
            'activo'       => $request->boolean('activo'),
        ];
    }

    /** La hora de salida debe ser posterior a la de entrada en los días marcados. */
    public static function revisarHoras(Validator $v, array $horarios): void
    {
        foreach ($horarios as $dia => $h) {
            if (empty($h['activo']) || empty($h['hora_apertura']) || empty($h['hora_cierre'])) continue;
            if ($h['hora_cierre'] <= $h['hora_apertura']) {
                $v->errors()->add("horarios.$dia", Horario::nombreDia((int) $dia) . ': la hora final debe ser después de la inicial.');
            }
        }
    }

    private function guardarHorarios(Empleado $empleado, Request $request): void
    {
        for ($dia = 1; $dia <= 7; $dia++) {
            $datos = $request->input("horarios.$dia", []);

            HorarioEmpleado::updateOrCreate(
                ['empleado_id' => $empleado->id, 'dia' => $dia],
                [
                    'hora_apertura' => $datos['hora_apertura'] ?? '09:00',
                    'hora_cierre'   => $datos['hora_cierre']   ?? '18:00',
                    'activo'        => ! empty($datos['activo']),
                ]
            );
        }
    }
}
