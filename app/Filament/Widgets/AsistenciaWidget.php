<?php

namespace App\Filament\Widgets;

use App\Models\Asistencia;
use App\Models\AsistenciaDocente;
use App\Models\Clase;
use App\Models\CorteDetalle;
use App\Models\Docente;
use App\Models\Alumno;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class AsistenciaWidget extends Widget
{
    protected string $view = 'filament.widgets.asistencia-widget';
    protected int | string | array $columnSpan = 'full';
    public static ?int $sort = 1;

    protected function getViewData(): array
    {
        $hoy = Carbon::today();

        $clases = Clase::with(['alumnos', 'docentes'])->where('activo', true)->get();

        $datosClases = $clases->map(function ($clase) use ($hoy) {
            $totalAlumnos = $clase->alumnos->count();
            $presentes = Asistencia::where('fecha', $hoy)
                ->whereIn('alumno_id', $clase->alumnos->pluck('id'))
                ->where('estado', 'presente')
                ->count();

            $porcentaje = $totalAlumnos > 0
                ? round(($presentes / $totalAlumnos) * 100)
                : 0;

            $docente = $clase->docentes->where('tipo', 'titular')->first();
            $maestroLlego = false;
            $ultimoAcceso = null;

            if ($docente) {
                $asistenciaDocente = AsistenciaDocente::where('docente_id', $docente->id)
                    ->where('fecha', $hoy)
                    ->first();
                $maestroLlego = $asistenciaDocente !== null;
                $ultimoAcceso = $asistenciaDocente?->hora_entrada;
            }

            return [
                'id' => $clase->id,
                'nombre' => $clase->nombre,
                'nivel' => $clase->nivel,
                'docente_nombre' => $docente ? $docente->nombre . ' ' . $docente->apellidos : 'Sin docente',
                'docente_foto' => $docente?->foto,
                'total' => $totalAlumnos,
                'presentes' => $presentes,
                'porcentaje' => $porcentaje,
                'maestro_llego' => $maestroLlego,
                'ultimo_acceso' => $ultimoAcceso,
            ];
        });

        $directivos = Docente::where('tipo', 'directivo')->where('activo', true)->get()->map(function ($d) use ($hoy) {
            $llego = AsistenciaDocente::where('docente_id', $d->id)->where('fecha', $hoy)->exists();
            return [
                'nombre' => $d->nombre . ' ' . $d->apellidos,
                'cargo' => $d->materia ?? 'Directivo',
                'foto' => $d->foto,
                'llego' => $llego,
            ];
        });

        $extracurriculares = Docente::where('tipo', 'extracurricular')->where('activo', true)->get()->map(function ($d) use ($hoy) {
            $llego = AsistenciaDocente::where('docente_id', $d->id)->where('fecha', $hoy)->exists();
            return [
                'nombre' => $d->nombre . ' ' . $d->apellidos,
                'materia' => $d->materia ?? 'Extracurricular',
                'foto' => $d->foto,
                'llego' => $llego,
            ];
        });

        $totalAlumnos = Alumno::where('activo', true)->count();
        $totalPresentes = Asistencia::where('fecha', $hoy)->where('estado', 'presente')->count();
        $asistenciaGeneral = $totalAlumnos > 0 ? round(($totalPresentes / $totalAlumnos) * 100) : 0;

        $alumnosPorNivel = Alumno::where('alumnos.activo', true)
            ->join('clases', 'alumnos.clase_id', '=', 'clases.id')
            ->selectRaw('clases.nivel, count(*) as total')
            ->groupBy('clases.nivel')
            ->pluck('total', 'nivel');

        // Faltas del día basadas en los cortes de entrada registrados por los maestros
        $faltasHoy = CorteDetalle::whereHas('corte', fn($q) =>
            $q->where('fecha', $hoy)->where('tipo', 'entrada')
        )->where('estado', 'ausente')->count();

        $clasesCortadas = \App\Models\CorteAsistencia::where('fecha', $hoy)
            ->where('tipo', 'entrada')
            ->count();

        return [
            'clases' => $datosClases,
            'directivos' => $directivos,
            'extracurriculares' => $extracurriculares,
            'fecha' => Carbon::today()->locale('es')->isoFormat('D [de] MMMM [de] YYYY'),
            'total_alumnos' => $totalAlumnos,
            'total_presentes' => $totalPresentes,
            'asistencia_general' => $asistenciaGeneral,
            'alumnos_por_nivel' => $alumnosPorNivel,
            'total_directivos' => $directivos->count(),
            'total_extracurriculares' => $extracurriculares->count(),
            'faltas_hoy' => $faltasHoy,
            'clases_cortadas' => $clasesCortadas,
        ];
    }
}