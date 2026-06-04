<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CorteDetalle extends Model
{
    protected $table = 'corte_detalle';

    protected $fillable = [
        'corte_id',
        'alumno_id',
        'estado',
        'nota',
    ];

    public function corte()
    {
        return $this->belongsTo(CorteAsistencia::class, 'corte_id');
    }

    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }
}
