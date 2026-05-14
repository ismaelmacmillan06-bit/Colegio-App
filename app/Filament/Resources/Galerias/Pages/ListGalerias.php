<?php

namespace App\Filament\Resources\Galerias\Pages;

use App\Filament\Resources\Galerias\GaleriaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGalerias extends ListRecords
{
    protected static string $resource = GaleriaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
