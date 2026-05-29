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
    Schema::create('asistencias', function (Blueprint $table) {
        $table->id();
        $table->foreignId('alumno_id')->constrained('alumnos')->onDelete('cascade');
        $table->date('fecha');
        $table->time('hora_entrada')->nullable();
        $table->time('hora_salida')->nullable();
        $table->enum('estado', ['presente', 'ausente', 'tardanza'])->default('presente');
        $table->boolean('notificacion_entrada')->default(false);
        $table->boolean('notificacion_salida')->default(false);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};
