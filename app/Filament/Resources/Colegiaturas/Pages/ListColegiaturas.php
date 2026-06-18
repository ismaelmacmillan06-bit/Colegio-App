<?php

namespace App\Filament\Resources\Colegiaturas\Pages;

use App\Filament\Resources\Colegiaturas\ColegiaturaResource;
use App\Filament\Widgets\ColegiaturaStatsWidget;
use App\Models\Alumno;
use App\Models\Clase;
use App\Models\Colegiatura;
use App\Models\NivelColegiatura;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListColegiaturas extends ListRecords
{
    protected static string $resource = ColegiaturaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generar_periodo')
                ->label('Generar período actual')
                ->icon('heroicon-o-arrow-path')
                ->color('info')
                ->requiresConfirmation()
                ->modalHeading('Generar colegiaturas del período actual')
                ->modalDescription(
                    'Esto creará una colegiatura PENDIENTE para todos los alumnos activos ' .
                    'que aún no tienen colegiatura en el período actual. ' .
                    'No afecta a los que ya tienen una registrada.'
                )
                ->modalSubmitActionLabel('Generar')
                ->action(function (): void {
                    $colegioId = auth()->user()->colegio_id;
                    $generados = 0;

                    $configs = NivelColegiatura::where('colegio_id', $colegioId)
                        ->where('activo', true)
                        ->get()
                        ->keyBy('nivel');

                    if ($configs->isEmpty()) {
                        Notification::make()
                            ->title('Sin configuración de niveles')
                            ->body('Ve al Super Admin y configura los niveles y colegiaturas del colegio primero.')
                            ->warning()
                            ->send();
                        return;
                    }

                    $clases = Clase::where('colegio_id', $colegioId)
                        ->where('activo', true)
                        ->whereIn('nivel', $configs->keys())
                        ->with('alumnos')
                        ->get();

                    foreach ($clases as $clase) {
                        $config = $configs->get($clase->nivel);
                        if (! $config) continue;

                        $periodo = Colegiatura::generarPeriodo($config->tipo_cobro);

                        foreach ($clase->alumnos as $alumno) {
                            // Skip if already has a colegiatura for this period
                            $existe = Colegiatura::withoutGlobalScopes()
                                ->where('alumno_id', $alumno->id)
                                ->where('nivel_colegiatura_id', $config->id)
                                ->where('periodo', $periodo)
                                ->exists();

                            if ($existe) continue;

                            Colegiatura::withoutGlobalScopes()->create([
                                'alumno_id'            => $alumno->id,
                                'colegio_id'           => $colegioId,
                                'nivel_colegiatura_id' => $config->id,
                                'periodo'              => $periodo,
                                'monto'                => $config->monto,
                                'tipo_cobro'           => $config->tipo_cobro,
                                'status'               => 'pendiente',
                                'fecha_vencimiento'    => Colegiatura::calcularVencimiento($config->tipo_cobro),
                            ]);

                            $generados++;
                        }
                    }

                    if ($generados === 0) {
                        Notification::make()
                            ->title('Sin cambios')
                            ->body('Todos los alumnos activos ya tienen colegiatura para el período actual.')
                            ->info()
                            ->send();
                    } else {
                        Notification::make()
                            ->title("$generados colegiatura(s) generada(s)")
                            ->body('Período: ' . Colegiatura::generarPeriodo(
                                NivelColegiatura::where('colegio_id', $colegioId)->value('tipo_cobro') ?? 'Mensual'
                            ))
                            ->success()
                            ->send();
                    }
                }),

            CreateAction::make()->label('+ Nueva Colegiatura'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ColegiaturaStatsWidget::class,
        ];
    }

    public function getTabs(): array
    {
        $base = Colegiatura::query();

        return [
            'todas'          => Tab::make('Todas')
                ->badge($base->count()),

            'pendiente'      => Tab::make('Pendientes')
                ->modifyQueryUsing(fn ($q) => $q->where('status', 'pendiente'))
                ->badge($base->clone()->where('status', 'pendiente')->count())
                ->badgeColor('warning'),

            'proximo_vencer' => Tab::make('Por vencer')
                ->modifyQueryUsing(fn ($q) => $q->where('status', 'proximo_vencer'))
                ->badge($base->clone()->where('status', 'proximo_vencer')->count())
                ->badgeColor('danger'),

            'pagada'         => Tab::make('Pagadas')
                ->modifyQueryUsing(fn ($q) => $q->where('status', 'pagada'))
                ->badge($base->clone()->where('status', 'pagada')->count())
                ->badgeColor('success'),

            'declinada'      => Tab::make('Declinadas')
                ->modifyQueryUsing(fn ($q) => $q->where('status', 'declinada'))
                ->badge($base->clone()->where('status', 'declinada')->count())
                ->badgeColor('danger'),

            'vencida'        => Tab::make('Vencidas')
                ->modifyQueryUsing(fn ($q) => $q->where('status', 'vencida'))
                ->badge($base->clone()->where('status', 'vencida')->count())
                ->badgeColor('gray'),
        ];
    }
}
