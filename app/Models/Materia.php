<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    protected $fillable = ['nombre', 'nivel', 'activo'];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function actividades()
    {
        return $this->hasMany(Actividad::class);
    }

    public function scopeDeNivel($query, string $nivel)
    {
        return $query->where('nivel', $nivel);
    }
}
