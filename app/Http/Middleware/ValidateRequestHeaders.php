<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateRequestHeaders
{
    /**
     * Обработка входящего запроса.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Проверка Content-Type для POST-запросов
        if ($request->isMethod('post') && !str_contains($request->header('Content-Type', ''), 'multipart/form-data')) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Content-Type. Must be multipart/form-data for POST requests.',
            ], 415);
        }


        // Проверка Accept для GET-запросов
        if ($request->isMethod('get') && $request->header('Accept') !== 'application/json') {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Accept header. Must be application/json for GET requests.',
            ], 406);
        }

        return $next($request);
    }
}
