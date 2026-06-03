<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('actividades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clase_id')->constrained('clases')->cascadeOnDelete();
            $table->enum('tipo', ['tarea', 'trabajo_clase', 'proyecto', 'examen', 'extra']);
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->foreignId('materia_id')->nullable()->constrained('materias')->nullOnDelete();
            $table->date('fecha_entrega')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actividades');
    }
};
