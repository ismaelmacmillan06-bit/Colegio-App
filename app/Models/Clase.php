<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clase extends Model
{
    protected $table = 'clases';

    protected $fillable = [
        'nombre',
        'nivel',
        'capacidad',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function alumnos()
    {
        return $this->hasMany(Alumno::class);
    }

    public function docentes()
    {
        return $this->hasMany(Docente::class);
    }

    public function actividades()
    {
        return $this->hasMany(Actividad::class);
    }
}
