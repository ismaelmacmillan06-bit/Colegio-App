<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\CredencialConfig;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CredencialController extends Controller
{
    public function alumno(Request $request, Alumno $alumno)
    {
        $responsable = $request->query('responsable', 'padre');
        $config      = CredencialConfig::obtener();

        $pdf = Pdf::loadView('credencial.credencial', [
            'alumno'      => $alumno->load('clase'),
            'responsable' => $responsable,
            'config'      => $config,
        ])
        ->setPaper([0, 0, 243.78, 419.53], 'portrait') // 86mm × 148mm en puntos
        ->setOption('isHtml5ParserEnabled', true)
        ->setOption('isRemoteEnabled', false)
        ->setOption('dpi', 150);

        $nombre = $alumno->nombre . '_' . $alumno->apellidos;
        $nombre = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $nombre);

        return $pdf->download("credencial_{$nombre}.pdf");
    }
}
