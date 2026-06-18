<?php

namespace App\Filament\Resources\Colegiaturas\Pages;

use App\Filament\Resources\Colegiaturas\ColegiaturaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateColegiatura extends CreateRecord
{
    protected static string $resource = ColegiaturaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['colegio_id'] = auth()->user()->colegio_id;
        return $data;
    }
}
