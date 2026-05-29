<?php

namespace App\Imports;

use App\Models\Docente;
use App\Models\Clase;

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

            [$nombre, $apellidos, $clase, $materia, $telefono] = array_pad($fila, 5, null);

            $claseModel = null;
            if ($clase) {
                $claseModel = Clase::where('nombre', trim($clase))->first();
                if (!$claseModel) {
                    $this->errores[] = "Clase '{$clase}' no encontrada para {$nombre} {$apellidos}";
                    continue;
                }
            }

            Docente::create([
                'nombre' => trim($nombre),
                'apellidos' => trim($apellidos),
                'clase_id' => $claseModel?->id,
                'materia' => trim($materia),
                'telefono' => trim($telefono),
                'activo' => true,
            ]);

            $this->importados++;
        }

        fclose($archivo);
    }
}