<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Circular extends Model
{
    protected $table = 'circulares';
    
    protected $fillable = [
        'titulo',
        'descripcion',
        'archivo_pdf',
        'activo',
        'fecha',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'fecha' => 'date',
    ];
}