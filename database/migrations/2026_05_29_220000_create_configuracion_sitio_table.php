<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('configuracion_sitio', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_negocio')->default('Arrecife');
            $table->string('logo')->nullable();
            $table->string('quienes_somos_titulo')->nullable()->default('Quiénes Somos');
            $table->text('quienes_somos_texto')->nullable();
            $table->string('quienes_somos_imagen')->nullable();
            $table->text('mision_texto')->nullable();
            $table->string('mision_imagen')->nullable();
            $table->text('vision_texto')->nullable();
            $table->string('vision_imagen')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email_contacto')->nullable();
            $table->string('direccion')->nullable();
            $table->timestamps();
        });

        // Registro único (singleton)
        DB::table('configuracion_sitio')->insert([
            'nombre_negocio'        => 'Arrecife',
            'quienes_somos_titulo'  => 'Quiénes Somos',
            'quienes_somos_texto'   => 'Somos un salón de belleza comprometido con realzar tu belleza natural con los mejores tratamientos y profesionales.',
            'mision_texto'          => 'Brindar experiencias de belleza únicas y personalizadas, usando técnicas modernas y productos de alta calidad para que cada cliente se sienta especial.',
            'vision_texto'          => 'Ser el salón de referencia en la región, reconocidos por nuestra excelencia, innovación y el trato cálido a cada uno de nuestros clientes.',
            'created_at'            => now(),
            'updated_at'            => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('configuracion_sitio');
    }
};
