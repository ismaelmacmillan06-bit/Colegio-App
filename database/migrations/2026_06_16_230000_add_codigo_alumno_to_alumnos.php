<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alumnos', function (Blueprint $table) {
            $table->string('codigo_alumno', 20)->nullable()->after('apellidos');
        });

        // Backfill existing rows
        $year = date('Y');
        DB::table('alumnos')->whereNull('codigo_alumno')->orderBy('id')->each(function ($row) use ($year) {
            do {
                $code = 'SC' . $year . strtoupper(Str::random(5));
            } while (DB::table('alumnos')->where('codigo_alumno', $code)->exists());

            DB::table('alumnos')->where('id', $row->id)->update(['codigo_alumno' => $code]);
        });

        // Now enforce uniqueness
        Schema::table('alumnos', function (Blueprint $table) {
            $table->unique('codigo_alumno');
        });
    }

    public function down(): void
    {
        Schema::table('alumnos', function (Blueprint $table) {
            $table->dropUnique(['codigo_alumno']);
            $table->dropColumn('codigo_alumno');
        });
    }
};
