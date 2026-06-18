<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class AsignarCredencial extends Page
{
    protected string $view = 'filament.pages.asignar-credencial';
    protected static ?string $navigationLabel = 'Asignar Credencial NFC';
    protected static ?string $title = 'Asignación Rápida de Credenciales NFC';
    protected static ?int $navigationSort = 13;
    public static function getNavigationIcon(): string|\BackedEnum|null { return 'heroicon-o-credit-card'; }

    public static function getNavigationGroup(): ?string
    {
        return 'Escuela';
    }
}