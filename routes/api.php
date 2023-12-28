<?php

use App\Http\Controllers\Api\V1\AuthenticationController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\CommentController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\EmailVerificatioinController;
use App\Http\Controllers\Api\V1\PasswordResetController;
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
    Route::post('/login', [AuthenticationController::class, 'login'])->name('login');
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);

    // Protected Routes
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/logout', [AuthenticationController::class, 'logout']);
        Route::get('/user', [AuthenticationController::class, 'user']);

        Route::middleware('verified')->group(function () {
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
});

// Route to get CSRF Token
Route::get('/sanctum/csrf-cookie', CsrfCookieController::class . '@show');

Route::get('/email/verify', [EmailVerificatioinController::class, 'notice'])->middleware('auth:sanctum')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [EmailVerificatioinController::class, 'verify'])->middleware(['auth:sanctum', 'signed'])->name('verification.verify');
Route::get('/email/verification-notification', [EmailVerificatioinController::class, 'resend'])->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');

Route::post('/forgot-password', [PasswordResetController::class, 'forgotPassword'])->middleware('guest')->name('password.email');
Route::post('/reset-password', [PasswordResetController::class, 'updatePassword'])->middleware('guest')->name('password.update');