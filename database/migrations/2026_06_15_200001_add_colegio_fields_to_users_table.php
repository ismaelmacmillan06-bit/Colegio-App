<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('colegio_id')
                ->nullable()
                ->after('id')
                ->constrained('colegios')
                ->nullOnDelete();

            $table->boolean('is_super_admin')->default(false)->after('colegio_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['colegio_id']);
            $table->dropColumn(['colegio_id', 'is_super_admin']);
        });
    }
};
