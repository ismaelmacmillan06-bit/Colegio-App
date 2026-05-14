<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuadroHonor extends Model
{
    protected $table = 'cuadro_honors';

    protected $fillable = [
        'nombre_alumno',
        'grado',
        'grupo',
        'foto',
        'periodo',
        'motivo',
        'orden',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];
}