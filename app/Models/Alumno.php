<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    protected $table = 'alumnos';

    protected $fillable = [
        'grupo_id',
        'nombre',
        'apellidos',
        'nfc_uid',
        'foto',
        'telefono_padre',
        'telefono_madre',
        'nombre_padre',
        'nombre_madre',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class);
    }
}