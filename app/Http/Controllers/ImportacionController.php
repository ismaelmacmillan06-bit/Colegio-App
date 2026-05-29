<?php

namespace App\Http\Controllers;

use App\Imports\AlumnosImport;
use App\Imports\DocentesImport;
use Illuminate\Http\Request;

class ImportacionController extends Controller
{
    public function descargarPlantilla($tipo)
    {
        $plantillas = [
            'alumnos' => [
                'nombre' => 'plantilla_alumnos.csv',
                'encabezado' => ['nombre', 'apellidos', 'clase', 'telefono_padre', 'correo_padre', 'nombre_padre', 'telefono_madre', 'correo_madre', 'nombre_madre', 'telefono_tutor', 'correo_tutor', 'nombre_tutor'],
                'ejemplo' => ['Juan', 'Pérez García', '1°A Primaria', '5512345678', 'papa@correo.com', 'Carlos Pérez', '5587654321', 'mama@correo.com', 'María García', '5598765432', 'tutor@correo.com', 'Roberto García'],
            ],
            'docentes' => [
                'nombre' => 'plantilla_docentes.csv',
                'encabezado' => ['nombre', 'apellidos', 'clase', 'materia', 'telefono'],
                'ejemplo' => ['Ana', 'González López', '1°A Primaria', 'Matemáticas', '5598765432'],
            ],
        ];

        if (!isset($plantillas[$tipo])) {
            abort(404);
        }

        $plantilla = $plantillas[$tipo];

        return response()->streamDownload(function () use ($plantilla) {
            $archivo = fopen('php://output', 'w');
            fputcsv($archivo, $plantilla['encabezado']);
            fputcsv($archivo, $plantilla['ejemplo']);
            fclose($archivo);
        }, $plantilla['nombre'], [
            'Content-Type' => 'text/csv',
        ]);
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