<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CorteAsistencia extends Model
{
    protected $table = 'cortes_asistencia';

    protected $fillable = [
        'clase_id',
        'fecha',
        'tipo',
        'hora_corte',
        'total_presentes',
        'total_ausentes',
        'total_tardanza',
        'total_justificados',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function clase()
    {
        return $this->belongsTo(Clase::class);
    }

    public function detalles()
    {
        return $this->hasMany(CorteDetalle::class, 'corte_id')->with('alumno');
    }

    public function recalcularTotales(): void
    {
        $this->total_presentes   = $this->detalles()->where('estado', 'presente')->count();
        $this->total_ausentes    = $this->detalles()->where('estado', 'ausente')->count();
        $this->total_tardanza    = $this->detalles()->where('estado', 'tardanza')->count();
        $this->total_justificados = $this->detalles()->where('estado', 'justificado')->count();
        $this->save();
    }
}
