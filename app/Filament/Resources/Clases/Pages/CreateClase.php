<?php

namespace App\Filament\Resources\Clases\Pages;

use App\Filament\Resources\Clases\ClaseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateClase extends CreateRecord
{
    protected static string $resource = ClaseResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['colegio_id'] = auth()->user()->colegio_id;
        return $data;
    }
}
