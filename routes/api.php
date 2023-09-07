<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::name('auth.')
    ->prefix('auth')
    ->group(function () {
        Route::group(['middleware' => ['guest']], function () {
            Route::post('login', [AuthController::class, 'store'])->name('login');
            Route::apiResource('register', RegistrationController::class)->only(['store']);
            Route::apiResource('forgot-password', ForgotPasswordController::class)->only(['store']);
            Route::apiResource('reset-password', ResetPasswordController::class)->only(['store']);
        });
        Route::group(['middleware' => ['auth:sanctum']], function () {
            Route::delete('logout', [AuthController::class, 'destroy'])->name('logout');
        });
    });
