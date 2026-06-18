<?php

namespace App\Filament\Resources\Clases\Pages;

use App\Filament\Pages\ImportarClases;
use App\Filament\Resources\Clases\ClaseResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListClases extends ListRecords
{
    protected static string $resource = ClaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('+ Nueva Clase'),
            Action::make('importar')
                ->label('Importar XLSX')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('gray')
                ->url(ImportarClases::getUrl()),
        ];
    }
}
