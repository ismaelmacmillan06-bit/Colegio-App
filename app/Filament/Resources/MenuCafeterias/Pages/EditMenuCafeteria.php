<?php

namespace App\Filament\Resources\MenuCafeterias\Pages;

use App\Filament\Resources\MenuCafeterias\MenuCafeteriaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMenuCafeteria extends EditRecord
{
    protected static string $resource = MenuCafeteriaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
