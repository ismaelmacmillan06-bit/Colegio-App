<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('colegiaturas', function (Blueprint $table) {
            $table->decimal('monto_original', 10, 2)->nullable()->after('monto');
            $table->unsignedTinyInteger('descuento_pct')->default(0)->after('monto_original');
        });
    }

    public function down(): void
    {
        Schema::table('colegiaturas', function (Blueprint $table) {
            $table->dropColumn(['monto_original', 'descuento_pct']);
        });
    }
};
