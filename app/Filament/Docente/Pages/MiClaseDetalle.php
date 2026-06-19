<?php

namespace App\Filament\Docente\Pages;

use App\Models\Actividad;
use App\Models\CalificacionActividad;
use App\Models\Docente;
use App\Models\Materia;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Livewire\Attributes\Computed;

class MiClaseDetalle extends \App\Filament\Pages\TrabajoEscolar\ClaseDetalle
{
    protected static ?string $slug = 'mis-clases/clase';
    protected string $view         = 'filament.docente.pages.mi-clase-detalle';
    protected static bool $shouldRegisterNavigation = false;

    public function mount(): void
    {
        abort_unless($this->claseId > 0, 404);

        $docente = $this->getDocente();
        abort_unless($docente, 403);

        // Verificar que el docente esté asignado a esta clase
        abort_unless(
            $docente->clases()->where('clases.id', $this->claseId)->exists(),
            403
        );
    }

    // ─── Filtrar materias a solo las del docente ──────────────────────────────

    #[Computed]
    public function materias(): array
    {
        $docente = $this->getDocente();
        if (! $docente) return [];

        return $docente->materias()
            ->where('activo', true)
            ->orderBy('campo_formativo')
            ->orderBy('orden')
            ->get()
            ->toArray();
    }

    #[Computed]
    public function materiasPorCampo(): array
    {
        $docente = $this->getDocente();
        if (! $docente) return [];

        return $docente->materias()
            ->where('activo', true)
            ->orderBy('campo_formativo')
            ->orderBy('orden')
            ->get()
            ->groupBy('campo_formativo')
            ->toArray();
    }

    // ─── Nueva actividad: materia limitada al docente ─────────────────────────

    public function nuevaActividadAction(): Action
    {
        $labels = [
            'tarea'         => 'Tarea',
            'trabajo_clase' => 'Trabajo en Clase',
            'proyecto'      => 'Proyecto',
            'examen'        => 'Examen',
            'extra'         => 'Extra',
        ];

        $label = $labels[$this->tab] ?? 'Actividad';

        $docente = $this->getDocente();
        $materiasDocente = $docente
            ? $docente->materias()->where('activo', true)
                ->orderBy('campo_formativo')->orderBy('orden')
                ->pluck('nombre', 'id')
            : collect();

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
                    ->options($materiasDocente)
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

    // ─── Quitar acciones de admin ─────────────────────────────────────────────

    public function asignarDocenteAction(): Action
    {
        return Action::make('asignarDocente')->label('N/A')->hidden();
    }

    public function removerDocenteAction(): Action
    {
        return Action::make('removerDocente')->label('N/A')->hidden();
    }

    // ─── Helper ──────────────────────────────────────────────────────────────

    private function getDocente(): ?Docente
    {
        return Docente::withoutGlobalScopes()
            ->where('user_id', auth()->id())
            ->first();
    }
}
