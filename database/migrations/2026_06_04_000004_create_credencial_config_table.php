<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('credencial_config', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_escuela')->default('Centro Educativo');
            $table->string('nombre_director')->default('Director General');
            $table->string('cargo_director')->default('Director General');
            $table->string('domicilio')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->string('web')->nullable();
            $table->text('terminos')->nullable();
            $table->string('color_primario')->default('#c0392b');
            $table->string('logo_path')->nullable();
            $table->string('firma_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credencial_config');
    }
};
