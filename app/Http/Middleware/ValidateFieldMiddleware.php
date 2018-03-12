<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidateFieldMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $messages = [
            'network_id.exists' => 'Такой соц.сети в базе данных нет',
        ];

        Validator::make($request->all(), [
            'network_id' => 'integer|required|exists:networks,id',
            'token' => 'required',
            'uid' => 'required',
        ], $messages);

        return $next($request);
    }
}