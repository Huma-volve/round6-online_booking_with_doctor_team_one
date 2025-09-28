<?php


use App\Http\Controllers\Api\AddressController;

use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\EmailAuthController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\FacebookAuthController;
use App\Http\Controllers\Auth\PhoneLoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ForegetPasswordController;
use App\Http\Controllers\Auth\UpdatePasswordController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Page Routes
|--------------------------------------------------------------------------
|
| Routes for managing static content pages like Privacy Policy and Terms & Conditions
|
*/

// Public routes (no authentication required)
Route::prefix('pages')->group(function () {
    Route::get('/privacy-policy', [PageController::class, 'privacyPolicy']);
    Route::get('/terms-and-conditions', [PageController::class, 'termsConditions']);
});

// Admin routes (authentication required)
Route::prefix('pages')->group(function () {
    Route::get('/', [PageController::class, 'index']); // Get all pages
    Route::post('/', [PageController::class, 'store']); // Create new page
    Route::put('/{type}', [PageController::class, 'update']); // Update page by type
});

/*
|--------------------------------------------------------------------------
| FAQ Routes
|--------------------------------------------------------------------------
|
| Routes for managing Frequently Asked Questions (FAQs)
|
*/

// Public FAQ routes
Route::prefix('faqs')->group(function () {
    Route::get('/', [FaqController::class, 'index']); // Get active FAQs
    Route::get('/all', [FaqController::class, 'all']); // Get all FAQs
    Route::get('/{id}', [FaqController::class, 'show']); // Get specific FAQ
    Route::post('/', [FaqController::class, 'store']); // Create new FAQ
    Route::put('/{id}', [FaqController::class, 'update']); // Update FAQ
    Route::delete('/{id}', [FaqController::class, 'destroy']); // Delete FAQ
});


Route::middleware('auth:sanctum')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);


    // Addresses
    Route::get('/profile/addresses', [AddressController::class, 'index']);
    Route::post('/profile/addresses', [AddressController::class, 'store']);
    Route::put('/profile/addresses/{address}', [AddressController::class, 'update']);
    Route::delete('/profile/addresses/{address}', [AddressController::class, 'destroy']);
    Route::patch('/profile/addresses/{address}/default', [AddressController::class, 'setDefault']);
});


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
