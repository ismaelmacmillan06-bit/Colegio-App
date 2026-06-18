<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    protected $fillable = ['nombre', 'campo_formativo', 'orden', 'activo'];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function actividades()
    {
        return $this->hasMany(Actividad::class);
    }

    public function docentes()
    {
        return $this->belongsToMany(Docente::class, 'docente_materia');
    }
}
