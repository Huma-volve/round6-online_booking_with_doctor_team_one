<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController as RegisterController;
use App\Http\Controllers\Auth\LoginController as LoginController;
use App\Http\Controllers\Auth\LogoutController;

//Authentication
Route::controller(RegisterController::class)->prefix('register')->group(function () {
    Route::post('/email-register', [RegisterController::class, 'EmailRegister']);
});
Route::controller(LoginController::class)->prefix('login')->group(function () {
    Route::post('/email-login', 'EmailLogin');
    Route::post('phone-login', 'PhoneLogin');
    Route::post('verify-phone-otp', 'verifyOtp');
});
Route::post('/logout', [LogoutController::class, 'logout'])->middleware('auth:sanctum');
