<?php

namespace App\Filament\Pages\TrabajoEscolar;

use App\Models\Actividad;
use App\Models\Asistencia;
use App\Models\CalificacionActividad;
use App\Models\Clase;
use App\Models\Materia;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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

    public ?int $actividadAbiertaId = null;

    public function mount(): void
    {
        abort_unless($this->claseId > 0, 404);
        // Verify class exists
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
            'alumnos' => fn($q) => $q->where('activo', true)->orderBy('apellidos'),
            'docentes' => fn($q) => $q->where('tipo', 'titular'),
        ])->findOrFail($this->claseId);
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
        return Materia::where('nivel', $this->clase->nivel)
            ->where('activo', true)
            ->orderBy('nombre')
            ->get()
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
        $esPrimaria = $this->clase->nivel === 'Primaria';

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
                        Materia::where('nivel', $this->clase->nivel)
                            ->where('activo', true)
                            ->pluck('nombre', 'id')
                    )
                    ->visible($esPrimaria)
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

                // Pre-crear registros de calificación para todos los alumnos
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
}
