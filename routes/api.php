<?php

use App\Http\Controllers\ApiV1\CarController;
use App\Http\Controllers\ApiV1\ConversationController;
use App\Http\Controllers\ApiV1\PhoneVerificationController;
use App\Http\Controllers\ApiV1\ProfileController;
use App\Http\Controllers\ApiV1\ReservationController;
use App\Http\Controllers\ApiV1\RouteReservationController;
use App\Http\Controllers\ApiV1\SearchController;
use App\Http\Controllers\RouteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/phone/send-verification-code', [PhoneVerificationController::class, 'sendVerificationCode']);
Route::post('/phone/verify-code', [PhoneVerificationController::class, 'verifyCode']);

Route::middleware('auth:sanctum')->group(static function () {
    Route::post('/routes/{route}/cancel', [RouteController::class, 'cancel']);
    Route::post('/routes/{route}/reservations/{reservation}/cancel', [RouteReservationController::class, 'cancel']);
    Route::post('/routes/{route}/reservations/{reservation}/confirm', [RouteReservationController::class, 'confirm']);
    Route::apiResource('routes', RouteController::class)->except(['destroy']);

    Route::apiResource('reservations', ReservationController::class)->except(['show', 'update']);

    Route::get('/conversations', [ConversationController::class, 'index']);
    Route::post('/conversations', [ConversationController::class, 'store']);

    Route::prefix('/profile')->group(static function () {
        Route::get('/', [ProfileController::class, 'show']);
        Route::put('/', [ProfileController::class, 'update']);

        Route::apiResource('cars', CarController::class)->except('show');
    });
});

Route::get('/search', [SearchController::class, 'search']);
