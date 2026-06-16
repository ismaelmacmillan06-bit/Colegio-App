<?php

namespace App\Models;

use App\Models\Scopes\ColegioScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;

#[ScopedBy(ColegioScope::class)]
class Clase extends Model
{
    protected $table = 'clases';

    protected $fillable = ['colegio_id', 'nombre', 'nivel', 'capacidad', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    public function colegio()    { return $this->belongsTo(Colegio::class); }
    public function alumnos()    { return $this->hasMany(Alumno::class); }
    public function docentes()   { return $this->hasMany(Docente::class); }
    public function actividades(){ return $this->hasMany(Actividad::class); }
}
