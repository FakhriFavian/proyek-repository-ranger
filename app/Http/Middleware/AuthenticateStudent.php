<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateStudent
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('student')->check()) {
            return redirect()->guest(route('user.login'));
        }

        return $next($request);
    }
}
