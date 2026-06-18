<?php

namespace App\Filament\Pages;

use App\Mail\NotificacionColegiatura;
use App\Models\Alumno;
use App\Models\Clase;
use App\Models\Colegiatura;
use App\Models\NivelColegiatura;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Computed;

class ColegiaturaMatriz extends Page
{
    protected string $view = 'filament.pages.colegiatura-matriz';

    protected static ?string $navigationLabel = 'Colegiaturas';
    protected static ?int    $navigationSort  = 5;

    public static function getNavigationIcon(): \BackedEnum|string|null
    {
        return 'heroicon-o-banknotes';
    }

    public static function getNavigationGroup(): \UnitEnum|string|null
    {
        return 'Escuela';
    }

    // ── State ──────────────────────────────────────────────
    public int    $schoolYear;
    public string $searchAlumno  = '';

    // Modal: pago
    public ?int   $colegiaturaId = null;
    public bool   $modalPago     = false;
    public bool   $mostrarBeca   = false;
    public int    $becaPct       = 0;

    // Modal: generar celda vacía
    public ?int   $alumnoIdGen   = null;
    public ?string $periodoGen   = null;
    public bool   $modalGenerar  = false;

    // Modal: notificar
    public bool   $modalNotificar          = false;
    public ?int   $alumnoIdNotificar       = null;
    public ?int   $colegiaturaIdNotificar  = null;
    public string $tipoNotificacion        = 'pago_pendiente';
    public array  $contactosSeleccionados  = [];

    // ── Mount ───────────────────────────────────────────────
    public function mount(): void
    {
        $this->schoolYear = Colegiatura::getCurrentSchoolYear();
    }

    public function prevYear(): void { $this->schoolYear--; }
    public function nextYear(): void { $this->schoolYear++; }

    // ── Computed ────────────────────────────────────────────

    #[Computed]
    public function periodos(): array
    {
        return Colegiatura::getSchoolYearPeriods($this->schoolYear);
    }

    #[Computed]
    public function matrizData(): Collection
    {
        $periodos = $this->periodos;

        $alumnos = Alumno::with([
                'clase',
                'colegiaturas' => fn ($q) => $q->withoutGlobalScopes()
                    ->whereIn('periodo', $periodos),
            ])
            ->when($this->searchAlumno !== '', fn ($q) =>
                $q->where(fn ($q2) =>
                    $q2->where('nombre', 'like', "%{$this->searchAlumno}%")
                       ->orWhere('apellidos', 'like', "%{$this->searchAlumno}%")
                )
            )
            ->orderBy('apellidos')
            ->get();

        return $alumnos->map(function ($alumno) use ($periodos) {
            $byPeriod = $alumno->colegiaturas->keyBy('periodo');
            return [
                'alumno'   => $alumno,
                'nivel'    => $alumno->clase?->nivel ?? '—',
                'clase'    => $alumno->clase?->nombre ?? '—',
                'periodos' => collect($periodos)
                    ->mapWithKeys(fn ($p) => [$p => $byPeriod->get($p)]),
            ];
        });
    }

    #[Computed]
    public function stats(): array
    {
        $periodoActual = Colegiatura::generarPeriodo('Bimestral');

        $total       = Colegiatura::where('periodo', $periodoActual)->count();
        $recaudado   = Colegiatura::where('periodo', $periodoActual)->where('status', 'pagada')->sum('monto');
        $alCorriente = Colegiatura::where('periodo', $periodoActual)->where('status', 'pagada')->count();
        $pendientes  = Colegiatura::where('periodo', $periodoActual)->whereIn('status', ['pendiente', 'proximo_vencer'])->count();
        $vencidas    = Colegiatura::where('periodo', $periodoActual)->where('status', 'vencida')->count();
        $pct         = $total > 0 ? round($alCorriente / $total * 100) : 0;

        $becasCount       = Colegiatura::where('descuento_pct', '>', 0)->count();
        $totalDescuentos  = Colegiatura::selectRaw('SUM(COALESCE(monto_original, 0) - monto) as total')
            ->where('descuento_pct', '>', 0)
            ->value('total') ?? 0;

        return compact(
            'recaudado', 'alCorriente', 'pendientes', 'vencidas', 'pct',
            'periodoActual', 'total', 'becasCount', 'totalDescuentos'
        );
    }

    #[Computed]
    public function colegiaturaSeleccionada(): ?Colegiatura
    {
        if (! $this->colegiaturaId) return null;
        return Colegiatura::withoutGlobalScopes()
            ->with(['alumno.clase'])
            ->find($this->colegiaturaId);
    }

    #[Computed]
    public function alumnoNotificar(): ?Alumno
    {
        if (! $this->alumnoIdNotificar) return null;
        return Alumno::withoutGlobalScopes()->find($this->alumnoIdNotificar);
    }

    // ── Modal: pago + beca ─────────────────────────────────

    public function abrirModalPago(int $id): void
    {
        $this->colegiaturaId = $id;
        $this->modalPago     = true;
        $this->mostrarBeca   = false;
        $this->becaPct       = 0;
        $this->modalGenerar  = false;
        $this->modalNotificar = false;
    }

