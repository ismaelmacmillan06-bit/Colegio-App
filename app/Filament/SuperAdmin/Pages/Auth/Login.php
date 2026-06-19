<?php

namespace App\Filament\SuperAdmin\Pages\Auth;

use Illuminate\Contracts\Support\Htmlable;

class Login extends \Filament\Auth\Pages\Login
{
    protected string $view = 'filament.pages.auth.superadmin-login';

    public function getHeading(): string|Htmlable
    {
        return '';
    }

    public function getSubHeading(): string|Htmlable|null
    {
        return null;
    }

    public function hasLogo(): bool
    {
        return false;
    }
}
