<?php

namespace App\Filament\Resources\Alumnos\Pages;

use App\Filament\Pages\ImportarAlumnos;
use App\Filament\Resources\Alumnos\AlumnoResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAlumnos extends ListRecords
{
    protected static string $resource = AlumnoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('+ Nuevo Alumno'),
            Action::make('importar')
                ->label('Importar XLSX')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('gray')
                ->url(ImportarAlumnos::getUrl()),
        ];
    }
}