    public function toggleBeca(): void
    {
        $this->mostrarBeca = ! $this->mostrarBeca;
        if (! $this->mostrarBeca) {
            $this->becaPct = 0;
        }
    }

    public function confirmarPago(): void
    {
        $c = Colegiatura::withoutGlobalScopes()->find($this->colegiaturaId);
        if (! $c) { $this->cerrarModal(); return; }

        $data = ['status' => 'pagada', 'fecha_pago' => now()->toDateString()];

        $pct = max(0, min(100, (int) $this->becaPct));
        if ($pct > 0) {
            $data['monto_original'] = $c->monto_original ?? $c->monto;
            $data['descuento_pct']  = $pct;
            $data['monto']          = round((float) ($c->monto_original ?? $c->monto) * (1 - $pct / 100), 2);
        }

        $c->update($data);

        $msg = 'Pago registrado';
        if ($pct > 0) $msg .= " con {$pct}% de beca";

        Notification::make()->title($msg)->success()->send();
        $this->cerrarModal();
    }

    // ── Modal: generar celda vacía ─────────────────────────

    public function abrirModalGenerar(int $alumnoId, string $periodo): void
    {
        $this->alumnoIdGen  = $alumnoId;
        $this->periodoGen   = $periodo;
        $this->modalGenerar = true;
        $this->modalPago    = false;
        $this->modalNotificar = false;
    }

    public function confirmarGenerar(): void
    {
        $alumno = Alumno::with('clase')->withoutGlobalScopes()->find($this->alumnoIdGen);

        if (! $alumno || ! $alumno->clase) {
            Notification::make()->title('Alumno o clase no encontrados')->danger()->send();
            $this->cerrarModal();
            return;
        }

        $config = NivelColegiatura::withoutGlobalScopes()
            ->where('colegio_id', $alumno->colegio_id)
            ->where('nivel', $alumno->clase->nivel)
            ->where('activo', true)
            ->first();

        if (! $config) {
            Notification::make()
                ->title('Sin configuración para nivel ' . ($alumno->clase->nivel ?? ''))
                ->warning()
                ->send();
            $this->cerrarModal();
            return;
        }

        $existe = Colegiatura::withoutGlobalScopes()
            ->where('alumno_id', $alumno->id)
            ->where('periodo', $this->periodoGen)
            ->exists();

        if (! $existe) {
            Colegiatura::withoutGlobalScopes()->create([
                'alumno_id'            => $alumno->id,
                'colegio_id'           => $alumno->colegio_id,
                'nivel_colegiatura_id' => $config->id,
                'periodo'              => $this->periodoGen,
                'monto'                => $config->monto,
                'tipo_cobro'           => $config->tipo_cobro,
                'status'               => 'pendiente',
                'fecha_vencimiento'    => Colegiatura::calcularVencimientoPeriodo($this->periodoGen),
            ]);
            Notification::make()->title("Colegiatura generada: {$this->periodoGen}")->success()->send();
        } else {
            Notification::make()->title('Ya existe colegiatura para ese período')->info()->send();
        }

        $this->cerrarModal();
    }

    // ── Modal: notificar ───────────────────────────────────

    public function abrirModalNotificar(int $alumnoId, ?int $colegiaturaId = null): void
    {
        $this->alumnoIdNotificar      = $alumnoId;
        $this->colegiaturaIdNotificar = $colegiaturaId;
        $this->tipoNotificacion       = $colegiaturaId ? 'pago_pendiente' : 'pago_pendiente';
        $this->contactosSeleccionados = [];
        $this->modalNotificar         = true;
        $this->modalPago              = false;
        $this->modalGenerar           = false;
    }

    public function enviarNotificacion(): void
    {
        $alumno      = Alumno::withoutGlobalScopes()->find($this->alumnoIdNotificar);
        $colegiatura = $this->colegiaturaIdNotificar
            ? Colegiatura::withoutGlobalScopes()->with(['alumno.clase'])->find($this->colegiaturaIdNotificar)
            : null;

        if (! $alumno) {
            Notification::make()->title('Alumno no encontrado')->danger()->send();
            $this->cerrarModal();
            return;
        }

        if (empty($this->contactosSeleccionados)) {
            Notification::make()->title('Selecciona al menos un contacto')->warning()->send();
            return;
        }

        if (! $colegiatura) {
            // Use latest colegiatura if none specified
            $colegiatura = Colegiatura::withoutGlobalScopes()
                ->with(['alumno.clase'])
                ->where('alumno_id', $alumno->id)
                ->latest()
                ->first();
        }

        if (! $colegiatura) {
            Notification::make()->title('Sin colegiatura asociada para notificar')->warning()->send();
            $this->cerrarModal();
            return;
        }

        $enviados = 0;

        foreach ($this->contactosSeleccionados as $tipo) {
            [$email, $nombre] = match ($tipo) {
                'padre' => [$alumno->correo_padre, $alumno->nombre_padre],
                'madre' => [$alumno->correo_madre, $alumno->nombre_madre],
                'tutor' => [$alumno->correo_tutor, $alumno->nombre_tutor],
                default => [null, null],
            };

            if (! $email) continue;

            try {
                Mail::to($email)->send(
                    new NotificacionColegiatura($colegiatura, $this->tipoNotificacion, $nombre ?? 'Estimado/a')
                );
                $enviados++;
            } catch (\Throwable) {
                // Log silently — not critical
            }
        }

        $msg = $enviados > 0
            ? "{$enviados} correo(s) enviado(s) correctamente"
            : 'No se pudo enviar ningún correo. Verifica la configuración de mail.';

        $enviados > 0
            ? Notification::make()->title($msg)->success()->send()
            : Notification::make()->title($msg)->warning()->send();

        $this->cerrarModal();
    }

