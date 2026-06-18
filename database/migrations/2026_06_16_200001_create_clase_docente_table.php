<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clase_docente', function (Blueprint $table) {
            $table->foreignId('clase_id')->constrained('clases')->cascadeOnDelete();
            $table->foreignId('docente_id')->constrained('docentes')->cascadeOnDelete();
            $table->boolean('es_titular')->default(false);
            $table->timestamps();

            $table->primary(['clase_id', 'docente_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clase_docente');
    }
};
