<?php

namespace App\Http\Middleware;

use App\Models\Administrador;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !($user instanceof Administrador)) {
            throw new AccessDeniedHttpException('Acceso denegado: se requieren privilegios administrativos.');
        }

        if (!$user->activo) {
            throw new AccessDeniedHttpException('Su cuenta administrativa se encuentra inactiva o suspendida.');
        }

        return $next($request);
    }
}
