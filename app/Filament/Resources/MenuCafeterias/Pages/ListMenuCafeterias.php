<?php

namespace App\Filament\Resources\MenuCafeterias\Pages;

use App\Filament\Resources\MenuCafeterias\MenuCafeteriaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMenuCafeterias extends ListRecords
{
    protected static string $resource = MenuCafeteriaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
