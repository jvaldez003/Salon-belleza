<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('telefono', 15)->nullable()->after('email');
            $table->boolean('activo')->default(true)->after('role');
        });

        Schema::table('servicios', function (Blueprint $table) {
            $table->unsignedSmallInteger('duracion')->default(60)->after('precio');
            $table->boolean('activo')->default(true)->after('duracion');
        });

        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->time('hora');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('total', 8, 2)->default(0);
            $table->enum('estado', ['pendiente', 'confirmada', 'completada', 'cancelada'])->default('pendiente');
            $table->text('notas')->nullable();
            $table->timestamps();

            $table->index(['fecha', 'hora']);
            $table->index('estado');
        });

        Schema::create('cita_servicio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cita_id')->constrained('citas')->cascadeOnDelete();
            $table->foreignId('servicio_id')->constrained('servicios')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cita_servicio');
        Schema::dropIfExists('citas');

        Schema::table('servicios', function (Blueprint $table) {
            $table->dropColumn(['duracion', 'activo']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['telefono', 'activo']);
        });
    }
};
