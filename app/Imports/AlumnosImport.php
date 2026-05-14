<?php

namespace App\Imports;

use App\Models\Alumno;
use App\Models\Grupo;
use App\Models\Grado;

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
            if (count($fila) < 6) continue;

            [$nombre, $apellidos, $grado, $grupo, $telefono_padre, $telefono_madre, $nombre_padre, $nombre_madre] = array_pad($fila, 8, null);

            $gradoModel = Grado::where('nombre', trim($grado))->first();
            if (!$gradoModel) {
                $this->errores[] = "Grado '{$grado}' no encontrado para {$nombre} {$apellidos}";
                continue;
            }

            $grupoModel = Grupo::where('grado_id', $gradoModel->id)
                ->where('grupo', trim($grupo))
                ->first();

            if (!$grupoModel) {
                $this->errores[] = "Grupo '{$grupo}' no encontrado en grado '{$grado}' para {$nombre} {$apellidos}";
                continue;
            }

            Alumno::create([
                'nombre' => trim($nombre),
                'apellidos' => trim($apellidos),
                'grupo_id' => $grupoModel->id,
                'telefono_padre' => trim($telefono_padre),
                'telefono_madre' => trim($telefono_madre),
                'nombre_padre' => trim($nombre_padre),
                'nombre_madre' => trim($nombre_madre),
                'activo' => true,
            ]);

            $this->importados++;
        }

        fclose($archivo);
    }
}