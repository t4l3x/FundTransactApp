<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransferController;
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
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
});

Route::middleware('auth:sanctum')->group(function () {
    // Define your authenticated routes here
    Route::controller(AccountController::class)->group(function () {
        Route::post('account', 'createAccount');
        Route::get('accounts/{user}', [AccountController::class, 'getClientAccounts']);
        Route::get('accounts/{account}/transactions', [AccountController::class, 'getTransactionHistory']);
    });

    Route::controller(TransferController::class)->group(function () {
        Route::post('transfer-funds', 'transferFunds');
    });

});
