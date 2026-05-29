<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('protocolo_medios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('protocolo_id')->constrained('protocolos')->cascadeOnDelete();
            $table->string('tipo', 10);      // imagen | video
            $table->string('url');           // ruta storage (imagen/video) o URL externa (YouTube)
            $table->unsignedSmallInteger('orden')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('protocolo_medios');
    }
};
