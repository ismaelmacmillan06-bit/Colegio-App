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
    Schema::create('cuadro_honors', function (Blueprint $table) {
        $table->id();
        $table->string('nombre_alumno');
        $table->string('grado');
        $table->string('grupo');
        $table->string('foto')->nullable();
        $table->string('periodo');
        $table->text('motivo')->nullable();
        $table->integer('orden')->default(0);
        $table->boolean('activo')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuadro_honors');
    }
};
