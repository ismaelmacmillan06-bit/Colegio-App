<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calificaciones_actividad', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actividad_id')->constrained('actividades')->cascadeOnDelete();
            $table->foreignId('alumno_id')->constrained('alumnos')->cascadeOnDelete();
            $table->integer('calificacion')->nullable(); // null=pendiente, 0=no entregó, 1-10=calificación
            $table->timestamps();
            $table->unique(['actividad_id', 'alumno_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calificaciones_actividad');
    }
};
