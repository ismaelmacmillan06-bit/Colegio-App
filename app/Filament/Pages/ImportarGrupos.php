<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class ImportarGrupos extends Page
{
    protected string $view = 'filament.pages.importar-grupos';
    protected static ?string $navigationLabel = 'Importar Grupos';
    protected static ?int $navigationSort = 12;
    protected static ?string $title = 'Importación Masiva de Grupos';
}