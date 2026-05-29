<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    protected $table = 'docentes';

    protected $fillable = [
    'clase_id',
    'tipo',
    'nombre',
    'apellidos',
    'materia',
    'foto',
    'telefono',
    'nfc_uid',
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
        return $this->hasMany(AsistenciaDocente::class);
    }
}