<?php

namespace App\Imports;

use App\Models\Docente;

class DocentesImport
{
    public array $errores = [];
    public int $importados = 0;

    public function importar(string $rutaArchivo): void
    {
        $archivo = fopen($rutaArchivo, 'r');
        stream_filter_append($archivo, 'convert.iconv.windows-1252/utf-8');
        $encabezado = fgetcsv($archivo);

        while (($fila = fgetcsv($archivo)) !== false) {
            if (count($fila) < 2) continue;

            [$nombre, $apellidos, $materia, $telefono] = array_pad($fila, 4, null);

            Docente::create([
                'nombre' => trim($nombre),
                'apellidos' => trim($apellidos),
                'materia' => trim($materia),
                'telefono' => trim($telefono),
                'activo' => true,
            ]);

            $this->importados++;
        }

        fclose($archivo);
    }
}