<?php

namespace App\Filament\Resources\Docentes\Pages;

use App\Filament\Resources\Docentes\DocenteResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateDocente extends CreateRecord
{
    protected static string $resource = DocenteResource::class;

    private ?string $pendingEmail = null;
    private ?string $pendingPassword = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->pendingEmail    = $data['email'] ?? null;
        $this->pendingPassword = $data['password'] ?? null;
        unset($data['email'], $data['password']);

        $data['colegio_id'] = auth()->user()->colegio_id;
        return $data;
    }

    protected function afterCreate(): void
    {
        if (! $this->pendingEmail) return;

        $user = User::create([
            'name'       => $this->record->nombre . ' ' . $this->record->apellidos,
            'email'      => $this->pendingEmail,
            'password'   => Hash::make($this->pendingPassword ?? str()->random(16)),
            'colegio_id' => $this->record->colegio_id,
        ]);

        $this->record->update(['user_id' => $user->id]);
    }
}
