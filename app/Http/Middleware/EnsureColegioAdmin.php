<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureColegioAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return $next($request);
        }

        if (auth()->user()->is_super_admin) {
            abort(403, 'Los super administradores deben usar /superadmin.');
        }

        if (! auth()->user()->colegio_id) {
            abort(403, 'Tu cuenta no está asociada a ningún colegio. Contacta al administrador.');
        }

        return $next($request);
    }
}
