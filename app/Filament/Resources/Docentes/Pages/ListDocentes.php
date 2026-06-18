<?php

namespace App\Filament\Resources\Docentes\Pages;

use App\Filament\Pages\ImportarDocentes;
use App\Filament\Resources\Docentes\DocenteResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDocentes extends ListRecords
{
    protected static string $resource = DocenteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('+ Nuevo Docente'),
            Action::make('importar')
                ->label('Importar XLSX')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('gray')
                ->url(ImportarDocentes::getUrl()),
        ];
    }
}
