<?php

namespace App\Filament\Pages;

use App\Models\CredencialConfig;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Livewire\WithFileUploads;

class GeneradorCredenciales extends Page
{
    use WithFileUploads;

    protected string $view = 'filament.pages.generador-credenciales';
    protected static ?string $navigationLabel = 'Generador de Credenciales';
    protected static ?string $title = 'Generador de Credenciales';
    protected static ?int $navigationSort = 15;
    public static function getNavigationIcon(): string|\BackedEnum|null { return 'heroicon-o-identification'; }

    public ?string $frente_actual  = null;
    public ?string $reverso_actual = null;

    public $frente_nuevo  = null;
    public $reverso_nuevo = null;

    public static function getNavigationGroup(): ?string
    {
        return 'Escuela';
    }

    public function mount(): void
    {
        $config = CredencialConfig::obtener();
        $this->frente_actual  = $config->frente_path;
        $this->reverso_actual = $config->reverso_path;
    }

    public function guardar(): void
    {
        $this->validate([
            'frente_nuevo'  => 'nullable|image|max:5120',
            'reverso_nuevo' => 'nullable|image|max:5120',
        ]);

        $config = CredencialConfig::obtener();
        $data   = [];

        if ($this->frente_nuevo) {
            $data['frente_path']    = $this->frente_nuevo->store('credenciales/templates', 'public');
            $this->frente_actual    = $data['frente_path'];
            $this->frente_nuevo     = null;
        }

        if ($this->reverso_nuevo) {
            $data['reverso_path']    = $this->reverso_nuevo->store('credenciales/templates', 'public');
            $this->reverso_actual    = $data['reverso_path'];
            $this->reverso_nuevo     = null;
        }

        if ($data) {
            $config->update($data);
            Notification::make()->title('Template guardado correctamente')->success()->send();
        } else {
            Notification::make()->title('No se seleccionó ningún archivo nuevo')->warning()->send();
        }
    }
}
