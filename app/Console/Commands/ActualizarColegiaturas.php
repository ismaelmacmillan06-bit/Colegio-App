<?php

namespace App\Console\Commands;

use App\Models\Alumno;
use App\Models\Clase;
use App\Models\Colegiatura;
use App\Models\NivelColegiatura;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ActualizarColegiaturas extends Command
{
    protected $signature = 'colegiaturas:actualizar
                            {--dry-run : Muestra qué se actualizaría sin hacer cambios}
                            {--generar : También genera colegiaturas del período actual para alumnos sin una}';

    protected $description = 'Actualiza estatus de colegiaturas (proximo_vencer/vencida) y opcionalmente genera el período actual';

    public function handle(): int
    {
        $dry = $this->option('dry-run');
        $now = Carbon::now();

        // 1. pendiente + vence en ≤7 días → proximo_vencer
        $builder1 = Colegiatura::withoutGlobalScopes()
            ->where('status', 'pendiente')
            ->whereNotNull('fecha_vencimiento')
            ->where('fecha_vencimiento', '>=', $now->toDateString())
            ->where('fecha_vencimiento', '<=', $now->copy()->addDays(7)->toDateString());

        $n1 = $builder1->count();
        if (! $dry && $n1 > 0) {
            Colegiatura::withoutGlobalScopes()
                ->where('status', 'pendiente')
                ->whereNotNull('fecha_vencimiento')
                ->where('fecha_vencimiento', '>=', $now->toDateString())
                ->where('fecha_vencimiento', '<=', $now->copy()->addDays(7)->toDateString())
                ->update(['status' => 'proximo_vencer']);
        }
        $this->info("Próximas a vencer: $n1" . ($dry ? ' (dry-run)' : ''));

        // 2. pendiente/proximo_vencer + fecha pasada → vencida
        $builder2 = Colegiatura::withoutGlobalScopes()
            ->whereIn('status', ['pendiente', 'proximo_vencer'])
            ->whereNotNull('fecha_vencimiento')
            ->where('fecha_vencimiento', '<', $now->toDateString());

        $n2 = $builder2->count();
        if (! $dry && $n2 > 0) {
            Colegiatura::withoutGlobalScopes()
                ->whereIn('status', ['pendiente', 'proximo_vencer'])
                ->whereNotNull('fecha_vencimiento')
                ->where('fecha_vencimiento', '<', $now->toDateString())
                ->update(['status' => 'vencida']);
        }
        $this->info("Vencidas: $n2" . ($dry ? ' (dry-run)' : ''));

        // 3. Opcional: generar período actual
        if ($this->option('generar')) {
            $generados = 0;
            $configs = NivelColegiatura::withoutGlobalScopes()
                ->where('activo', true)
                ->get()
                ->groupBy('colegio_id');

            foreach ($configs as $colegioId => $niveles) {
                $nivelesMap = $niveles->keyBy('nivel');

                Clase::withoutGlobalScopes()
                    ->where('colegio_id', $colegioId)
                    ->whereIn('nivel', $nivelesMap->keys())
                    ->with('alumnos')
                    ->each(function ($clase) use ($nivelesMap, $colegioId, $dry, &$generados) {
                        $config = $nivelesMap->get($clase->nivel);
                        if (! $config) return;

                        $periodo = Colegiatura::generarPeriodo($config->tipo_cobro);

                        foreach ($clase->alumnos as $alumno) {
                            $existe = Colegiatura::withoutGlobalScopes()
                                ->where('alumno_id', $alumno->id)
                                ->where('periodo', $periodo)
                                ->exists();
                            if ($existe) continue;

                            if (! $dry) {
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
                            }
                            $generados++;
                        }
                    });
            }
            $this->info("Colegiaturas generadas: $generados" . ($dry ? ' (dry-run)' : ''));
        }

        return self::SUCCESS;
    }
}
