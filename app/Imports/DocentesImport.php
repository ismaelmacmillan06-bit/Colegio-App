<?php

namespace App\Imports;

use App\Models\Docente;
use App\Models\Materia;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DocentesImport
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

        // Índice de materias por nombre para asignación rápida
        $materiasIndex = Materia::where('activo', true)
            ->get()
            ->keyBy(fn($m) => mb_strtolower(trim($m->nombre)));

        foreach ($rows as $fila) {
            if (empty(trim((string) ($fila[0] ?? '')))) continue;

            [$nombre, $apellidos, $tipo, $telefono, $materiasTexto] = array_pad($fila, 5, null);

            $tipoValido = in_array($tipo, ['titular', 'especialista', 'extracurricular', 'directivo'])
                ? $tipo
                : 'titular';

            $docente = Docente::create([
                'colegio_id' => $colegioId,
                'nombre'     => trim((string) $nombre),
                'apellidos'  => trim((string) $apellidos),
                'tipo'       => $tipoValido,
                'telefono'   => trim((string) ($telefono ?? '')),
                'activo'     => true,
            ]);

            // Asignar materias si vienen separadas por coma
            if ($materiasTexto) {
                $nombresMateria = array_map('trim', explode(',', (string) $materiasTexto));
                $materiasIds    = [];

                foreach ($nombresMateria as $nombreM) {
                    $key = mb_strtolower($nombreM);
                    if (isset($materiasIndex[$key])) {
                        $materiasIds[] = $materiasIndex[$key]->id;
                    } else {
                        $this->errores[] = "Materia '{$nombreM}' no encontrada para {$nombre} {$apellidos}";
                    }
                }

                if ($materiasIds) {
                    $docente->materias()->sync($materiasIds);
                }
            }

            $this->importados++;
        }
    }
}
