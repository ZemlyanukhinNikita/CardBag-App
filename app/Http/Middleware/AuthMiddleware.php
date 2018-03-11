<?php

namespace App\Http\Middleware;

use App\AccessToken;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        DB::table('access_tokens')->where('expires_at', '<=', (Carbon::now()))->update(['expires_at' => null]);

        if ($request->input('access_token')) {
            if (!AccessToken::where('name', $request->input('access_token'))->where('expires_at', '!=', null)->first()) {
                abort(401, 'Token not found in database');
            }
        }

        if ($request->header('token')) {
            if (!AccessToken::where('name', $request->header('token'))->where('expires_at', '!=', null)->first()) {
                abort(401, 'Token not found in database');
            }
        }

        return $next($request);
    }
}