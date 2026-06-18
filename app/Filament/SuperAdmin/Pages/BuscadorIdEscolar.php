<?php

namespace App\Filament\SuperAdmin\Pages;

use App\Models\Alumno;
use Filament\Pages\Page;
use Livewire\Attributes\Computed;

class BuscadorIdEscolar extends Page
{
    protected static ?string $navigationLabel = 'Buscador ID Escolar';
    protected static ?int $navigationSort = 10;
    protected string $view = 'filament.super-admin.pages.buscador-id-escolar';

    public static function getNavigationIcon(): string|\BackedEnum|null { return 'heroicon-o-magnifying-glass'; }
    public static function getNavigationGroup(): ?string { return 'Alumnos'; }

    public string $query = '';

    #[Computed]
    public function resultados()
    {
        if (strlen(trim($this->query)) < 2) {
            return collect();
        }

        return Alumno::withoutGlobalScopes()
            ->with(['clase', 'clase.colegio'])
            ->where(function ($q) {
                $term = '%' . $this->query . '%';
                $q->where('codigo_alumno', 'like', $term)
                  ->orWhere('nombre', 'like', $term)
                  ->orWhere('apellidos', 'like', $term);
            })
            ->orderBy('apellidos')
            ->limit(50)
            ->get();
    }
}
