<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthSuperadmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth('superadmin')->check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        return $next($request);
    }
}
