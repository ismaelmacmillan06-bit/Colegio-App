<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('docentes', function (Blueprint $table) {
            // clase_id se depreca — la asignación ahora usa la tabla pivot clase_docente
            $table->foreignId('clase_id')->nullable()->change();
            // materia text se depreca — reemplazado por pivot docente_materia
            $table->string('materia')->nullable()->change();
        });
    }

    public function down(): void
    {
        // No revertimos el nullable para evitar pérdida de datos
    }
};
