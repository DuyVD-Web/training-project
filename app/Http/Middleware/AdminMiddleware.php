<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role_id === Role::where('name', UserRole::Admin)->first()->id) {
            return $next($request);
        }
        abort(Response::HTTP_FORBIDDEN);
    }
}
