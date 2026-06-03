<?php

namespace Database\Seeders;

use App\Models\Materia;
use Illuminate\Database\Seeder;

class MateriasSeeder extends Seeder
{
    public function run(): void
    {
        $materiasPrimaria = [
            'Lecto Escritura',
            'Matemáticas',
            'Naturaleza y Comunidad',
            'Pensamiento Científico',
        ];

        foreach ($materiasPrimaria as $nombre) {
            Materia::firstOrCreate(
                ['nombre' => $nombre, 'nivel' => 'Primaria'],
                ['activo' => true]
            );
        }
    }
}
