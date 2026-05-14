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
    Schema::create('alumnos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('grupo_id')->constrained('grupos')->onDelete('cascade');
        $table->string('nombre');
        $table->string('apellidos');
        $table->string('nfc_uid')->unique()->nullable();
        $table->string('foto')->nullable();
        $table->string('telefono_padre')->nullable();
        $table->string('telefono_madre')->nullable();
        $table->string('nombre_padre')->nullable();
        $table->string('nombre_madre')->nullable();
        $table->boolean('activo')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumnos');
    }
};
