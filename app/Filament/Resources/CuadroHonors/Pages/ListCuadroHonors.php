<?php

namespace App\Filament\Resources\CuadroHonors\Pages;

use App\Filament\Resources\CuadroHonors\CuadroHonorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCuadroHonors extends ListRecords
{
    protected static string $resource = CuadroHonorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
