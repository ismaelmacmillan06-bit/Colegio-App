<?php

namespace App\Filament\SuperAdmin\Resources\Colegios\Pages;

use App\Filament\SuperAdmin\Resources\ColegioResource;
use Filament\Resources\Pages\CreateRecord;

class CreateColegio extends CreateRecord
{
    protected static string $resource = ColegioResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
