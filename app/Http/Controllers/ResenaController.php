<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResenaRequest;
use App\Models\Cita;
use App\Models\Resena;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResenaController extends Controller
{
    public function historial(Request $request)
    {
        $query = Cita::with(['servicios', 'resena'])
            ->where('estado', 'completada')
            ->orderByDesc('fecha')
            ->orderByDesc('hora');

        if (! auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        } elseif ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->whereHas('usuario', fn ($q) => $q->where('name', 'like', "%{$buscar}%"));
        }

        $citas = $query->paginate(10)->withQueryString();

        return view('resenas.historial', compact('citas'));
    }

    public function create(Cita $cita)
    {
        $this->autorizarResena($cita);

        if ($cita->resena) {
            return redirect()->route('resenas.historial')->with('success', 'Esta cita ya tiene una reseña.');
        }

        $cita->load('servicios');

        return view('resenas.create', compact('cita'));
    }

    public function store(ResenaRequest $request, Cita $cita)
    {
        $this->autorizarResena($cita);

        if ($cita->resena) {
            return redirect()->route('resenas.historial')->with('success', 'Esta cita ya tiene una reseña.');
        }

        Resena::create([
            'cita_id' => $cita->id,
            'user_id' => auth()->id(),
            'calificacion' => $request->calificacion,
            'comentario' => $request->comentario,
        ]);

        return redirect()->route('resenas.historial')->with('success', '¡Gracias por tu reseña!');
    }

    public function adminIndex()
    {
        $resenas = Resena::with(['usuario', 'cita.servicios'])
            ->latest()
            ->paginate(15);

        $promedioPorServicio = DB::table('resenas')
            ->join('citas', 'citas.id', '=', 'resenas.cita_id')
            ->join('cita_servicio', 'cita_servicio.cita_id', '=', 'citas.id')
            ->join('servicios', 'servicios.id', '=', 'cita_servicio.servicio_id')
            ->select(
                'servicios.id',
                'servicios.nombre',
                DB::raw('ROUND(AVG(resenas.calificacion), 1) as promedio'),
                DB::raw('COUNT(resenas.id) as total_resenas')
            )
            ->groupBy('servicios.id', 'servicios.nombre')
            ->orderByDesc('promedio')
            ->get();

        $promedioGeneral = Resena::avg('calificacion');

        return view('resenas.admin', compact('resenas', 'promedioPorServicio', 'promedioGeneral'));
    }

    protected function autorizarResena(Cita $cita): void
    {
        if ($cita->estado !== 'completada') {
            abort(403, 'Solo puedes reseñar citas completadas.');
        }

        if ($cita->user_id !== auth()->id()) {
            abort(403, 'Solo puedes reseñar tus propias citas.');
        }
    }
}
