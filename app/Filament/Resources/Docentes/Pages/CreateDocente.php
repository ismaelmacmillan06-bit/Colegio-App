<?php

namespace App\Filament\Resources\Docentes\Pages;

use App\Filament\Resources\Docentes\DocenteResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDocente extends CreateRecord
{
    protected static string $resource = DocenteResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['colegio_id'] = auth()->user()->colegio_id;
        return $data;
    }
}
