<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class ImportarAlumnos extends Page
{
    protected string $view = 'filament.pages.importar-alumnos';
    protected static ?string $navigationLabel = 'Importar Alumnos';
    
    protected static ?int $navigationSort = 10;
    protected static ?string $title = 'Importación Masiva de Alumnos';
}