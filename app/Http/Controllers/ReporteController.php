<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Services\ReportePdfService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReporteController extends Controller
{
    private const ESTADOS = [
        'pendiente'  => 'Pendiente',
        'confirmada' => 'Confirmada',
        'completada' => 'Completada',
        'cancelada'  => 'Cancelada',
    ];

    public function index(Request $request)
    {
        [$desde, $hasta] = $this->periodo($request);

        $citas = Cita::whereBetween('fecha', [$desde, $hasta])->get(['id', 'user_id', 'estado', 'total']);
        $validas = $citas->where('estado', '!=', 'cancelada');

        $resumen = [
            'citas'       => $validas->count(),
            'realizado'   => (float) $validas->where('estado', 'completada')->sum('total'),
            'agendado'    => (float) $validas->whereIn('estado', ['pendiente', 'confirmada'])->sum('total'),
            'canceladas'  => $citas->where('estado', 'cancelada')->count(),
            'clientes'    => $validas->pluck('user_id')->filter()->unique()->count(),
        ];

        $porEstado = collect(self::ESTADOS)->map(fn ($nombre, $clave) => [
            'nombre' => $nombre,
            'total'  => $citas->where('estado', $clave)->count(),
        ]);

        $base = fn () => DB::table('cita_servicio')
            ->join('citas', 'citas.id', '=', 'cita_servicio.cita_id')
            ->join('servicios', 'servicios.id', '=', 'cita_servicio.servicio_id')
            ->whereBetween('citas.fecha', [$desde, $hasta])
            ->where('citas.estado', '!=', 'cancelada');

        $porServicio = $base()
            ->select('servicios.id', 'servicios.nombre', DB::raw('COUNT(*) as cantidad'), DB::raw('SUM(servicios.precio) as ingresos'))
            ->groupBy('servicios.id', 'servicios.nombre')
            ->orderByDesc('ingresos')
            ->get();

        $porEmpleado = $base()
            ->leftJoin('empleados', 'empleados.id', '=', 'cita_servicio.empleado_id')
            ->select(DB::raw("COALESCE(empleados.nombre, 'Sin preferencia') as nombre"), DB::raw('COUNT(*) as cantidad'))
            ->groupBy('empleados.id', 'empleados.nombre')
            ->orderByDesc('cantidad')
            ->get();

        $atajos = [
            'Este mes'         => [now()->startOfMonth(), now()->endOfMonth()],
            'Mes pasado'       => [now()->subMonthNoOverflow()->startOfMonth(), now()->subMonthNoOverflow()->endOfMonth()],
            'Últimos 30 días'  => [now()->subDays(29), now()],
            'Este año'         => [now()->startOfYear(), now()->endOfYear()],
        ];

        return view('reportes.index', compact('desde', 'hasta', 'resumen', 'porEstado', 'porServicio', 'porEmpleado', 'atajos'));
    }

    public function exportarCsv(Request $request): StreamedResponse
    {
        [$desde, $hasta] = $this->periodo($request);
        $citas = $this->citasDetalle($desde, $hasta);
        $archivo = 'arrecife_citas_' . str_replace('-', '', $desde) . '_' . str_replace('-', '', $hasta) . '.csv';

        return response()->streamDownload(function () use ($citas) {
            $h = fopen('php://output', 'w');
            fwrite($h, "\xEF\xBB\xBF"); // BOM: Excel abre bien las tildes
            fputcsv($h, ['Cita', 'Fecha', 'Hora', 'Cliente', 'Correo', 'Servicios', 'Total', 'Estado'], ';');
            foreach ($citas as $c) {
                fputcsv($h, [
                    $c->id,
                    $c->fecha->format('d/m/Y'),
                    substr((string) $c->hora, 0, 5),
                    $c->usuario?->name ?? 'Sin cliente',
                    $c->usuario?->email ?? '',
                    $c->servicios->map->nombre_publico->implode(', '),
                    (int) round($c->total),
                    self::ESTADOS[$c->estado] ?? $c->estado,
                ], ';');
            }
            fclose($h);
        }, $archivo, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function exportarPdf(Request $request)
    {
        [$desde, $hasta] = $this->periodo($request);
        $citas   = $this->citasDetalle($desde, $hasta);
        $validas = $citas->where('estado', '!=', 'cancelada');
        $moneda  = fn ($v) => '$' . number_format($v, 0, ',', '.');
        $fecha   = fn ($f) => Carbon::parse($f)->translatedFormat('j \d\e F \d\e Y');

        $lineas = [
            'Período: ' . $fecha($desde) . ' al ' . $fecha($hasta),
            'Citas (sin canceladas): ' . $validas->count() . '   Canceladas: ' . $citas->where('estado', 'cancelada')->count(),
            'Realizado (completadas): ' . $moneda($validas->where('estado', 'completada')->sum('total')),
            'Agendado (pendientes y confirmadas): ' . $moneda($validas->whereIn('estado', ['pendiente', 'confirmada'])->sum('total')),
            '',
        ];
        foreach ($citas as $c) {
            $lineas[] = $c->fecha->format('d/m/Y') . '  ' . substr((string) $c->hora, 0, 5)
                . '  ' . str_pad(self::ESTADOS[$c->estado] ?? $c->estado, 11)
                . '  ' . $moneda($c->total)
                . '  ' . mb_strimwidth(($c->usuario?->name ?? 'Sin cliente') . ' - ' . $c->servicios->map->nombre_publico->implode(', '), 0, 70, '...');
        }
        if ($citas->isEmpty()) $lineas[] = 'No hay citas en este período.';

        $pdf = (new ReportePdfService)->generar('Arrecife Beauty Studio - Reporte de citas', $lineas);

        return response($pdf, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="arrecife_citas_' . str_replace('-', '', $desde) . '_' . str_replace('-', '', $hasta) . '.pdf"',
        ]);
    }

    /** Lee "desde" y "hasta" (por defecto el mes actual). Si vienen al revés, los ordena. */
    private function periodo(Request $request): array
    {
        $leer = function ($valor, Carbon $defecto) {
            try {
                return $valor ? Carbon::createFromFormat('Y-m-d', $valor)->startOfDay() : $defecto;
            } catch (\Throwable) {
                return $defecto;
            }
        };
        $desde = $leer($request->get('desde'), now()->startOfMonth());
        $hasta = $leer($request->get('hasta'), now()->endOfMonth());
        if ($desde->gt($hasta)) [$desde, $hasta] = [$hasta, $desde];

        return [$desde->toDateString(), $hasta->toDateString()];
    }

    private function citasDetalle(string $desde, string $hasta)
    {
        return Cita::with(['usuario', 'servicios'])
            ->whereBetween('fecha', [$desde, $hasta])
            ->orderBy('fecha')->orderBy('hora')
            ->get();
    }
}
