<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Convertir ENUM a string para evitar problemas al agregar nuevos tipos
        DB::statement("ALTER TABLE docentes MODIFY tipo VARCHAR(30) NOT NULL DEFAULT 'titular'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE docentes MODIFY tipo ENUM('titular','especialista','extracurricular','directivo') NOT NULL DEFAULT 'titular'");
    }
};
