<?php

namespace App\Filament\Widgets;

use App\Models\Colegiatura;
use App\Models\NivelColegiatura;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ColegiaturaStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $niveles = NivelColegiatura::distinct()->pluck('nivel')->sort()->values();

        $colores = [
            'Maternal'     => 'info',
            'Preescolar'   => 'success',
            'Primaria'     => 'warning',
            'Secundaria'   => 'danger',
            'Bachillerato' => 'gray',
            'Licenciatura' => 'primary',
        ];

        $stats = [];

        foreach ($niveles as $nivel) {
            // Separate queries so distinct() on the count does NOT bleed into sum()
            $alumnos = Colegiatura::whereHas('nivelConfig', fn ($q) => $q->where('nivel', $nivel))
                ->distinct('alumno_id')
                ->count('alumno_id');

            $total = Colegiatura::whereHas('nivelConfig', fn ($q) => $q->where('nivel', $nivel))
                ->sum('monto');

            $pendiente = Colegiatura::whereHas('nivelConfig', fn ($q) => $q->where('nivel', $nivel))
                ->whereIn('status', ['pendiente', 'proximo_vencer'])
                ->sum('monto');

            $desc = '$' . number_format($total, 0) . ' MXN recaudados';
            if ($pendiente > 0) {
                $desc .= ' · $' . number_format($pendiente, 0) . ' pendiente';
            }

            $stats[] = Stat::make($nivel, $alumnos . ' alumno' . ($alumnos !== 1 ? 's' : ''))
                ->description($desc)
                ->color($pendiente > 0 ? 'warning' : ($colores[$nivel] ?? 'gray'))
                ->icon('heroicon-o-academic-cap');
        }

        if (empty($stats)) {
            $stats[] = Stat::make('Sin niveles', '—')
                ->description('Configura niveles y colegiaturas en el Super Admin')
                ->color('gray');
        }

        return $stats;
    }
}
