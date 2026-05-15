<?php

namespace App\Filament\Widgets;

use App\Models\Asistencia;
use App\Models\AsistenciaDocente;
use App\Models\Grupo;
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
        $grupos = Grupo::with(['grado', 'alumnos', 'docente'])->where('activo', true)->get();

        $datos = $grupos->map(function ($grupo) use ($hoy) {
            $totalAlumnos = $grupo->alumnos->count();
            $presentes = Asistencia::where('fecha', $hoy)
                ->whereIn('alumno_id', $grupo->alumnos->pluck('id'))
                ->where('estado', 'presente')
                ->count();

            $porcentaje = $totalAlumnos > 0
                ? round(($presentes / $totalAlumnos) * 100)
                : 0;

            $maestroLlego = false;
            if ($grupo->docente) {
                $maestroLlego = AsistenciaDocente::where('docente_id', $grupo->docente->id)
                    ->where('fecha', $hoy)
                    ->exists();
            }

            return [
                'grupo' => $grupo->grado->nombre . $grupo->grupo,
                'nivel' => $grupo->grado->nivel,
                'maestro' => $grupo->docente
                    ? $grupo->docente->nombre . ' ' . $grupo->docente->apellidos
                    : ($grupo->maestro ?? 'Sin asignar'),
                'total' => $totalAlumnos,
                'presentes' => $presentes,
                'porcentaje' => $porcentaje,
                'maestro_llego' => $maestroLlego,
            ];
        });

        return [
            'grupos' => $datos,
            'fecha' => Carbon::today()->locale('es')->isoFormat('D [de] MMMM [de] YYYY'),
        ];
    }
}