<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('docente_materia', function (Blueprint $table) {
            $table->foreignId('docente_id')->constrained('docentes')->cascadeOnDelete();
            $table->foreignId('materia_id')->constrained('materias')->cascadeOnDelete();

            $table->primary(['docente_id', 'materia_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('docente_materia');
    }
};
