<?php

namespace App\Filament\Docente\Widgets;

use App\Models\AsistenciaDocente;
use App\Models\Docente;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class MarcarEntradaWidget extends Widget
{
    protected string $view = 'filament.docente.widgets.marcar-entrada-widget';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = -10;

    public ?AsistenciaDocente $registroHoy = null;

    public function mount(): void
    {
        $this->cargarRegistro();
    }

    private function cargarRegistro(): void
    {
        $docente = $this->getDocente();
        if (! $docente) return;

        $this->registroHoy = AsistenciaDocente::where('docente_id', $docente->id)
            ->whereDate('fecha', Carbon::today())
            ->first();
    }

    public function marcarEntrada(): void
    {
        $docente = $this->getDocente();

        if (! $docente) {
            Notification::make()->title('Error')->body('No se encontró tu perfil docente.')->danger()->send();
            return;
        }

        if ($this->registroHoy) {
            Notification::make()->title('Ya marcaste tu entrada hoy')->warning()->send();
            return;
        }

        $this->registroHoy = AsistenciaDocente::create([
            'docente_id'   => $docente->id,
            'fecha'        => Carbon::today(),
            'hora_entrada' => Carbon::now()->format('H:i:s'),
            'estado'       => 'presente',
        ]);

        Notification::make()
            ->title('¡Entrada registrada!')
            ->body('Entrada marcada a las ' . Carbon::now()->format('H:i') . '.')
            ->success()
            ->send();
    }

    public function marcarSalida(): void
    {
        $docente = $this->getDocente();

        if (! $docente || ! $this->registroHoy) {
            Notification::make()->title('Primero debes marcar tu entrada')->warning()->send();
            return;
        }

        if ($this->registroHoy->hora_salida) {
            Notification::make()->title('Ya marcaste tu salida hoy')->warning()->send();
            return;
        }

        $this->registroHoy->update(['hora_salida' => Carbon::now()->format('H:i:s')]);
        $this->cargarRegistro();

        Notification::make()
            ->title('¡Salida registrada!')
            ->body('Salida marcada a las ' . Carbon::now()->format('H:i') . '.')
            ->success()
            ->send();
    }

    private function getDocente(): ?Docente
    {
        return Docente::withoutGlobalScopes()
            ->where('user_id', auth()->id())
            ->first();
    }
}
