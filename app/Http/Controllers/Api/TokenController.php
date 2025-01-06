<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;

class TokenController extends Controller
{
    public function getToken()
    {

        $token = Str::random(80);

        Cache::put($token, true, now()->addMinutes(40));

        return response()->json([
            'success' => true,
            'token' => $token,
        ], 200);

    }
}
