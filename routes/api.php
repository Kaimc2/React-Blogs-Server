<?php

use App\Http\Controllers\Api\V1\AuthenticationController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\Api\V1\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\UserController;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'v1'], function () {
    // Public Routes
    Route::apiResource('posts', PostController::class);
    Route::post('/register', [AuthenticationController::class, 'register']);
    Route::post('/login', [AuthenticationController::class, 'login']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);

    // Protected Routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthenticationController::class, 'logout']);
        Route::get('/user', [AuthenticationController::class, 'user']);

        Route::get('/dashboard/posts', [DashboardController::class, 'index']);
        Route::post('/dashboard/categories/create', [CategoryController::class, 'store']);
        Route::put('/dashboard/categories/{category}', [CategoryController::class, 'update']);
        Route::delete('/dashboard/categories/{category}', [CategoryController::class, 'destroy']);
        Route::put('/dashboard/account', [UserController::class, 'update']);

        Route::post('/comment/create', [CommentController::class, 'store']);
        Route::put('/comment/{comment}', [CommentController::class, 'update']);
        Route::delete('/comment/{comment}', [CommentController::class, 'destroy']);
    });
});

// Route to get CSRF Token
Route::get('/sanctum/csrf-cookie', CsrfCookieController::class . '@show');
