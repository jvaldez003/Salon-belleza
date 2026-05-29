<?php

namespace Database\Seeders;

use App\Models\Cita;
use App\Models\Resena;
use App\Models\Servicio;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ResenasSeeder extends Seeder
{
    public function run(): void
    {
        $usuario = User::where('email', 'cliente@ejemplo.com')->first();
        if (! $usuario) {
            return;
        }

        $servicioIds = Servicio::take(2)->pluck('id')->toArray();
        if (empty($servicioIds)) {
            return;
        }

        $total = Servicio::whereIn('id', $servicioIds)->sum('precio');

        $cita = Cita::firstOrCreate(
            [
                'user_id' => $usuario->id,
                'fecha' => Carbon::yesterday()->toDateString(),
                'hora' => '11:00:00',
            ],
            [
                'total' => $total,
                'estado' => 'completada',
            ]
        );

        $cita->servicios()->sync($servicioIds);

        Resena::firstOrCreate(
            ['cita_id' => $cita->id],
            [
                'user_id' => $usuario->id,
                'calificacion' => 5,
                'comentario' => 'Excelente atención, muy profesionales.',
            ]
        );
    }
}
