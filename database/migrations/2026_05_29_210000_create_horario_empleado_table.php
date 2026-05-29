<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('horario_empleado', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('dia'); // 1=lunes ... 7=domingo
            $table->time('hora_apertura');
            $table->time('hora_cierre');
            $table->boolean('activo')->default(true);
            $table->unique(['empleado_id', 'dia']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horario_empleado');
    }
};
