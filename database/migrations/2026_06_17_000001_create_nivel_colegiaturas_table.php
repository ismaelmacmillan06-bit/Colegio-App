<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nivel_colegiaturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('colegio_id')->constrained('colegios')->cascadeOnDelete();
            $table->string('nivel', 30); // Maternal, Preescolar, Primaria, etc.
            $table->decimal('monto', 10, 2)->default(0);
            $table->string('tipo_cobro', 20); // Mensual, Bimestral, Semestral
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['colegio_id', 'nivel']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nivel_colegiaturas');
    }
};
