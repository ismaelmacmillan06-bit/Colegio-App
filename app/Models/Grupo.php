<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $table = 'grupos';

    protected $fillable = [
        'grado_id',
        'grupo',
        'maestro',
        'total_alumnos',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function grado()
    {
        return $this->belongsTo(Grado::class);
    }

    public function alumnos()
    {
        return $this->hasMany(Alumno::class);
    }
}