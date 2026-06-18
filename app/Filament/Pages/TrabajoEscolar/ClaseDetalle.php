<?php

namespace App\Filament\Pages\TrabajoEscolar;

use App\Models\Actividad;
use App\Models\Asistencia;
use App\Models\CalificacionActividad;
use App\Models\Clase;
use App\Models\CorteAsistencia;
use App\Models\CorteDetalle;
use App\Models\Docente;
use App\Models\Materia;
use App\Mail\AsistenciaNotificacion;
use Illuminate\Support\Facades\Mail;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;

class ClaseDetalle extends Page implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    protected string $view = 'filament.pages.trabajo-escolar.clase-detalle';
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $slug = 'trabajo-escolar/clase';

    #[Url]
    public int $claseId = 0;

    #[Url]
    public string $tab = 'pase-lista';

    #[Url]
    public ?int $materiaId = null;

    #[Url]
    public ?int $corteMateria = null;

    public ?int $actividadAbiertaId = null;
    public ?int $corteExpandidoId = null;

    public function mount(): void
    {
        abort_unless($this->claseId > 0, 404);
        Clase::findOrFail($this->claseId);
    }

    public function getTitle(): string
    {
        return $this->clase->nombre ?? 'Detalle de Clase';
    }

    #[Computed]
    public function clase(): Clase
    {
        return Clase::with([
            'alumnos'  => fn($q) => $q->where('activo', true)->orderBy('apellidos'),
            'docentes' => fn($q) => $q->withPivot('es_titular')->where('activo', true),
        ])->findOrFail($this->claseId);
    }

    #[Computed]
    public function esSecundaria(): bool
    {
        return in_array($this->clase->nivel, ['Secundaria', 'Bachillerato']);
    }

    #[Computed]
    public function alumnosHoy(): array
    {
        $hoy = Carbon::today();

        return $this->clase->alumnos->map(function ($alumno) use ($hoy) {
            $asistencia = Asistencia::where('alumno_id', $alumno->id)
                ->where('fecha', $hoy)
                ->first();

            return [
                'id'          => $alumno->id,
                'nombre'      => $alumno->nombre . ' ' . $alumno->apellidos,
                'foto'        => $alumno->foto,
                'estado'      => $asistencia?->estado ?? 'ausente',
                'hora_entrada' => $asistencia?->hora_entrada,
            ];
        })->toArray();
    }

    #[Computed]
    public function actividades()
    {
        return Actividad::where('clase_id', $this->claseId)
            ->where('tipo', $this->tab)
            ->when($this->materiaId, fn($q) => $q->where('materia_id', $this->materiaId))
            ->with('materia')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    #[Computed]
    public function materias(): array
    {
        return Materia::where('activo', true)
            ->orderBy('campo_formativo')
            ->orderBy('orden')
            ->get()
            ->toArray();
    }

    #[Computed]
    public function materiasPorCampo(): array
    {
        return Materia::where('activo', true)
            ->orderBy('campo_formativo')
            ->orderBy('orden')
            ->get()
            ->groupBy('campo_formativo')
            ->toArray();
    }

    #[Computed]
    public function coberturaMaterias(): array
    {
        $docentesCon = $this->clase->docentes()
            ->with('materias')
            ->withPivot('es_titular')
            ->get();

        $materias = Materia::where('activo', true)
            ->orderBy('campo_formativo')
            ->orderBy('orden')
            ->get();

        return $materias
            ->groupBy('campo_formativo')
            ->map(function ($grupo) use ($docentesCon) {
                return $grupo->map(function ($materia) use ($docentesCon) {
                    $docentesMateria = $docentesCon->filter(
                        fn($d) => $d->materias->contains('id', $materia->id)
                    );
                    return [
                        'id'       => $materia->id,
                        'nombre'   => $materia->nombre,
                        'docentes' => $docentesMateria->map(fn($d) => [
                            'id'         => $d->id,
                            'nombre'     => $d->nombre . ' ' . $d->apellidos,
                            'es_titular' => (bool) $d->pivot->es_titular,
                        ])->values()->toArray(),
                    ];
                })->values()->toArray();
            })
            ->toArray();
    }

    #[Computed]
    public function calificacionesAbiertas(): array
    {
        if (! $this->actividadAbiertaId) {
            return [];
        }

        return $this->clase->alumnos->map(function ($alumno) {
            $cal = CalificacionActividad::where('actividad_id', $this->actividadAbiertaId)
                ->where('alumno_id', $alumno->id)
                ->first();

            return [
                'alumno_id'    => $alumno->id,
                'nombre'       => $alumno->nombre . ' ' . $alumno->apellidos,
                'calificacion' => $cal?->calificacion,
            ];
        })->toArray();
    }

    public function changeTab(string $tab): void
    {
        $this->tab              = $tab;
        $this->materiaId        = null;
        $this->actividadAbiertaId = null;
        unset($this->actividades);
        unset($this->alumnosHoy);
        unset($this->calificacionesAbiertas);
    }

    public function toggleActividad(int $id): void
    {
        $this->actividadAbiertaId = ($this->actividadAbiertaId === $id) ? null : $id;
        unset($this->calificacionesAbiertas);
    }

    public function filtrarMateria(?int $materiaId): void
    {
        $this->materiaId = $materiaId;
        $this->actividadAbiertaId = null;
        unset($this->actividades);
        unset($this->calificacionesAbiertas);
    }

    public function seleccionarMateriaCorte(?int $materiaId): void
    {
        $this->corteMateria = $materiaId;
        unset($this->corteEntradaHoy);
        unset($this->corteSalidaHoy);
        unset($this->historialCortes);
        unset($this->alumnosHoy);
    }

    public function marcarPresente(int $alumnoId): void
    {
        Asistencia::updateOrCreate(
            ['alumno_id' => $alumnoId, 'fecha' => today()],
            [
                'hora_entrada'         => now()->format('H:i:s'),
                'estado'               => 'presente',
                'notificacion_entrada' => false,
                'notificacion_salida'  => false,
            ]
        );

        unset($this->alumnosHoy);

        Notification::make()
            ->title('Asistencia marcada correctamente')
            ->success()
            ->send();
    }

    public function nuevaActividadAction(): Action
    {
        $labels = [
            'tarea'        => 'Tarea',
            'trabajo_clase' => 'Trabajo en Clase',
            'proyecto'     => 'Proyecto',
            'examen'       => 'Examen',
            'extra'        => 'Extra',
        ];

        $label = $labels[$this->tab] ?? 'Actividad';

        return Action::make('nuevaActividad')
            ->label("Nueva $label")
            ->icon('heroicon-o-plus')
            ->color('primary')
            ->slideOver()
            ->form([
                TextInput::make('titulo')
                    ->label('Título')
                    ->required()
                    ->maxLength(255),

                Textarea::make('descripcion')
                    ->label('Descripción')
                    ->rows(3)
                    ->nullable(),

                Select::make('materia_id')
                    ->label('Materia')
                    ->options(
                        Materia::where('activo', true)
                            ->orderBy('campo_formativo')
                            ->orderBy('orden')
                            ->get()
                            ->mapWithKeys(fn($m) => [$m->id => $m->nombre])
                    )
                    ->nullable(),

                DatePicker::make('fecha_entrega')
                    ->label('Fecha de entrega')
                    ->nullable(),
            ])
            ->action(function (array $data) use ($label): void {
                $actividad = Actividad::create([
                    'clase_id'      => $this->claseId,
                    'tipo'          => $this->tab,
                    'titulo'        => $data['titulo'],
                    'descripcion'   => $data['descripcion'] ?? null,
                    'materia_id'    => $data['materia_id'] ?? null,
                    'fecha_entrega' => $data['fecha_entrega'] ?? null,
                ]);

                $inserts = $this->clase->alumnos->map(fn($a) => [
                    'actividad_id' => $actividad->id,
                    'alumno_id'    => $a->id,
                    'calificacion' => null,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ])->toArray();

                CalificacionActividad::insert($inserts);

                unset($this->actividades);

                Notification::make()
                    ->title("$label creada correctamente")
                    ->success()
                    ->send();
            });
    }

    public function editarCalificacionAction(): Action
    {
        return Action::make('editarCalificacion')
            ->label('Editar calificación')
            ->icon('heroicon-o-pencil')
            ->color('warning')
            ->mountUsing(function ($form, array $arguments) {
                $cal = CalificacionActividad::where('actividad_id', $arguments['actividad_id'])
                    ->where('alumno_id', $arguments['alumno_id'])
                    ->value('calificacion');

                $form->fill(['calificacion' => $cal]);
            })
            ->form([
                TextInput::make('calificacion')
                    ->label('Calificación (0-10)')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(10)
                    ->nullable(),
            ])
            ->action(function (array $data, array $arguments): void {
                CalificacionActividad::updateOrCreate(
                    [
                        'actividad_id' => $arguments['actividad_id'],
                        'alumno_id'    => $arguments['alumno_id'],
                    ],
                    ['calificacion' => $data['calificacion']]
                );

                unset($this->calificacionesAbiertas);

                Notification::make()
                    ->title('Calificación guardada')
                    ->success()
                    ->send();
            });
    }

    public function eliminarActividadAction(): Action
    {
        return Action::make('eliminarActividad')
            ->label('Eliminar')
            ->icon('heroicon-o-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->modalHeading('¿Eliminar actividad?')
            ->modalDescription('Se eliminarán también todas las calificaciones registradas. Esta acción no se puede deshacer.')
            ->action(function (array $arguments): void {
                Actividad::find($arguments['actividad_id'])?->delete();

                if ($this->actividadAbiertaId === $arguments['actividad_id']) {
                    $this->actividadAbiertaId = null;
                }

                unset($this->actividades);
                unset($this->calificacionesAbiertas);

                Notification::make()
                    ->title('Actividad eliminada')
                    ->success()
                    ->send();
            });
    }

    // ─── CORTES DE ASISTENCIA ────────────────────────────────────────────────

    #[Computed]
    public function corteEntradaHoy(): ?CorteAsistencia
    {
        return CorteAsistencia::where('clase_id', $this->claseId)
            ->where('fecha', today())
            ->where('tipo', 'entrada')
            ->where('materia_id', $this->esSecundaria ? $this->corteMateria : null)
            ->first();
    }

    #[Computed]
    public function corteSalidaHoy(): ?CorteAsistencia
    {
        return CorteAsistencia::where('clase_id', $this->claseId)
            ->where('fecha', today())
            ->where('tipo', 'salida')
            ->where('materia_id', $this->esSecundaria ? $this->corteMateria : null)
            ->first();
    }

    #[Computed]
    public function historialCortes()
    {
        $query = CorteAsistencia::where('clase_id', $this->claseId)
            ->orderBy('fecha', 'desc')
            ->orderByRaw("FIELD(tipo, 'entrada', 'salida')")
            ->with('materia');

        if ($this->esSecundaria && $this->corteMateria) {
            $query->where('materia_id', $this->corteMateria);
        } elseif (! $this->esSecundaria) {
            $query->whereNull('materia_id');
        }

        return $query->get();
    }

    #[Computed]
    public function detallesCorteExpandido(): array
    {
        if (! $this->corteExpandidoId) {
            return [];
        }

        return CorteDetalle::where('corte_id', $this->corteExpandidoId)
            ->with('alumno')
            ->get()
            ->map(fn($d) => [
                'id'     => $d->id,
                'nombre' => $d->alumno->nombre . ' ' . $d->alumno->apellidos,
                'estado' => $d->estado,
                'nota'   => $d->nota,
            ])
            ->sortBy('nombre')
            ->values()
            ->toArray();
    }

    public function toggleCorte(int $id): void
    {
        $this->corteExpandidoId = ($this->corteExpandidoId === $id) ? null : $id;
        unset($this->detallesCorteExpandido);
    }

    public function marcarEntradaAction(): Action
    {
        return Action::make('marcarEntrada')
            ->label('Marcar Entrada')
            ->icon('heroicon-o-arrow-right-circle')
            ->color('primary')
            ->requiresConfirmation()
            ->modalHeading('Notificar Entrada')
            ->modalDescription('Estás a punto de mandar correos a los padres de familia de los alumnos que NO se presentaron hoy. ¿Deseas continuar?')
            ->modalSubmitActionLabel('Enviar')
            ->modalCancelActionLabel('Cancelar')
            ->action(function (): void {
                if ($this->esSecundaria && ! $this->corteMateria) {
                    Notification::make()
                        ->title('Selecciona una materia/periodo primero.')
                        ->warning()->send();
                    return;
                }

                if ($this->corteEntradaHoy) {
                    Notification::make()
                        ->title('La entrada ya fue marcada hoy a las ' . substr($this->corteEntradaHoy->hora_corte, 0, 5))
                        ->warning()->send();
                    return;
                }

                $hoy    = Carbon::today();
                $ahora  = now()->format('H:i:s');
                $fecha  = Carbon::today()->locale('es')->isoFormat('D [de] MMMM [de] YYYY');
                $clase  = $this->clase->nombre;
                $alumnos = $this->clase->alumnos;

                $corte = CorteAsistencia::create([
                    'clase_id'           => $this->claseId,
                    'materia_id'         => $this->esSecundaria ? $this->corteMateria : null,
                    'fecha'              => $hoy,
                    'tipo'               => 'entrada',
                    'hora_corte'         => $ahora,
                    'total_presentes'    => 0,
                    'total_ausentes'     => 0,
                    'total_tardanza'     => 0,
                    'total_justificados' => 0,
                ]);

                $presentes = $ausentes = $tardanza = 0;
                $inserts   = [];
                $correosSent = 0;

                foreach ($alumnos as $alumno) {
                    $asistencia = Asistencia::where('alumno_id', $alumno->id)
                        ->where('fecha', $hoy)->first();

                    $estado = $asistencia?->estado ?? 'ausente';

                    if ($estado === 'presente' && $asistencia && ! $asistencia->hora_entrada) {
                        $asistencia->update(['hora_entrada' => $ahora]);
                    }

                    if ($estado === 'ausente') {
                        if (! $asistencia) {
                            Asistencia::create([
                                'alumno_id'            => $alumno->id,
                                'fecha'                => $hoy,
                                'estado'               => 'ausente',
                                'notificacion_entrada' => true,
                                'notificacion_salida'  => false,
                            ]);
                        }
                        $this->enviarCorreos($alumno, $clase, $fecha, $ahora, 'ausencia');
                        $correosSent++;
                        $ausentes++;
                    } elseif ($estado === 'tardanza') {
                        $tardanza++;
                    } else {
                        $presentes++;
                    }

                    $inserts[] = [
                        'corte_id'   => $corte->id,
                        'alumno_id'  => $alumno->id,
                        'estado'     => $estado,
                        'nota'       => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                CorteDetalle::insert($inserts);
                $corte->update([
                    'total_presentes' => $presentes,
                    'total_ausentes'  => $ausentes,
                    'total_tardanza'  => $tardanza,
                ]);

                unset($this->corteEntradaHoy);
                unset($this->historialCortes);
                unset($this->alumnosHoy);

                Notification::make()
                    ->title("Entrada registrada — {$correosSent} aviso(s) de ausencia enviados")
                    ->success()->send();
            });
    }

    public function marcarSalidaAction(): Action
    {
        return Action::make('marcarSalida')
            ->label('Marcar Salida')
            ->icon('heroicon-o-arrow-left-circle')
            ->color('warning')
            ->requiresConfirmation()
            ->modalHeading('Notificar Salida')
            ->modalDescription('Estás a punto de notificar la salida de los alumnos presentes a sus padres de familia. ¿Deseas continuar?')
            ->modalSubmitActionLabel('Enviar')
            ->modalCancelActionLabel('Cancelar')
            ->action(function (): void {
                if (! $this->corteEntradaHoy) {
                    Notification::make()
                        ->title('Debes registrar la entrada primero antes de marcar la salida.')
                        ->warning()->send();
                    return;
                }

                if ($this->corteSalidaHoy) {
                    Notification::make()
                        ->title('La salida ya fue marcada hoy a las ' . substr($this->corteSalidaHoy->hora_corte, 0, 5))
                        ->warning()->send();
                    return;
                }

                $hoy    = Carbon::today();
                $ahora  = now()->format('H:i:s');
                $fecha  = Carbon::today()->locale('es')->isoFormat('D [de] MMMM [de] YYYY');
                $clase  = $this->clase->nombre;

                $presentesIds = CorteDetalle::where('corte_id', $this->corteEntradaHoy->id)
                    ->where('estado', 'presente')
                    ->pluck('alumno_id');

                $corte = CorteAsistencia::create([
                    'clase_id'           => $this->claseId,
                    'materia_id'         => $this->esSecundaria ? $this->corteMateria : null,
                    'fecha'              => $hoy,
                    'tipo'               => 'salida',
                    'hora_corte'         => $ahora,
                    'total_presentes'    => $presentesIds->count(),
                    'total_ausentes'     => 0,
                    'total_tardanza'     => 0,
                    'total_justificados' => 0,
                ]);

                $inserts     = [];
                $correosSent = 0;

                foreach ($this->clase->alumnos as $alumno) {
                    $estaPresente = $presentesIds->contains($alumno->id);
                    $estado = $estaPresente ? 'presente' : 'ausente';

                    if ($estaPresente) {
                        Asistencia::where('alumno_id', $alumno->id)
                            ->where('fecha', $hoy)
                            ->update(['hora_salida' => $ahora]);

                        $this->enviarCorreos($alumno, $clase, $fecha, $ahora, 'salida');
                        $correosSent++;
                    }

                    $inserts[] = [
                        'corte_id'   => $corte->id,
                        'alumno_id'  => $alumno->id,
                        'estado'     => $estado,
                        'nota'       => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                CorteDetalle::insert($inserts);

                unset($this->corteSalidaHoy);
                unset($this->historialCortes);
                unset($this->alumnosHoy);

                Notification::make()
                    ->title("Salida registrada — {$correosSent} notificación(es) enviadas")
                    ->success()->send();
            });
    }

    private function enviarCorreos($alumno, string $clase, string $fecha, string $hora, string $tipo): void
    {
        $nombreAlumno = $alumno->nombre . ' ' . $alumno->apellidos;
        $destinatarios = [
            [$alumno->correo_padre, $alumno->nombre_padre ?? 'Padre de familia'],
            [$alumno->correo_madre, $alumno->nombre_madre ?? 'Madre de familia'],
            [$alumno->correo_tutor, $alumno->nombre_tutor ?? 'Tutor'],
        ];

        foreach ($destinatarios as [$correo, $nombrePadre]) {
            if (! $correo) continue;

            try {
                Mail::to($correo)->queue(new AsistenciaNotificacion(
                    nombrePadre:  $nombrePadre,
                    nombreAlumno: $nombreAlumno,
                    grado:        $clase,
                    fecha:        $fecha,
                    hora:         $hora,
                    tipo:         $tipo,
                ));
            } catch (\Throwable) {
                // Fallo silencioso para no interrumpir el registro
            }
        }
    }

    public function editarEstadoCorteAction(): Action
    {
        return Action::make('editarEstadoCorte')
            ->label('Editar estado')
            ->icon('heroicon-o-pencil')
            ->color('warning')
            ->mountUsing(function ($form, array $arguments) {
                $detalle = CorteDetalle::find($arguments['detalle_id']);
                $form->fill([
                    'estado' => $detalle?->estado,
                    'nota'   => $detalle?->nota,
                ]);
            })
            ->form([
                Select::make('estado')
                    ->label('Estado')
                    ->options([
                        'presente'    => 'Presente',
                        'ausente'     => 'Ausente',
                        'tardanza'    => 'Tardanza',
                        'justificado' => 'Falta justificada',
                    ])
                    ->required(),

                TextInput::make('nota')
                    ->label('Nota / Observación (opcional)')
                    ->nullable(),
            ])
            ->action(function (array $data, array $arguments): void {
                $detalle = CorteDetalle::findOrFail($arguments['detalle_id']);
                $detalle->update(['estado' => $data['estado'], 'nota' => $data['nota']]);

                $detalle->corte->recalcularTotales();

                unset($this->historialCortes);
                unset($this->detallesCorteExpandido);

                Notification::make()
                    ->title('Estado actualizado')
                    ->success()
                    ->send();
            });
    }

    // ─── DOCENTES ────────────────────────────────────────────────────────────

    public function asignarDocenteAction(): Action
    {
        return Action::make('asignarDocente')
            ->label('+ Asignar Docente')
            ->icon('heroicon-o-user-plus')
            ->color('primary')
            ->slideOver()
            ->form([
                Select::make('docente_id')
                    ->label('Docente')
                    ->options(
                        Docente::where('activo', true)
                            ->get()
                            ->mapWithKeys(fn($d) => [$d->id => $d->nombre . ' ' . $d->apellidos])
                    )
                    ->required()
                    ->searchable()
                    ->helperText('Solo se muestran docentes activos del plantel.'),

                Toggle::make('es_titular')
                    ->label('¿Es docente titular de esta clase?')
                    ->helperText('El titular aparece en el encabezado de la clase.')
                    ->default(false),
            ])
            ->action(function (array $data): void {
                $this->clase->docentes()->syncWithoutDetaching([
                    $data['docente_id'] => ['es_titular' => $data['es_titular']],
                ]);

                unset($this->clase);
                unset($this->coberturaMaterias);

                Notification::make()
                    ->title('Docente asignado a la clase')
                    ->success()
                    ->send();
            });
    }

    public function removerDocenteAction(): Action
    {
        return Action::make('removerDocente')
            ->label('Remover')
            ->icon('heroicon-o-user-minus')
            ->color('danger')
            ->requiresConfirmation()
            ->modalHeading('¿Remover docente de esta clase?')
            ->modalDescription('El docente ya no aparecerá en esta clase. Sus registros históricos de actividades y cortes se conservan.')
            ->action(function (array $arguments): void {
                $this->clase->docentes()->detach($arguments['docente_id']);

                unset($this->clase);
                unset($this->coberturaMaterias);

                Notification::make()
                    ->title('Docente removido de la clase')
                    ->success()
                    ->send();
            });
    }

    // ─── MATRIZ DE ASISTENCIA ────────────────────────────────────────────────

    #[Computed]
    public function matrizAsistencia(): array
    {
        $cortes = CorteAsistencia::where('clase_id', $this->claseId)
            ->where('tipo', 'entrada')
            ->whereNull('materia_id')
            ->orderBy('fecha', 'asc')
            ->get();

        if ($cortes->isEmpty()) {
            return ['fechas' => [], 'alumnos' => []];
        }

        $detalles = CorteDetalle::whereIn('corte_id', $cortes->pluck('id'))
            ->get()
            ->keyBy(fn($d) => $d->corte_id . '-' . $d->alumno_id);

        $alumnos = $this->clase->alumnos;

        $fechas = $cortes->map(fn($c) => [
            'id'    => $c->id,
            'fecha' => $c->fecha->format('d/m/Y'),
            'dia'   => $c->fecha->locale('es')->isoFormat('ddd'),
        ])->toArray();

        $filas = $alumnos->map(function ($alumno) use ($cortes, $detalles) {
            $dias = $cortes->map(function ($corte) use ($alumno, $detalles) {
                $key     = $corte->id . '-' . $alumno->id;
                $detalle = $detalles->get($key);
                return $detalle?->estado;
            })->toArray();

            return [
                'nombre' => $alumno->nombre . ' ' . $alumno->apellidos,
                'foto'   => $alumno->foto,
                'dias'   => $dias,
            ];
        })->sortBy('nombre')->values()->toArray();

        return ['fechas' => $fechas, 'alumnos' => $filas];
    }
}
