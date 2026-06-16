<?php

namespace App\Http\Controllers;

use App\Imports\AlumnosImport;
use App\Imports\ClasesImport;
use App\Imports\DocentesImport;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ImportacionController extends Controller
{
    private array $plantillas = [
        'clases' => [
            'nombre'     => 'plantilla_clases.xlsx',
            'encabezado' => ['Nombre de la Clase', 'Fecha de Fin'],
            'ejemplo'    => ['1°A Primaria', null], // fecha se maneja aparte
            'anchos'     => [35, 18],
        ],
        'alumnos' => [
            'nombre'     => 'plantilla_alumnos.xlsx',
            'encabezado' => ['nombre', 'apellidos', 'clase', 'telefono_padre', 'correo_padre',
                             'nombre_padre', 'telefono_madre', 'correo_madre', 'nombre_madre',
                             'telefono_tutor', 'correo_tutor', 'nombre_tutor'],
            'ejemplo'    => ['Juan', 'Pérez García', '1°A Primaria', '5512345678',
                             'papa@correo.com', 'Carlos Pérez', '5587654321', 'mama@correo.com',
                             'María García', '5598765432', 'tutor@correo.com', 'Roberto García'],
            'anchos'     => [18, 22, 20, 15, 28, 22, 15, 28, 22, 15, 28, 22],
        ],
        'docentes' => [
            'nombre'     => 'plantilla_docentes.xlsx',
            'encabezado' => ['nombre', 'apellidos', 'clase', 'materia', 'telefono'],
            'ejemplo'    => ['Ana', 'González López', '1°A Primaria', 'Matemáticas', '5598765432'],
            'anchos'     => [18, 22, 20, 20, 15],
        ],
    ];

    public function descargarPlantilla($tipo)
    {
        if (! isset($this->plantillas[$tipo])) abort(404);

        $config = $this->plantillas[$tipo];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet()->setTitle('Plantilla');

        // Encabezados
        foreach ($config['encabezado'] as $col => $titulo) {
            $letra = chr(65 + $col);
            $sheet->setCellValue("{$letra}1", $titulo);
        }

        // Estilo encabezado: fondo navy, texto blanco, negrita
        $lastCol = chr(64 + count($config['encabezado']));
        $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '00004E']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Fila de ejemplo
        foreach ($config['ejemplo'] as $col => $valor) {
            if ($valor === null) continue;
            $sheet->setCellValue(chr(65 + $col) . '2', $valor);
        }

        // Fecha de fin para plantilla de clases (columna B)
        if ($tipo === 'clases') {
            $sheet->setCellValue('B2', ExcelDate::PHPToExcel(strtotime('+1 year')));
            $sheet->getStyle('B2')->getNumberFormat()->setFormatCode('DD/MM/YYYY');
        }

        // Anchos de columna
        foreach ($config['anchos'] as $col => $ancho) {
            $sheet->getColumnDimension(chr(65 + $col))->setWidth($ancho);
        }

        $sheet->getRowDimension(1)->setRowHeight(22);

        $writer   = new Xlsx($spreadsheet);
        $filename = $config['nombre'];

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function importar(Request $request, $tipo)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        $importadores = [
            'alumnos'  => AlumnosImport::class,
            'docentes' => DocentesImport::class,
            'clases'   => ClasesImport::class,
        ];

        if (! isset($importadores[$tipo])) abort(404);

        $importador = new $importadores[$tipo]();
        $importador->importar($request->file('archivo')->getRealPath());

        $mensaje = "✅ Se importaron {$importador->importados} registros correctamente.";
        if (! empty($importador->errores)) {
            $mensaje .= " ⚠️ Errores: " . implode(' | ', $importador->errores);
        }

        return redirect()->back()->with('mensaje', $mensaje);
    }
}
