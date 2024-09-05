<?php

use App\Http\Controllers\Api\Auth\CompanyController;
use App\Http\Controllers\Api\Auth\PartnerController;
use App\Http\Controllers\Api\Auth\UserController;
use App\Http\Controllers\Api\PasswordResetController;
use Illuminate\Support\Facades\Route;

    Route::group(['prefix' => 'user'], function () {
        Route::post('register', [UserController::class, 'register']);
        Route::post('login', [UserController::class, 'login']);
        Route::post('verify-otp', [UserController::class, 'verifyOtp']);
        Route::post('logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
        Route::get('user', [UserController::class, 'user'])->middleware('auth:sanctum');
    });

    Route::post('password/email', [PasswordResetController::class, 'RestPasswordRequest'])->middleware('auth:sanctum');


    Route::prefix('company')->group(function () {
        Route::post('register', [CompanyController::class, 'register']);
        Route::post('login', [CompanyController::class, 'login']);
        Route::post('verify-otp', [CompanyController::class, 'verifyOtp']);
        Route::post('logout', [CompanyController::class, 'logout'])->middleware('auth:sanctum', 'CheckCompany');
        Route::get('company', [CompanyController::class, 'company'])->middleware('auth:sanctum', 'CheckCompany');
    });


    Route::prefix('partner')->group(function () {
        Route::post('register', [PartnerController::class, 'register']);
        Route::post('login', [PartnerController::class, 'login']);
        Route::post('verify-otp', [PartnerController::class, 'verifyOtp']);
        Route::post('logout', [PartnerController::class, 'logout'])->middleware('auth:sanctum');
        Route::get('partner', [PartnerController::class, 'partner'])->middleware('auth:sanctum');
    });

