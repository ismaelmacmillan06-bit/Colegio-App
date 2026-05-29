<?php

namespace App\Imports;

use App\Models\Alumno;
use App\Models\Clase;

class AlumnosImport
{
    public array $errores = [];
    public int $importados = 0;

    public function importar(string $rutaArchivo): void
    {
        $archivo = fopen($rutaArchivo, 'r');
        stream_filter_append($archivo, 'convert.iconv.windows-1252/utf-8');
        $encabezado = fgetcsv($archivo);

        while (($fila = fgetcsv($archivo)) !== false) {
            if (count($fila) < 3) continue;

            [$nombre, $apellidos, $clase, $telefono_padre, $correo_padre, $nombre_padre,
             $telefono_madre, $correo_madre, $nombre_madre,
             $telefono_tutor, $correo_tutor, $nombre_tutor] = array_pad($fila, 12, null);

            $claseModel = Clase::where('nombre', trim($clase))->first();
            if (!$claseModel) {
                $this->errores[] = "Clase '{$clase}' no encontrada para {$nombre} {$apellidos}";
                continue;
            }

            Alumno::create([
                'nombre' => trim($nombre),
                'apellidos' => trim($apellidos),
                'clase_id' => $claseModel->id,
                'nombre_padre' => trim($nombre_padre),
                'telefono_padre' => trim($telefono_padre),
                'correo_padre' => trim($correo_padre),
                'nombre_madre' => trim($nombre_madre),
                'telefono_madre' => trim($telefono_madre),
                'correo_madre' => trim($correo_madre),
                'nombre_tutor' => trim($nombre_tutor),
                'telefono_tutor' => trim($telefono_tutor),
                'correo_tutor' => trim($correo_tutor),
                'activo' => true,
            ]);

            $this->importados++;
        }

        fclose($archivo);
    }
}