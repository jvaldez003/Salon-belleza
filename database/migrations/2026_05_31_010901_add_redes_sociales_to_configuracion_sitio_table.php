<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('configuracion_sitio', function (Blueprint $table) {
            $table->string('whatsapp')->nullable()->after('direccion');
            $table->string('facebook')->nullable()->after('whatsapp');
            $table->string('instagram')->nullable()->after('facebook');
            $table->string('tiktok')->nullable()->after('instagram');
            $table->string('youtube')->nullable()->after('tiktok');
            $table->string('eslogan')->nullable()->after('nombre_negocio');
        });
    }

    public function down(): void
    {
        Schema::table('configuracion_sitio', function (Blueprint $table) {
            $table->dropColumn(['whatsapp','facebook','instagram','tiktok','youtube','eslogan']);
        });
    }
};
