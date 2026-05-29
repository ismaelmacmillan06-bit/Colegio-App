<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class ImportarDocentes extends Page
{
    protected string $view = 'filament.pages.importar-docentes';
    protected static ?string $navigationLabel = 'Importar Docentes';
    protected static ?string $title = 'Importación Masiva de Docentes';
    protected static ?int $navigationSort = 11;
    public static function getNavigationGroup(): ?string
{
    return 'Escuela';
}
}