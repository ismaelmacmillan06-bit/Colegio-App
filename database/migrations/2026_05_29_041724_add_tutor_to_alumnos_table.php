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
        $table->string('nombre_tutor')->nullable()->after('correo_madre');
        $table->string('telefono_tutor')->nullable()->after('nombre_tutor');
        $table->string('correo_tutor')->nullable()->after('telefono_tutor');
    });
}

public function down(): void
{
    Schema::table('alumnos', function (Blueprint $table) {
        $table->dropColumn(['nombre_tutor', 'telefono_tutor', 'correo_tutor']);
    });
}
};
