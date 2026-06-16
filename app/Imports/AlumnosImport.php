<?php

namespace App\Imports;

use App\Models\Alumno;
use App\Models\Clase;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AlumnosImport
{
    public array $errores = [];
    public int $importados = 0;

    public function importar(string $rutaArchivo): void
    {
        $spreadsheet = IOFactory::load($rutaArchivo);
        $sheet       = $spreadsheet->getActiveSheet();
        $rows        = $sheet->toArray(null, true, true, false);

        array_shift($rows); // quitar encabezado

        $colegioId = auth()->user()?->colegio_id;

        foreach ($rows as $fila) {
            if (empty(trim((string) ($fila[0] ?? '')))) continue;

            [
                $nombre, $apellidos, $clase,
                $telefono_padre, $correo_padre, $nombre_padre,
                $telefono_madre, $correo_madre, $nombre_madre,
                $telefono_tutor, $correo_tutor, $nombre_tutor,
            ] = array_pad($fila, 12, null);

            $claseModel = Clase::where('nombre', trim((string) $clase))
                ->where('colegio_id', $colegioId)
                ->first();

            if (! $claseModel) {
                $this->errores[] = "Clase '{$clase}' no encontrada para {$nombre} {$apellidos}";
                continue;
            }

            Alumno::create([
                'colegio_id'      => $colegioId,
                'clase_id'        => $claseModel->id,
                'nombre'          => trim((string) $nombre),
                'apellidos'       => trim((string) $apellidos),
                'nombre_padre'    => trim((string) $nombre_padre),
                'telefono_padre'  => trim((string) $telefono_padre),
                'correo_padre'    => trim((string) $correo_padre),
                'nombre_madre'    => trim((string) $nombre_madre),
                'telefono_madre'  => trim((string) $telefono_madre),
                'correo_madre'    => trim((string) $correo_madre),
                'nombre_tutor'    => trim((string) $nombre_tutor),
                'telefono_tutor'  => trim((string) $telefono_tutor),
                'correo_tutor'    => trim((string) $correo_tutor),
                'activo'          => true,
            ]);

            $this->importados++;
        }
    }
}
