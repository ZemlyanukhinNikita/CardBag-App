<?php

namespace App\Http\Middleware;

use App\Token;
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

        if (!$token = Token::where('token', $request->header('token'))->first()) {
            abort(400, 'Token not found in database');
        }
        return $next($request);
    }
}