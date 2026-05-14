<?php

namespace App\Filament\Resources\CuadroHonors\Pages;

use App\Filament\Resources\CuadroHonors\CuadroHonorResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCuadroHonor extends EditRecord
{
    protected static string $resource = CuadroHonorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
