<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::resource('posts', PostController::class)->only(['index', 'show']);
    Route::resource('categories', CategoryController::class)->only(['index', 'show']);
    Route::resource('posts.comments', CommentController::class)->only(['index', 'show']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::delete('logout', [AuthenticatedSessionController::class, 'destroy']);

        Route::resource('posts', PostController::class)->only(['store', 'update', 'destroy']);
        Route::resource('categories', CategoryController::class)->only(['store', 'update', 'destroy']);
        Route::resource('posts.comments', CommentController::class)->only(['store', 'update', 'destroy']);
        Route::put('posts/{post}/status', [PostController::class, 'updateStatus'])->name('posts.status');
        Route::put('posts/{post}/comments/{comment}/status', [CommentController::class, 'updateStatus'])->name('posts.comments.status');
    });
});
