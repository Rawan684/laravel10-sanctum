<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SignupController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EmailController;

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


Route::group(['prefix' => 'v1/auth'], function () {
    Route::post('/register', [SignupController::class, 'register']);
    Route::post('/login', [LoginController::class, 'login']);

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/logout', [LoginController::class, 'logout']);
    });

    Route::post('/confirm-2FA-code', [LoginController::class, 'confirmTwoFactorCode']);
    Route::post('/re-send-2FA-code', [LoginController::class, 'resendTwoFactorCode']);
    Route::post('/confirm-email-vf-code/{user}', [SignupController::class, 'confirmEmailVerificationCode'])->name('api.confirm-email-vf-code');
    Route::post('/re-send-email-vf-code', [SignupController::class, 'resendEmailVerificationCode']);
    Route::get('/refresh-token', [LoginController::class, 'refreshToken'])->middleware('auth:sanctum');
    Route::get('/send', [EmailController::class, 'send'])->middleware(['auth:sanctum']);
});
