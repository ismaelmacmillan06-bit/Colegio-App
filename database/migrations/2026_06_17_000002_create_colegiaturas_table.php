<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('colegiaturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained('alumnos')->cascadeOnDelete();
            $table->foreignId('colegio_id')->constrained('colegios')->cascadeOnDelete();
            $table->foreignId('nivel_colegiatura_id')->nullable()->constrained('nivel_colegiaturas')->nullOnDelete();
            $table->string('periodo', 30);        // "Enero 2026", "Bim. 1/2026", etc.
            $table->decimal('monto', 10, 2);
            $table->string('tipo_cobro', 20);     // copia del config en el momento
            $table->string('status', 20)->default('pendiente'); // pendiente|pagada|declinada|vencida
            $table->date('fecha_vencimiento')->nullable();
            $table->date('fecha_pago')->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();

            $table->index(['colegio_id', 'status']);
            $table->index(['alumno_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('colegiaturas');
    }
};
