<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;


class InvalidUuidMiddleware
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!preg_match('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i',
            $request->header('uuid'))
        ) {
            abort(400, 'Invalid uuid');
        }
        return $next($request);
    }
}