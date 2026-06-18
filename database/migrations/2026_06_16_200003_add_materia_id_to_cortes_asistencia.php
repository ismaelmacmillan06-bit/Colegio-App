<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cortes_asistencia', function (Blueprint $table) {
            // null = primaria (un corte diario); populated = secundaria (un corte por materia/periodo)
            $table->foreignId('materia_id')
                ->nullable()
                ->after('clase_id')
                ->constrained('materias')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('cortes_asistencia', function (Blueprint $table) {
            $table->dropForeign(['materia_id']);
            $table->dropColumn('materia_id');
        });
    }
};
