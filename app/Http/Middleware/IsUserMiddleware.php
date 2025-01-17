<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsUserMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->user_role === 'user') {
            return $next($request);
        } else {
            return redirect('/forbidden_403');
        }
    }
}
