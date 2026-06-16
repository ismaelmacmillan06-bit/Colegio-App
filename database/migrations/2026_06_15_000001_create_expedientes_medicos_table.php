<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expedientes_medicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained('alumnos')->cascadeOnDelete();

            // Datos médicos
            $table->string('tipo_sangre', 5)->nullable();
            $table->text('alergias')->nullable();
            $table->text('condiciones_medicas')->nullable();
            $table->text('medicamentos')->nullable();
            $table->text('restricciones_fisicas')->nullable();

            // Médico tratante
            $table->string('medico_nombre')->nullable();
            $table->string('medico_telefono', 20)->nullable();
            $table->string('medico_cedula', 50)->nullable();

            // Seguro médico
            $table->string('seguro_medico')->nullable();
            $table->string('numero_poliza')->nullable();

            // Certificado
            $table->date('fecha_expedicion')->nullable();
            $table->string('archivo_certificado')->nullable();
            $table->text('notas')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expedientes_medicos');
    }
};
