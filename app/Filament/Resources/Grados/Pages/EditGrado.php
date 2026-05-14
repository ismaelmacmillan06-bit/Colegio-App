<?php

namespace App\Filament\Resources\Grados\Pages;

use App\Filament\Resources\Grados\GradoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGrado extends EditRecord
{
    protected static string $resource = GradoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
