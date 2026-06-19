<?php

namespace App\Filament\Resources\Docentes\Pages;

use App\Filament\Resources\Docentes\DocenteResource;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;

class EditDocente extends EditRecord
{
    protected static string $resource = DocenteResource::class;

    private ?string $pendingEmail = null;
    private ?string $pendingPassword = null;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if ($this->record->user) {
            $data['email'] = $this->record->user->email;
        }
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->pendingEmail    = $data['email'] ?? null;
        $this->pendingPassword = $data['password'] ?? null;
        unset($data['email'], $data['password']);
        return $data;
    }

    protected function afterSave(): void
    {
        if (! $this->pendingEmail) return;

        $docente = $this->record;

        if ($docente->user_id && $user = User::find($docente->user_id)) {
            $user->name  = $docente->nombre . ' ' . $docente->apellidos;
            $user->email = $this->pendingEmail;
            if ($this->pendingPassword) {
                $user->password = Hash::make($this->pendingPassword);
            }
            $user->save();
        } else {
            $user = User::create([
                'name'       => $docente->nombre . ' ' . $docente->apellidos,
                'email'      => $this->pendingEmail,
                'password'   => Hash::make($this->pendingPassword ?? str()->random(16)),
                'colegio_id' => $docente->colegio_id,
            ]);
            $docente->update(['user_id' => $user->id]);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
