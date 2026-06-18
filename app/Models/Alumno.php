<?php

namespace App\Models;

use App\Models\Scopes\ColegioScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

#[ScopedBy(ColegioScope::class)]
class Alumno extends Model
{
    protected $table = 'alumnos';

    protected $fillable = [
        'colegio_id', 'clase_id', 'nombre', 'apellidos', 'codigo_alumno', 'nfc_uid', 'foto',
        'nombre_padre', 'telefono_padre', 'correo_padre',
        'nombre_madre', 'telefono_madre', 'correo_madre',
        'nombre_tutor', 'telefono_tutor', 'correo_tutor',
        'activo',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $alumno) {
            if (empty($alumno->codigo_alumno)) {
                do {
                    $code = 'SC' . date('Y') . strtoupper(Str::random(5));
                } while (static::withoutGlobalScopes()->where('codigo_alumno', $code)->exists());

                $alumno->codigo_alumno = $code;
            }
        });

        static::created(function (self $alumno) {
            if (! $alumno->colegio_id || ! $alumno->clase_id) return;

            $clase = Clase::withoutGlobalScopes()->find($alumno->clase_id);
            if (! $clase || ! $clase->nivel) return;

            $config = NivelColegiatura::withoutGlobalScopes()
                ->where('colegio_id', $alumno->colegio_id)
                ->where('nivel', $clase->nivel)
                ->where('activo', true)
                ->first();

            if (! $config) return;

            Colegiatura::withoutGlobalScopes()->create([
                'alumno_id'             => $alumno->id,
                'colegio_id'            => $alumno->colegio_id,
                'nivel_colegiatura_id'  => $config->id,
                'periodo'               => Colegiatura::generarPeriodo($config->tipo_cobro),
                'monto'                 => $config->monto,
                'tipo_cobro'            => $config->tipo_cobro,
                'status'                => 'pendiente',
                'fecha_vencimiento'     => Colegiatura::calcularVencimiento($config->tipo_cobro),
            ]);
        });
    }

    protected $casts = ['activo' => 'boolean'];

    public function colegio()          { return $this->belongsTo(Colegio::class); }
    public function clase()            { return $this->belongsTo(Clase::class); }
    public function asistencias()      { return $this->hasMany(Asistencia::class); }
    public function expedienteMedico() { return $this->hasOne(ExpedienteMedico::class); }
    public function colegiaturas()     { return $this->hasMany(Colegiatura::class); }
}
