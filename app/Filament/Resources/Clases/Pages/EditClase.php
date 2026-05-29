<?php

namespace App\Filament\Resources\Clases\Pages;

use App\Filament\Resources\Clases\ClaseResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditClase extends EditRecord
{
    protected static string $resource = ClaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
