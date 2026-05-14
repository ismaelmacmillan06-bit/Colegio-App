<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Asistencia;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AsistenciaController extends Controller
{
    public function registrar(Request $request)
    {
        $nfc_uid = $request->input('nfc_uid');

        if (!$nfc_uid) {
            return response()->json([
                'success' => false,
                'mensaje' => 'UID no proporcionado'
            ], 400);
        }

        $alumno = Alumno::where('nfc_uid', $nfc_uid)
            ->where('activo', true)
            ->first();

        if (!$alumno) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Credencial no registrada'
            ], 404);
        }

        $hoy = Carbon::today();
        $ahora = Carbon::now()->format('H:i:s');

        $asistencia = Asistencia::where('alumno_id', $alumno->id)
            ->where('fecha', $hoy)
            ->first();

        if (!$asistencia) {
            Asistencia::create([
                'alumno_id' => $alumno->id,
                'fecha' => $hoy,
                'hora_entrada' => $ahora,
                'estado' => 'presente',
                'notificacion_entrada' => false,
                'notificacion_salida' => false,
            ]);

            return response()->json([
                'success' => true,
                'tipo' => 'entrada',
                'mensaje' => "Bienvenido {$alumno->nombre}",
                'hora' => $ahora,
                'alumno' => $alumno->nombre . ' ' . $alumno->apellidos,
            ]);
        }

        if (!$asistencia->hora_salida) {
            $asistencia->update([
                'hora_salida' => $ahora,
            ]);

            return response()->json([
                'success' => true,
                'tipo' => 'salida',
                'mensaje' => "Hasta mañana {$alumno->nombre}",
                'hora' => $ahora,
                'alumno' => $alumno->nombre . ' ' . $alumno->apellidos,
            ]);
        }

        return response()->json([
            'success' => true,
            'tipo' => 'ya_registrado',
            'mensaje' => "{$alumno->nombre} ya tiene entrada y salida registradas hoy",
        ]);
    }
}