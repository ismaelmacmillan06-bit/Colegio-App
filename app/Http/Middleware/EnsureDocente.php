<?php

namespace App\Http\Middleware;

use App\Models\Docente;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureDocente
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return $next($request);
        }

        $tieneDocente = Docente::withoutGlobalScopes()
            ->where('user_id', auth()->id())
            ->exists();

        if (! $tieneDocente) {
            abort(403, 'No tienes acceso al portal docente. Contacta al administrador del colegio.');
        }

        return $next($request);
    }
}
