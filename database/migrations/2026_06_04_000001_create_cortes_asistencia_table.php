<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cortes_asistencia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clase_id')->constrained('clases')->cascadeOnDelete();
            $table->date('fecha');
            $table->time('hora_corte');
            $table->integer('total_presentes')->default(0);
            $table->integer('total_ausentes')->default(0);
            $table->integer('total_tardanza')->default(0);
            $table->integer('total_justificados')->default(0);
            $table->timestamps();
            $table->unique(['clase_id', 'fecha']); // solo un corte por día por clase
        });

        Schema::create('corte_detalle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('corte_id')->constrained('cortes_asistencia')->cascadeOnDelete();
            $table->foreignId('alumno_id')->constrained('alumnos')->cascadeOnDelete();
            $table->enum('estado', ['presente', 'ausente', 'tardanza', 'justificado']);
            $table->string('nota')->nullable();
            $table->timestamps();
            $table->unique(['corte_id', 'alumno_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('corte_detalle');
        Schema::dropIfExists('cortes_asistencia');
    }
};
