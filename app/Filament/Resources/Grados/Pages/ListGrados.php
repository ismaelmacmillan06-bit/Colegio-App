<?php

namespace App\Filament\Resources\Grados\Pages;

use App\Filament\Resources\Grados\GradoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGrados extends ListRecords
{
    protected static string $resource = GradoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
