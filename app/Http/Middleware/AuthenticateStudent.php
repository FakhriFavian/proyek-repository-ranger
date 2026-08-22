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
            return redirect()->route('user.login', array_filter([
                'item_id' => $request->query('item_id'),
                'tanggal' => $request->query('tanggal'),
                'jam' => $request->query('jam'),
                'jumlah' => $request->query('jumlah'),
            ]));
        }

        return $next($request);
    }
}
