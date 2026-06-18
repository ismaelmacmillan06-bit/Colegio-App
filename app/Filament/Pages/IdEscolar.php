<?php

namespace App\Filament\Pages;

use App\Models\Clase;
use Filament\Pages\Page;
use Livewire\Attributes\Computed;

class IdEscolar extends Page
{
    protected static ?string $navigationLabel = 'ID Escolar';
    protected static ?int $navigationSort = 16;
    protected string $view = 'filament.pages.id-escolar';

    public static function getNavigationIcon(): string|\BackedEnum|null { return 'heroicon-o-identification'; }
    public static function getNavigationGroup(): ?string { return 'Escuela'; }

    #[Computed]
    public function clases()
    {
        return Clase::withCount('alumnos')
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();
    }
}
