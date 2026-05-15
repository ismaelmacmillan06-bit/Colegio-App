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
    Schema::create('asistencia_docentes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('docente_id')->constrained('docentes')->onDelete('cascade');
        $table->date('fecha');
        $table->time('hora_entrada')->nullable();
        $table->time('hora_salida')->nullable();
        $table->enum('estado', ['presente', 'ausente'])->default('presente');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencia_docentes');
    }
};
