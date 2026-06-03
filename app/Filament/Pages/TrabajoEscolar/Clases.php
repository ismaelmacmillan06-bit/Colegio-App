<?php

namespace App\Filament\Pages\TrabajoEscolar;

use App\Models\Clase;
use Filament\Pages\Page;
use Livewire\Attributes\Computed;

class Clases extends Page
{
    protected static ?string $navigationLabel = 'Clases';
    protected static ?int $navigationSort = 10;
    protected static ?string $slug = 'trabajo-escolar';
    protected string $view = 'filament.pages.trabajo-escolar.clases';
    protected static ?string $title = 'Clases';

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return 'heroicon-o-academic-cap';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Trabajo Escolar';
    }

    #[Computed]
    public function clases()
    {
        return Clase::withCount(['alumnos', 'docentes'])
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();
    }
}
