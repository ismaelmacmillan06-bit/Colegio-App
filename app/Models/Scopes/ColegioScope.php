<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ColegioScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        // Solo aplica el filtro si hay un usuario autenticado con colegio asignado.
        // Super admins (colegio_id = null) ven todo sin restricción.
        if (auth()->check() && auth()->user()->colegio_id) {
            $builder->where($model->getTable() . '.colegio_id', auth()->user()->colegio_id);
        }
    }
}
