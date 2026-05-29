<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seccion_imagenes', function (Blueprint $table) {
            $table->id();
            $table->string('seccion', 20); // quienes_somos | mision | vision
            $table->string('imagen_url');
            $table->unsignedSmallInteger('orden')->default(0);
            $table->timestamps();

            $table->index('seccion');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seccion_imagenes');
    }
};
