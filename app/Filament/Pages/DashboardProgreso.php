<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class DashboardProgreso extends Page
{
    protected static ?string $navigationLabel = 'Dashboard Progreso';
    protected static ?int $navigationSort = -1;
    protected string $view = 'filament.pages.dashboard-progreso';

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-chart-bar';
    }
}
