<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthSocialNetworkMiddleware
{
    /**
     * Авторизация пользователя
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->header('token')) {
            abort(401, 'Unauthorized');
        }

        return $next($request);
    }
}