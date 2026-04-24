<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiciosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $servicios = [
            ['nombre' => 'Corte de Cabello Hombre', 'precio' => 80],
            ['nombre' => 'Corte de Cabello Mujer', 'precio' => 120],
            ['nombre' => 'Corte de Cabello Niño', 'precio' => 60],
            ['nombre' => 'Peinado Mujer', 'precio' => 80],
            ['nombre' => 'Peinado Hombre', 'precio' => 60],
            ['nombre' => 'Tinte (Color)', 'precio' => 300],
            ['nombre' => 'Uñas de Acrílico', 'precio' => 400],
            ['nombre' => 'Uñas de Gel', 'precio' => 400],
            ['nombre' => 'Manicura', 'precio' => 100],
            ['nombre' => 'Pedicura', 'precio' => 150],
            ['nombre' => 'Maquillaje', 'precio' => 300],
            ['nombre' => 'Tratamiento Capilar', 'precio' => 150],
        ];

        foreach ($servicios as $servicio) {
            \App\Models\Servicio::create($servicio);
        }
    }
}
