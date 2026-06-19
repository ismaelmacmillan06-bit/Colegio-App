<?php

namespace App\Filament\Docente\Pages;

use App\Models\Docente;
use Filament\Pages\Page;
use Livewire\Attributes\Computed;

class MisClases extends Page
{
    protected static ?string $navigationLabel = 'Mis Clases';
    protected static ?string $slug            = 'mis-clases';
    protected static ?int    $navigationSort  = 1;
    protected string $view = 'filament.docente.pages.mis-clases';

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return 'heroicon-o-academic-cap';
    }

    #[Computed]
    public function docente(): ?Docente
    {
        return Docente::withoutGlobalScopes()
            ->where('user_id', auth()->id())
            ->first();
    }

    #[Computed]
    public function clases()
    {
        if (! $this->docente) return collect();

        return $this->docente->clases()
            ->withCount('alumnos')
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();
    }
}
