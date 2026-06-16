<?php

namespace App\Filament\SuperAdmin\Resources\Colegios\Pages;

use App\Filament\SuperAdmin\Resources\ColegioResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListColegios extends ListRecords
{
    protected static string $resource = ColegioResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
