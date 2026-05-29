<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    protected $table = 'asistencias';

    protected $fillable = [
        'alumno_id',
        'fecha',
        'hora_entrada',
        'hora_salida',
        'estado',
        'notificacion_entrada',
        'notificacion_salida',
    ];

    protected $casts = [
        'notificacion_entrada' => 'boolean',
        'notificacion_salida' => 'boolean',
        'fecha' => 'date',
    ];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }
}