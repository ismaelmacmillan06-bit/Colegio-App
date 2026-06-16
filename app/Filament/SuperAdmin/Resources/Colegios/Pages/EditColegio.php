<?php

namespace App\Filament\SuperAdmin\Resources\Colegios\Pages;

use App\Filament\SuperAdmin\Resources\ColegioResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditColegio extends EditRecord
{
    protected static string $resource = ColegioResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
