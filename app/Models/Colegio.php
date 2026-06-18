<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Colegio extends Model
{
    protected $table = 'colegios';

    protected $fillable = [
        'nombre', 'rfc', 'email', 'telefono', 'director',
        'domicilio', 'logo_path', 'plan', 'precio_mensual',
        'fecha_inicio', 'fecha_vencimiento', 'activo', 'notas',
    ];

    protected $casts = [
        'activo'            => 'boolean',
        'precio_mensual'    => 'decimal:2',
        'fecha_inicio'      => 'date',
        'fecha_vencimiento' => 'date',
    ];

    public function alumnos()           { return $this->hasMany(Alumno::class); }
    public function docentes()          { return $this->hasMany(Docente::class); }
    public function clases()            { return $this->hasMany(Clase::class); }
    public function users()             { return $this->hasMany(User::class); }
    public function nivelColegiaturas() { return $this->hasMany(NivelColegiatura::class); }
    public function colegiaturas()      { return $this->hasMany(Colegiatura::class); }

    public function porVencer(): bool
    {
        return $this->fecha_vencimiento && $this->fecha_vencimiento->diffInDays(now()) <= 30
            && $this->fecha_vencimiento->isFuture();
    }
}
