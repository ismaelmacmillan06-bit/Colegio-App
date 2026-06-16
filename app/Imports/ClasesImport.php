<?php

namespace App\Imports;

use App\Models\Clase;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class ClasesImport
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
            $nombre = trim((string) ($fila[0] ?? ''));
            if (empty($nombre)) continue;

            $rawFecha  = $fila[1] ?? null;
            $fechaFin  = null;

            if ($rawFecha) {
                try {
                    if (is_numeric($rawFecha)) {
                        $fechaFin = ExcelDate::excelToDateTimeObject($rawFecha)->format('Y-m-d');
                    } else {
                        $fechaFin = Carbon::parse($rawFecha)->format('Y-m-d');
                    }
                } catch (\Exception $e) {
                    $fechaFin = null;
                }
            }

            Clase::create([
                'colegio_id' => $colegioId,
                'nombre'     => $nombre,
                'fecha_fin'  => $fechaFin,
                'activo'     => true,
            ]);

            $this->importados++;
        }
    }
}
