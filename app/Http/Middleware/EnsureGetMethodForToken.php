<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureGetMethodForToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Проверяем, что путь совпадает и метод не GET
        if ($request->is('api/v1/token') && !$request->isMethod('get')) {
            return response()->json([
                'success' => false,
                'message' => 'The HTTP method used is not allowed for this route.',
                'supported_methods' => ['GET'],
            ], 405);
        }

        return $next($request);
    }
}
