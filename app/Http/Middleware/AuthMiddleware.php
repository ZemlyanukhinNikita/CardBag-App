<?php

namespace App\Http\Middleware;

use App\AccessToken;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class AuthMiddleware
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

        if (!AccessToken::where('name', $request->header('token'))
            ->where('expires_at', '!=', null)->where('expires_at', '>', Carbon::now())->first()) {
            abort(401, 'Token not found in database');
        }

        return $next($request);
    }
}