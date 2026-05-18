<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthChecker
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('api')->check()) {
            return response()->json([
            "status" => Response::HTTP_UNAUTHORIZED,
            "statusMessage" => Response::$statusTexts[Response::HTTP_UNAUTHORIZED],
            "message" => 'Token Expired',
            'timestamp' => floor(microtime(true) * 1000),], 401);
        }

        return $next($request);
    }
}