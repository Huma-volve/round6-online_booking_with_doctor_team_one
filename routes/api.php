<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\EmailAuthController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\FacebookAuthController;
use App\Http\Controllers\Auth\PhoneLoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ForegetPasswordController;
use App\Http\Controllers\Auth\UpdatePasswordController;

//Authentication
Route::controller(EmailAuthController::class)->prefix('email')->group(function () {
    Route::post('/register', 'EmailRegister');
    Route::post('/login', 'EmailLogin');
});
Route::controller(GoogleAuthController::class)->prefix('google')->group(function () {
    Route::post('/auth', 'handleGoogleToken');        
});
Route::controller(FacebookAuthController::class)->prefix('facebook')->group(function () {
    Route::post('/auth', 'handleFacebookToken'); 
});
Route::controller(PhoneLoginController::class)->prefix('phone')->group(function () {

    Route::post('login', 'PhoneLogin');
    Route::post('verify-otp', 'verifyOtp');
});
Route::controller(ForegetPasswordController::class)->prefix('password')->group(function(){
    Route::post('send-email','sendEmail');
    Route::post('verify-otp','verifyOTP');
    Route::post('/reset','passwordReset');
});
Route::put('password/update',[UpdatePasswordController::class,'changePassword'])->middleware('auth:sanctum');
Route::post('/logout', [LogoutController::class, 'logout'])->middleware('auth:sanctum');
