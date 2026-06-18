<?php

namespace App\Filament\Resources\Alumnos\Pages;

use App\Filament\Resources\Alumnos\AlumnoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAlumno extends CreateRecord
{
    protected static string $resource = AlumnoResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['colegio_id'] = auth()->user()->colegio_id;
        return $data;
    }
}
