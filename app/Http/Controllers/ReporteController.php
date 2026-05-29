<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Servicio;
use App\Services\ReportePdfService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $desde = $request->get('desde', now()->startOfMonth()->toDateString());
        $hasta = $request->get('hasta', now()->toDateString());

        $citas = Cita::with('servicios')
            ->whereBetween('fecha', [$desde, $hasta])
            ->whereNot('estado', 'cancelada')
            ->get();

        $ingresosPorServicio = DB::table('cita_servicio')
            ->join('servicios', 'servicios.id', '=', 'cita_servicio.servicio_id')
            ->join('citas', 'citas.id', '=', 'cita_servicio.cita_id')
            ->whereBetween('citas.fecha', [$desde, $hasta])
            ->whereNot('citas.estado', 'cancelada')
            ->select('servicios.nombre', DB::raw('COUNT(*) as cantidad'), DB::raw('SUM(servicios.precio) as ingresos'))
            ->groupBy('servicios.id', 'servicios.nombre')
            ->orderByDesc('ingresos')
            ->get();

        $citasPorEstado = Cita::whereBetween('fecha', [$desde, $hasta])
            ->select('estado', DB::raw('COUNT(*) as total'))
            ->groupBy('estado')
            ->pluck('total', 'estado');

        $citasHoy = Cita::delDia()->activas()->count();
        $ingresosPeriodo = $citas->sum('total');
        $clientesUnicos = $citas->pluck('user_id')->unique()->filter()->count();

        return view('reportes.index', compact(
            'desde',
            'hasta',
            'citas',
            'ingresosPorServicio',
            'citasPorEstado',
            'citasHoy',
            'ingresosPeriodo',
            'clientesUnicos'
        ));
    }

    public function exportarCsv(Request $request): StreamedResponse
    {
        $desde = $request->get('desde', now()->startOfMonth()->toDateString());
        $hasta = $request->get('hasta', now()->toDateString());

        $citas = Cita::with(['usuario', 'servicios'])
            ->whereBetween('fecha', [$desde, $hasta])
            ->orderBy('fecha')
            ->get();

        $filename = 'reporte_citas_'.Carbon::parse($desde)->format('Ymd').'_'.Carbon::parse($hasta)->format('Ymd').'.csv';

        return response()->streamDownload(function () use ($citas) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Fecha', 'Hora', 'Cliente', 'Servicios', 'Total', 'Estado']);

            foreach ($citas as $cita) {
                fputcsv($handle, [
                    $cita->id,
                    $cita->fecha->format('Y-m-d'),
                    substr((string) $cita->hora, 0, 5),
                    $cita->usuario?->name ?? 'N/A',
                    $cita->servicios->pluck('nombre')->implode('; '),
                    $cita->total,
                    $cita->estado,
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public function exportarPdf(Request $request)
    {
        $desde = $request->get('desde', now()->startOfMonth()->toDateString());
        $hasta = $request->get('hasta', now()->toDateString());

        $citas = Cita::with('servicios')
            ->whereBetween('fecha', [$desde, $hasta])
            ->whereNot('estado', 'cancelada')
            ->get();

        $lineas = [
            'Periodo: '.$desde.' al '.$hasta,
            'Total citas: '.$citas->count(),
            'Ingresos: $'.number_format($citas->sum('total'), 0, ',', '.'),
            ' ',
        ];

        foreach ($citas->take(25) as $cita) {
            $lineas[] = $cita->fecha->format('d/m/Y').' '.substr((string) $cita->hora, 0, 5)
                .' - $'.number_format($cita->total, 0).' ('.$cita->estado.')';
        }

        $pdf = (new ReportePdfService)->generar('Reporte de Citas - AppSalon', $lineas);

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="reporte_citas.pdf"',
        ]);
    }
}
