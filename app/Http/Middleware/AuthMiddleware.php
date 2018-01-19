<?php

namespace App\Http\Middleware;

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
        if (!$request->header('uuid')) {
            return response()->json(['status' => '401', 'message' => 'Unauthorized'], 401);
        }
        return $next($request);
    }
}