<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use Illuminate\Http\Request;

class HorarioController extends Controller
{
    public function index()
    {
        $horarios = Horario::orderBy('dia')->get();
        return view('horarios.index', compact('horarios'));
    }

    public function update(Request $request)
    {
        $intervalo = (int) $request->input('intervalo', config('salon.intervalo_minutos', 30));

        for ($dia = 1; $dia <= 7; $dia++) {
            $datos  = $request->input("horarios.$dia", []);
            $activo = !empty($datos['activo']);

            Horario::where('dia', $dia)->update([
                'hora_apertura' => $datos['hora_apertura'] ?? '09:00',
                'hora_cierre'   => $datos['hora_cierre']   ?? '18:00',
                'activo'        => $activo,
            ]);
        }

        // Persist the interval back to .env if changed
        if ($intervalo !== config('salon.intervalo_minutos', 30)) {
            $this->setEnv('SALON_INTERVALO', $intervalo);
        }

        return redirect()->route('horarios.index')->with('success', 'Horarios actualizados correctamente.');
    }

    private function setEnv(string $key, mixed $value): void
    {
        $path    = base_path('.env');
        $content = file_get_contents($path);
        $escaped = preg_quote("$key=", '/');

        if (preg_match("/^$escaped.*/m", $content)) {
            $content = preg_replace("/^$escaped.*/m", "$key=$value", $content);
        } else {
            $content .= "\n$key=$value";
        }

        file_put_contents($path, $content);
    }
}
