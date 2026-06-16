<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpedienteMedico extends Model
{
    protected $table = 'expedientes_medicos';

    protected $fillable = [
        'alumno_id',
        'tipo_sangre',
        'alergias',
        'condiciones_medicas',
        'medicamentos',
        'restricciones_fisicas',
        'medico_nombre',
        'medico_telefono',
        'medico_cedula',
        'seguro_medico',
        'numero_poliza',
        'fecha_expedicion',
        'archivo_certificado',
        'notas',
    ];

    protected $casts = [
        'fecha_expedicion' => 'date',
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }
}
