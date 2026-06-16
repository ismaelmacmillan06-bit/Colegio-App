<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Limpiamos datos existentes sin colegio para empezar limpio
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('calificaciones_actividad')->truncate();
        DB::table('corte_detalle')->truncate();
        DB::table('cortes_asistencia')->truncate();
        DB::table('actividades')->truncate();
        DB::table('expedientes_medicos')->truncate();
        DB::table('asistencias')->truncate();
        DB::table('asistencia_docentes')->truncate();
        DB::table('alumnos')->truncate();
        DB::table('docentes')->truncate();
        DB::table('clases')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        Schema::table('clases', function (Blueprint $table) {
            $table->foreignId('colegio_id')
                ->nullable()
                ->after('id')
                ->constrained('colegios')
                ->cascadeOnDelete();
        });

        Schema::table('alumnos', function (Blueprint $table) {
            $table->foreignId('colegio_id')
                ->nullable()
                ->after('id')
                ->constrained('colegios')
                ->cascadeOnDelete();
        });

        Schema::table('docentes', function (Blueprint $table) {
            $table->foreignId('colegio_id')
                ->nullable()
                ->after('id')
                ->constrained('colegios')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('clases', fn ($t) => $t->dropForeign(['colegio_id']));
        Schema::table('alumnos', fn ($t) => $t->dropForeign(['colegio_id']));
        Schema::table('docentes', fn ($t) => $t->dropForeign(['colegio_id']));

        Schema::table('clases',   fn ($t) => $t->dropColumn('colegio_id'));
        Schema::table('alumnos',  fn ($t) => $t->dropColumn('colegio_id'));
        Schema::table('docentes', fn ($t) => $t->dropColumn('colegio_id'));
    }
};
