<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureGetMethodForPositions
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

        if ($request->is('api/v1/positions') && !$request->isMethod('get')) {
            return response()->json([
                'success' => false,
                'message' => 'The HTTP method used is not allowed for this route.',
                'supported_methods' => ['GET'],
            ], 405);
        }

        return $next($request);
    }
}
