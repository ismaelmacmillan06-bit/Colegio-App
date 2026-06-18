<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Clase;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class IdEscolarController extends Controller
{
    public function pdf(int $claseId): Response
    {
        $user  = auth()->user();
        $clase = Clase::where('id', $claseId)
            ->where('colegio_id', $user->colegio_id)
            ->firstOrFail();

        $alumnos = Alumno::withoutGlobalScopes()
            ->select('nombre', 'apellidos', 'codigo_alumno')
            ->where('clase_id', $clase->id)
            ->where('colegio_id', $user->colegio_id)
            ->whereNotNull('codigo_alumno')
            ->orderBy('apellidos')
            ->orderBy('nombre')
            ->get();

        $pdf = Pdf::loadView('pdf.id-escolar', compact('clase', 'alumnos'));
        $pdf->setPaper('a4', 'portrait');

        $filename = 'ID-Escolar-' . str_replace(' ', '_', $clase->nombre) . '.pdf';

        return $pdf->stream($filename);
    }
}
