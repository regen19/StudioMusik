<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $currentRole = $user->user_role;

        if (empty($roles)) {
            return $next($request);
        }

        if (!in_array($currentRole, $roles, true)) {
            return response()->view('errors.forbidden_403', [], 403);
        }

        return $next($request);
    }
}