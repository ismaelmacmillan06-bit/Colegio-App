<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    protected $table = 'docentes';

    protected $fillable = [
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

    public function grupos()
    {
        return $this->hasMany(Grupo::class);
    }

    public function asistencias()
    {
        return $this->hasMany(AsistenciaDocente::class);
    }

    public function getNombreCompletoAttribute()
    {
        return $this->nombre . ' ' . $this->apellidos;
    }
}