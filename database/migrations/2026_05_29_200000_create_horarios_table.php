<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('dia')->unique(); // 1=lunes ... 7=domingo
            $table->time('hora_apertura');
            $table->time('hora_cierre');
            $table->boolean('activo')->default(true);
        });

        $apertura = config('salon.hora_apertura', '09:00');
        $cierre   = config('salon.hora_cierre',   '18:00');
        $laborales = config('salon.dias_laborales', [1, 2, 3, 4, 5, 6]);

        for ($dia = 1; $dia <= 7; $dia++) {
            DB::table('horarios')->insert([
                'dia'           => $dia,
                'hora_apertura' => $apertura,
                'hora_cierre'   => $cierre,
                'activo'        => in_array($dia, $laborales),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};
