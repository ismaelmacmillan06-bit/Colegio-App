<?php

namespace App\Filament\SuperAdmin\Widgets;

use App\Models\Alumno;
use App\Models\Colegio;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class ResumenWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $colegiosActivos  = Colegio::where('activo', true)->count();
        $totalColegios    = Colegio::count();
        $totalAlumnos     = Alumno::withoutGlobalScopes()->count();
        $ingresosMes      = Colegio::where('activo', true)->sum('precio_mensual');
        $porVencer        = Colegio::where('activo', true)
            ->whereNotNull('fecha_vencimiento')
            ->where('fecha_vencimiento', '<=', now()->addDays(30))
            ->where('fecha_vencimiento', '>=', now())
            ->count();

        return [
            Stat::make('Colegios Activos', $colegiosActivos . ' / ' . $totalColegios)
                ->description('Total registrados en la plataforma')
                ->color('success')
                ->icon('heroicon-o-building-office-2'),

            Stat::make('Alumnos en la plataforma', number_format($totalAlumnos))
                ->description('Suma de todos los colegios')
                ->color('info')
                ->icon('heroicon-o-users'),

            Stat::make('Ingresos Mensuales', '$' . number_format($ingresosMes, 2))
                ->description('Colegios activos × precio de plan')
                ->color('warning')
                ->icon('heroicon-o-banknotes'),

            Stat::make('Por Vencer (30 días)', $porVencer)
                ->description('Colegios con suscripción próxima a expirar')
                ->color($porVencer > 0 ? 'danger' : 'gray')
                ->icon('heroicon-o-exclamation-triangle'),
        ];
    }
}
