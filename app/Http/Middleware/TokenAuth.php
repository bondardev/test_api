<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class TokenAuth
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Token');

        if (!$token || !Cache::pull($token)) { 
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired token.',
            ], 401);
        }

        return $next($request);
    }
}
