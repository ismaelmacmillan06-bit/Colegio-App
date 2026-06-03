<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    protected $table = 'actividades';

    protected $fillable = [
        'clase_id',
        'tipo',
        'titulo',
        'descripcion',
        'materia_id',
        'fecha_entrega',
    ];

    protected $casts = [
        'fecha_entrega' => 'date',
    ];

    public function clase()
    {
        return $this->belongsTo(Clase::class);
    }

    public function materia()
    {
        return $this->belongsTo(Materia::class);
    }

    public function calificaciones()
    {
        return $this->hasMany(CalificacionActividad::class);
    }
}
