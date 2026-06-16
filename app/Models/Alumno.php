<?php

namespace App\Models;

use App\Models\Scopes\ColegioScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;

#[ScopedBy(ColegioScope::class)]
class Alumno extends Model
{
    protected $table = 'alumnos';

    protected $fillable = [
        'colegio_id', 'clase_id', 'nombre', 'apellidos', 'nfc_uid', 'foto',
        'nombre_padre', 'telefono_padre', 'correo_padre',
        'nombre_madre', 'telefono_madre', 'correo_madre',
        'nombre_tutor', 'telefono_tutor', 'correo_tutor',
        'activo',
    ];

    protected $casts = ['activo' => 'boolean'];

    public function colegio()   { return $this->belongsTo(Colegio::class); }
    public function clase()     { return $this->belongsTo(Clase::class); }
    public function asistencias() { return $this->hasMany(Asistencia::class); }
    public function expedienteMedico() { return $this->hasOne(ExpedienteMedico::class); }
}
