<?php

use App\Http\Controllers\Api\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\Auth\RegisteredUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/register', [RegisteredUserController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::delete('/logout', [AuthenticatedSessionController::class, 'destroy']);
});
