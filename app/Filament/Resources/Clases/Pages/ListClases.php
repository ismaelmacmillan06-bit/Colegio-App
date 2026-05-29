<?php

namespace App\Filament\Resources\Clases\Pages;

use App\Filament\Resources\Clases\ClaseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListClases extends ListRecords
{
    protected static string $resource = ClaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
