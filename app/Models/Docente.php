<?php

namespace App\Models;

use App\Models\Scopes\ColegioScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;

#[ScopedBy(ColegioScope::class)]
class Docente extends Model
{
    protected $table = 'docentes';

    protected $fillable = [
        'colegio_id', 'clase_id', 'tipo', 'nombre', 'apellidos',
        'materia', 'foto', 'telefono', 'nfc_uid', 'activo',
    ];

    protected $casts = ['activo' => 'boolean'];

    public function colegio()    { return $this->belongsTo(Colegio::class); }
    public function asistencias(){ return $this->hasMany(AsistenciaDocente::class); }

    public function clases()
    {
        return $this->belongsToMany(Clase::class, 'clase_docente')
            ->withPivot('es_titular')
            ->withTimestamps();
    }

    public function materias()
    {
        return $this->belongsToMany(Materia::class, 'docente_materia');
    }
}
