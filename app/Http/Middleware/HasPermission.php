<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class HasPermission
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()->role->name === UserRole::Admin) {
            return $next($request);
        }

        if (!$request->user()->hasPermission($request->route()->getName())) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
