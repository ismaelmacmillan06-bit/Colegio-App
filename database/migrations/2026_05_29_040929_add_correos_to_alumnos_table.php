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
    Schema::table('alumnos', function (Blueprint $table) {
        $table->string('correo_padre')->nullable()->after('telefono_padre');
        $table->string('correo_madre')->nullable()->after('telefono_madre');
    });
}

public function down(): void
{
    Schema::table('alumnos', function (Blueprint $table) {
        $table->dropColumn(['correo_padre', 'correo_madre']);
    });
}
};
