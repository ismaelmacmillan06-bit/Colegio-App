<?php

namespace App\Filament\Pages;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected static ?string $title = '';
    protected static ?string $navigationLabel = 'Dashboard';

    public function getColumns(): int | array
    {
        return 1;
    }

    protected function getHeaderWidgets(): array
    {
        return [];
    }
}