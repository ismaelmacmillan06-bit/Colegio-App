<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuCafeteria extends Model
{
    protected $table = 'menu_cafeterias';

    protected $fillable = [
        'dia',
        'platillo_principal',
        'sopa',
        'bebida',
        'fruta',
        'precio',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'precio' => 'decimal:2',
    ];
}