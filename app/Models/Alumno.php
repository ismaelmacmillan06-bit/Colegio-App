<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    protected $table = 'alumnos';

protected $fillable = [
    'clase_id',
    'nombre',
    'apellidos',
    'nfc_uid',
    'foto',
    'nombre_padre',
    'telefono_padre',
    'correo_padre',
    'nombre_madre',
    'telefono_madre',
    'correo_madre',
    'nombre_tutor',
    'telefono_tutor',
    'correo_tutor',
    'activo',
];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function clase()
    {
        return $this->belongsTo(Clase::class);
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class);
    }
}