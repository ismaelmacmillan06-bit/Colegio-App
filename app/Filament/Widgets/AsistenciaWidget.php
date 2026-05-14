<?php

namespace App\Filament\Widgets;

use App\Models\Asistencia;
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
        $grupos = Grupo::with(['grado', 'alumnos'])->where('activo', true)->get();

        $datos = $grupos->map(function ($grupo) use ($hoy) {
            $totalAlumnos = $grupo->alumnos->count();
            $presentes = Asistencia::where('fecha', $hoy)
                ->whereIn('alumno_id', $grupo->alumnos->pluck('id'))
                ->where('estado', 'presente')
                ->count();

            $porcentaje = $totalAlumnos > 0
                ? round(($presentes / $totalAlumnos) * 100)
                : 0;

            return [
                'grupo' => $grupo->grado->nombre . $grupo->grupo,
                'nivel' => $grupo->grado->nivel,
                'maestro' => $grupo->maestro ?? 'Sin asignar',
                'total' => $totalAlumnos,
                'presentes' => $presentes,
                'porcentaje' => $porcentaje,
            ];
        });

        return [
            'grupos' => $datos,
            'fecha' => Carbon::today()->locale('es')->isoFormat('D [de] MMMM [de] YYYY'),
        ];
    }
}