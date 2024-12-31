<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use App\Traits\HttpResponses;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    use HttpResponses;
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()->role->name === UserRole::Admin) {
            return $next($request);
        }

        if (!$request->user()->hasPermission($request->route()->getName())) {
            return $this->responseError(message:"Unauthorized", code:401);
        }

        return $next($request);
    }
}
