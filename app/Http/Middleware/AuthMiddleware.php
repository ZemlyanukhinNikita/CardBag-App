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
        if ($request->header('uuid')) {
            if (!$uuid = preg_match('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i',
                $request->header('uuid'))
            ) {
                return response()->json(['status' => '400', 'message' => 'Invalid uuid'], 400);
            }
        } else {
            return response()->json(['status' => '401', 'message' => 'Unauthorized'], 401);
        }
        return $next($request);
    }
}