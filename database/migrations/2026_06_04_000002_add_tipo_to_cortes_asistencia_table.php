<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cortes_asistencia', function (Blueprint $table) {
            // Primero agregar índice simple en clase_id para que la FK no dependa del unique
            $table->index('clase_id', 'cortes_clase_id_idx');
        });

        Schema::table('cortes_asistencia', function (Blueprint $table) {
            $table->dropUnique('cortes_asistencia_clase_id_fecha_unique');
            $table->enum('tipo', ['entrada', 'salida'])->default('entrada')->after('fecha');
            $table->unique(['clase_id', 'fecha', 'tipo']);
        });
    }

    public function down(): void
    {
        Schema::table('cortes_asistencia', function (Blueprint $table) {
            $table->index('clase_id', 'cortes_clase_id_idx');
        });

        Schema::table('cortes_asistencia', function (Blueprint $table) {
            $table->dropUnique('cortes_asistencia_clase_id_fecha_tipo_unique');
            $table->dropColumn('tipo');
            $table->unique(['clase_id', 'fecha']);
            $table->dropIndex('cortes_clase_id_idx');
        });
    }
};
