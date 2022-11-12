<?php


use App\Http\Controllers\ApiV1\Account\PhoneVerificationController;
use App\Http\Controllers\ApiV1\Shared\RouteController;
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

Route::get('/routes', [RouteController::class, 'index']);
