<?php

namespace App\Http\Controllers;

use App\Imports\AlumnosImport;
use App\Imports\DocentesImport;
use App\Imports\GruposImport;
use Illuminate\Http\Request;

class ImportacionController extends Controller
{
    public function descargarPlantilla($tipo)
    {
        $plantillas = [
            'alumnos' => [
                'nombre' => 'plantilla_alumnos.csv',
                'encabezado' => ['nombre', 'apellidos', 'grado', 'grupo', 'telefono_padre', 'telefono_madre', 'nombre_padre', 'nombre_madre'],
                'ejemplo' => ['Juan', 'Pérez García', '1°', 'A', '5512345678', '5587654321', 'Carlos Pérez', 'María García'],
            ],
            'docentes' => [
                'nombre' => 'plantilla_docentes.csv',
                'encabezado' => ['nombre', 'apellidos', 'materia', 'telefono'],
                'ejemplo' => ['Ana', 'González López', '1ro A Primaria', '5598765432'],
            ],
            'grupos' => [
                'nombre' => 'plantilla_grupos.csv',
                'encabezado' => ['grado', 'grupo', 'docente_nombre_completo', 'total_alumnos'],
                'ejemplo' => ['1°', 'A', 'Ana González López', '30'],
            ],
        ];

        if (!isset($plantillas[$tipo])) {
            abort(404);
        }

        $plantilla = $plantillas[$tipo];

        $response = response()->streamDownload(function () use ($plantilla) {
            $archivo = fopen('php://output', 'w');
            fputcsv($archivo, $plantilla['encabezado']);
            fputcsv($archivo, $plantilla['ejemplo']);
            fclose($archivo);
        }, $plantilla['nombre'], [
            'Content-Type' => 'text/csv',
        ]);

        return $response;
    }

    public function importar(Request $request, $tipo)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $archivo = $request->file('archivo');
        $ruta = $archivo->getRealPath();

        $importadores = [
            'alumnos' => AlumnosImport::class,
            'docentes' => DocentesImport::class,
            'grupos' => GruposImport::class,
        ];

        if (!isset($importadores[$tipo])) {
            abort(404);
        }

        $importador = new $importadores[$tipo]();
        $importador->importar($ruta);

        $mensaje = "Se importaron {$importador->importados} registros correctamente.";
        if (!empty($importador->errores)) {
            $mensaje .= " Errores: " . implode(' | ', $importador->errores);
        }

        return redirect()->back()->with('mensaje', $mensaje);
    }
}