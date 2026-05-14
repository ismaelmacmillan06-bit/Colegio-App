<?php

namespace App\Imports;

use App\Models\Grupo;
use App\Models\Grado;
use App\Models\Docente;

class GruposImport
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

            [$grado, $grupo, $docente_nombre, $total_alumnos] = array_pad($fila, 4, null);

            $gradoModel = Grado::where('nombre', trim($grado))->first();
            if (!$gradoModel) {
                $this->errores[] = "Grado '{$grado}' no encontrado";
                continue;
            }

            $docenteModel = null;
            if ($docente_nombre) {
                $docenteModel = Docente::whereRaw("CONCAT(nombre, ' ', apellidos) LIKE ?", ['%' . trim($docente_nombre) . '%'])->first();
            }

            Grupo::create([
                'grado_id' => $gradoModel->id,
                'docente_id' => $docenteModel?->id,
                'grupo' => trim($grupo),
                'maestro' => trim($docente_nombre),
                'total_alumnos' => intval($total_alumnos) ?? 0,
                'activo' => true,
            ]);

            $this->importados++;
        }

        fclose($archivo);
    }
}