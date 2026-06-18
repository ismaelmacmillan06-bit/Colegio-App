<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Limpiar FK dependientes antes de truncar materias
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('actividades')->update(['materia_id' => null]);
        DB::table('materias')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        Schema::table('materias', function (Blueprint $table) {
            $table->dropColumn('nivel');
            $table->string('campo_formativo')->after('nombre');
            $table->unsignedTinyInteger('orden')->default(0)->after('campo_formativo');
        });

        // Catálogo SEP (campos formativos Plan 2022)
        DB::table('materias')->insert([
            ['nombre' => 'Español',           'campo_formativo' => 'Lenguajes',                        'orden' => 1, 'activo' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Inglés',            'campo_formativo' => 'Lenguajes',                        'orden' => 2, 'activo' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Artes',             'campo_formativo' => 'Lenguajes',                        'orden' => 3, 'activo' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Matemáticas',       'campo_formativo' => 'Saberes y Pensamiento Científico', 'orden' => 4, 'activo' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Química',           'campo_formativo' => 'Saberes y Pensamiento Científico', 'orden' => 5, 'activo' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Historia',          'campo_formativo' => 'Naturaleza y Comunidad',           'orden' => 6, 'activo' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Formación Cívica',  'campo_formativo' => 'Naturaleza y Comunidad',           'orden' => 7, 'activo' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Educación Física',  'campo_formativo' => 'De lo Humano y lo Comunitario',   'orden' => 8, 'activo' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Tecnología',        'campo_formativo' => 'De lo Humano y lo Comunitario',   'orden' => 9, 'activo' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::table('materias', function (Blueprint $table) {
            $table->dropColumn(['campo_formativo', 'orden']);
            $table->string('nivel')->default('Primaria');
        });
    }
};
