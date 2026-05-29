<?php

namespace Database\Seeders;

use App\Models\Cita;
use App\Models\Servicio;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CitasSeeder extends Seeder
{
    public function run(): void
    {
        $usuario = User::where('email', 'cliente@ejemplo.com')->first()
            ?? User::where('role', 'usuario')->first();

        if (! $usuario) {
            return;
        }

        $servicios = Servicio::take(3)->pluck('id')->toArray();
        if (empty($servicios)) {
            return;
        }

        $total = Servicio::whereIn('id', $servicios)->sum('precio');

        $cita = Cita::create([
            'fecha' => Carbon::tomorrow()->toDateString(),
            'hora' => '10:00:00',
            'user_id' => $usuario->id,
            'total' => $total,
            'estado' => 'confirmada',
        ]);

        $cita->servicios()->sync($servicios);
    }
}
