<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class ImportarClases extends Page
{
    protected string $view = 'filament.pages.importar-clases';
    protected static ?string $navigationLabel = 'Importar Clases';
    protected static ?string $title = 'Importación Masiva de Clases';
    protected static ?int $navigationSort = 9;

    public static function getNavigationGroup(): ?string
    {
        return 'Escuela';
    }
}
