<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Docente;
use App\Models\Asistencia;
use App\Models\AsistenciaDocente;
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

        $alumno = Alumno::where('nfc_uid', $nfc_uid)->where('activo', true)->first();
        if ($alumno) {
            return $this->registrarAlumno($alumno);
        }

        $docente = Docente::where('nfc_uid', $nfc_uid)->where('activo', true)->first();
        if ($docente) {
            return $this->registrarDocente($docente);
        }

        return response()->json([
            'success' => false,
            'mensaje' => 'Credencial no registrada'
        ], 404);
    }

    private function registrarAlumno(Alumno $alumno)
    {
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
                'persona' => 'alumno',
                'mensaje' => "Bienvenido {$alumno->nombre}",
                'hora' => $ahora,
                'nombre' => $alumno->nombre . ' ' . $alumno->apellidos,
            ]);
        }

        if (!$asistencia->hora_salida) {
    $horaEntrada = Carbon::parse($asistencia->hora_entrada);
    $ahora_carbon = Carbon::now();
    
    if ($ahora_carbon->diffInMinutes($horaEntrada) < 30) {
        return response()->json([
            'success' => true,
            'tipo' => 'muy_pronto',
            'persona' => 'alumno',
            'mensaje' => "Aún no puede registrar salida {$alumno->nombre}, mínimo 30 minutos",
            'hora' => $ahora,
            'nombre' => $alumno->nombre . ' ' . $alumno->apellidos,
        ]);
    }

    $asistencia->update(['hora_salida' => $ahora]);

        return response()->json([
            'success' => true,
            'tipo' => 'ya_registrado',
            'persona' => 'alumno',
            'mensaje' => "{$alumno->nombre} ya tiene entrada y salida registradas hoy",
        ]);
    }
    }

    private function registrarDocente(Docente $docente)
    {
        $hoy = Carbon::today();
        $ahora = Carbon::now()->format('H:i:s');

        $asistencia = AsistenciaDocente::where('docente_id', $docente->id)
            ->where('fecha', $hoy)
            ->first();

        if (!$asistencia) {
            AsistenciaDocente::create([
                'docente_id' => $docente->id,
                'fecha' => $hoy,
                'hora_entrada' => $ahora,
                'estado' => 'presente',
            ]);

            return response()->json([
                'success' => true,
                'tipo' => 'entrada',
                'persona' => 'docente',
                'mensaje' => "Buenos días {$docente->nombre}",
                'hora' => $ahora,
                'nombre' => $docente->nombre . ' ' . $docente->apellidos,
            ]);
        }

        if (!$asistencia->hora_salida) {
    $horaEntrada = Carbon::parse($asistencia->hora_entrada);
    $ahora_carbon = Carbon::now();

    if ($ahora_carbon->diffInMinutes($horaEntrada) < 30) {
        return response()->json([
            'success' => true,
            'tipo' => 'muy_pronto',
            'persona' => 'docente',
            'mensaje' => "Aún no puede registrar salida {$docente->nombre}, mínimo 30 minutos",
            'hora' => $ahora,
            'nombre' => $docente->nombre . ' ' . $docente->apellidos,
        ]);
    }
        }

    $asistencia->update(['hora_salida' => $ahora]);

        return response()->json([
            'success' => true,
            'tipo' => 'ya_registrado',
            'persona' => 'docente',
            'mensaje' => "{$docente->nombre} ya tiene entrada y salida registradas hoy",
        ]);
    }

    public function asignarUid(Request $request)
    {
        $tipo = $request->input('tipo');
        $id = $request->input('id');
        $nfc_uid = $request->input('nfc_uid');

        if ($tipo === 'alumno') {
            $persona = Alumno::find($id);
        } else {
            $persona = Docente::find($id);
        }

        if (!$persona) {
            return response()->json([
                'success' => false,
                'mensaje' => ucfirst($tipo) . ' no encontrado'
            ], 404);
        }

        $existente = $tipo === 'alumno'
            ? Alumno::where('nfc_uid', $nfc_uid)->where('id', '!=', $id)->first()
            : Docente::where('nfc_uid', $nfc_uid)->where('id', '!=', $id)->first();

        if ($existente) {
            return response()->json([
                'success' => false,
                'mensaje' => 'Esta credencial ya está asignada a otra persona'
            ], 400);
        }

        $persona->nfc_uid = $nfc_uid;
        $persona->save();

        return response()->json([
            'success' => true,
            'mensaje' => 'Credencial asignada correctamente',
            'nombre' => $persona->nombre . ' ' . $persona->apellidos,
        ]);
    }

    public function buscarUid($uid)
    {
        $alumno = Alumno::where('nfc_uid', $uid)->first();
        if ($alumno) {
            return response()->json([
                'encontrado' => true,
                'tipo' => 'alumno',
                'nombre' => $alumno->nombre . ' ' . $alumno->apellidos,
            ]);
        }

        $docente = Docente::where('nfc_uid', $uid)->first();
        if ($docente) {
            return response()->json([
                'encontrado' => true,
                'tipo' => 'docente',
                'nombre' => $docente->nombre . ' ' . $docente->apellidos,
            ]);
        }

        return response()->json(['encontrado' => false]);
    }
    public function buscarPersona(Request $request)
{
    $tipo = $request->input('tipo', 'alumno');
    $q = $request->input('q', '');

    if ($tipo === 'alumno') {
        $resultados = \App\Models\Alumno::where('activo', true)
            ->where(function($query) use ($q) {
                $query->where('nombre', 'like', "%{$q}%")
                      ->orWhere('apellidos', 'like', "%{$q}%");
            })
            ->with('clase')
            ->take(8)
            ->get()
            ->map(fn($a) => [
                'id' => $a->id,
                'nombre' => $a->nombre . ' ' . $a->apellidos,
                'info' => $a->clase?->nombre ?? 'Sin clase',
                'tiene_uid' => !empty($a->nfc_uid),
            ]);
    } else {
        $resultados = \App\Models\Docente::where('activo', true)
            ->where(function($query) use ($q) {
                $query->where('nombre', 'like', "%{$q}%")
                      ->orWhere('apellidos', 'like', "%{$q}%");
            })
            ->take(8)
            ->get()
            ->map(fn($d) => [
                'id' => $d->id,
                'nombre' => $d->nombre . ' ' . $d->apellidos,
                'info' => $d->materia ?? $d->tipo,
                'tiene_uid' => !empty($d->nfc_uid),
            ]);
    }

    return response()->json($resultados);
}

public function leerNfcYAsignar(Request $request)
{
    $tipo = $request->input('tipo');
    $id = $request->input('id');

    $pythonPath = 'C:\Users\Ismael Avila\AppData\Local\Python\pythoncore-3.14-64\python.exe';
    $scriptPath = base_path('python\leer_uid_unico.py');

    $output = shell_exec("\"$pythonPath\" \"$scriptPath\" 2>&1");
    $uid = trim($output);

    if (empty($uid) || strlen($uid) < 4) {
        return response()->json([
            'success' => false,
            'mensaje' => 'No se detectó ninguna tarjeta. Intenta de nuevo.'
        ]);
    }

    if ($tipo === 'alumno') {
        $persona = \App\Models\Alumno::find($id);
    } else {
        $persona = \App\Models\Docente::find($id);
    }

    if (!$persona) {
        return response()->json(['success' => false, 'mensaje' => 'Persona no encontrada']);
    }

    $persona->nfc_uid = $uid;
    $persona->save();

    return response()->json([
        'success' => true,
        'nombre' => $persona->nombre . ' ' . $persona->apellidos,
        'uid' => $uid,
    ]);
}
}