    public function abrirWhatsApp(string $contactoTipo): void
    {
        $alumno      = Alumno::withoutGlobalScopes()->find($this->alumnoIdNotificar);
        $colegiatura = $this->colegiaturaIdNotificar
            ? Colegiatura::withoutGlobalScopes()->find($this->colegiaturaIdNotificar)
            : Colegiatura::withoutGlobalScopes()
                ->where('alumno_id', $this->alumnoIdNotificar)
                ->latest()->first();

        if (! $alumno || ! $colegiatura) return;

        [$phone, $nombre] = match ($contactoTipo) {
            'padre' => [$alumno->telefono_padre, $alumno->nombre_padre],
            'madre' => [$alumno->telefono_madre, $alumno->nombre_madre],
            'tutor' => [$alumno->telefono_tutor, $alumno->nombre_tutor],
            default => [null, null],
        };

        if (! $phone) return;

        $alumnoNombre = "{$alumno->nombre} {$alumno->apellidos}";
        $monto        = '$' . number_format($colegiatura->monto, 0) . ' MXN';
        $periodo      = $colegiatura->periodo;
        $vence        = $colegiatura->fecha_vencimiento?->format('d/m/Y') ?? '';
        $becaLinea    = $colegiatura->descuento_pct > 0
            ? "\n🎓 Beca aplicada: {$colegiatura->descuento_pct}% de descuento"
            : '';

        $mensaje = match ($this->tipoNotificacion) {
            'pago_realizado' =>
                "¡Hola {$nombre}! ✅\n\n" .
                "Le confirmamos que el pago de colegiatura de *{$alumnoNombre}* " .
                "del período *{$periodo}* ha sido registrado exitosamente.\n\n" .
                "💰 Monto pagado: {$monto}{$becaLinea}\n\n" .
                "¡Gracias por su puntualidad! 🙏\n\n_SchoolCore_",
            default =>
                "¡Hola {$nombre}! ⏰\n\n" .
                "Le recordamos que la colegiatura de *{$alumnoNombre}* " .
                "del período *{$periodo}* está *pendiente de pago*.\n\n" .
                "💰 Monto: {$monto}\n" .
                ($vence ? "📅 Fecha límite: {$vence}\n" : '') .
                "\nFavor de realizar su pago a la brevedad. 🙏\n\n_SchoolCore_",
        };

        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($cleanPhone) === 10) {
            $cleanPhone = '52' . $cleanPhone;
        }

        $url = 'https://wa.me/' . $cleanPhone . '?text=' . urlencode($mensaje);
        $this->dispatch('open-url', url: $url);
    }

    // ── Generar período masivo ─────────────────────────────

    public function generarPeriodoActual(): void
    {
        $colegioId = auth()->user()->colegio_id;
        $generados = 0;

        $configs = NivelColegiatura::where('colegio_id', $colegioId)
            ->where('activo', true)
            ->get()
            ->keyBy('nivel');

        if ($configs->isEmpty()) {
            Notification::make()
                ->title('Sin configuración de niveles')
                ->body('Configura los niveles en el Super Admin.')
                ->warning()
                ->send();
            return;
        }

        Clase::where('colegio_id', $colegioId)
            ->whereIn('nivel', $configs->keys())
            ->with('alumnos')
            ->each(function ($clase) use ($configs, $colegioId, &$generados) {
                $config  = $configs->get($clase->nivel);
                if (! $config) return;
                $periodo = Colegiatura::generarPeriodo($config->tipo_cobro);

                foreach ($clase->alumnos as $alumno) {
                    if (Colegiatura::withoutGlobalScopes()
                        ->where('alumno_id', $alumno->id)
                        ->where('periodo', $periodo)
                        ->exists()) continue;

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
            });

        $generados > 0
            ? Notification::make()->title("{$generados} colegiatura(s) generada(s)")->success()->send()
            : Notification::make()->title('Todos los alumnos ya tienen colegiatura para el período actual')->info()->send();
    }

    // ── Cerrar todos los modales ───────────────────────────

    public function cerrarModal(): void
    {
        $this->colegiaturaId          = null;
        $this->alumnoIdGen            = null;
        $this->periodoGen             = null;
        $this->alumnoIdNotificar      = null;
        $this->colegiaturaIdNotificar = null;
        $this->contactosSeleccionados = [];
        $this->modalPago              = false;
        $this->modalGenerar           = false;
        $this->modalNotificar         = false;
        $this->mostrarBeca            = false;
        $this->becaPct                = 0;
    }
}
