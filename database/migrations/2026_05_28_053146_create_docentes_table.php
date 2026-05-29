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
    Schema::create('docentes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('clase_id')->nullable()->constrained('clases')->onDelete('set null');
        $table->string('nombre');
        $table->string('apellidos');
        $table->string('materia')->nullable();
        $table->string('foto')->nullable();
        $table->string('telefono')->nullable();
        $table->string('nfc_uid')->unique()->nullable();
        $table->boolean('activo')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docentes');
    }
};
