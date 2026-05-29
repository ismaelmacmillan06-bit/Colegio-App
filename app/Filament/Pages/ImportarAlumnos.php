<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class ImportarAlumnos extends Page
{
    protected string $view = 'filament.pages.importar-alumnos';
    protected static ?string $navigationLabel = 'Importar Alumnos';
    protected static ?string $title = 'Importación Masiva de Alumnos';
    protected static ?int $navigationSort = 10;
    

public static function getNavigationGroup(): ?string
    {
    return 'Escuela';
}
    }
