<?php
use App\Http\Controllers\Api\TokenController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PositionController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/token', [TokenController::class, 'getToken']);
    Route::get('/positions', [PositionController::class, 'index']);
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::post('/users', [UserController::class, 'store']);

});