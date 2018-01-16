<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16.01.18
 * Time: 17:53
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('uuid')) {
            return $next($request);
        }
        return response()->json(['status' => '401', 'message' => 'Unauthorized'], 401);
    }
}