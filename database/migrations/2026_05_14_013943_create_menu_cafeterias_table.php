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
    Schema::create('menu_cafeterias', function (Blueprint $table) {
        $table->id();
        $table->enum('dia', [
            'Lunes',
            'Martes',
            'Miércoles',
            'Jueves',
            'Viernes'
        ]);
        $table->string('platillo_principal');
        $table->string('sopa')->nullable();
        $table->string('bebida')->nullable();
        $table->string('fruta')->nullable();
        $table->decimal('precio', 8, 2)->nullable();
        $table->boolean('activo')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_cafeterias');
    }
};
