<?php

namespace App\Models;

use App\Models\Scopes\ColegioScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;

#[ScopedBy(ColegioScope::class)]
class NivelColegiatura extends Model
{
    protected $table = 'nivel_colegiaturas';

    protected $fillable = ['colegio_id', 'nivel', 'monto', 'tipo_cobro', 'activo'];

    protected $casts = [
        'monto'  => 'decimal:2',
        'activo' => 'boolean',
    ];

    public function colegio() { return $this->belongsTo(Colegio::class); }
    public function colegiaturas() { return $this->hasMany(Colegiatura::class); }
}
