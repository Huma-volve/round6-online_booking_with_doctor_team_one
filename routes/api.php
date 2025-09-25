<?php

use App\Http\Controllers\Api\PageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
