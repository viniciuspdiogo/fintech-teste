<?php

use App\Http\Controllers\Api\AccountsController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TransactionsController;
use Illuminate\Support\Facades\Route;


Route::controller(AuthController::class)->group(function (){
    Route::post('/login',  'login');
    Route::post('/register',  'register');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
});

Route::prefix('accounts')->middleware('auth:sanctum')->group(function () {
    Route::controller(AccountsController::class)->group(function () {
        Route::post('/deposit', 'deposit');
        Route::post('/withdraw', 'withdraw');
        Route::get('/account', 'findByUser');
    });

    Route::controller(TransactionsController::class)->group(function () {
        Route::get('/transactions', 'listTransactions');
    });
});


Route::fallback(function () {
    return response()->json([
        'status' => 'erro',
        'message' => 'A rota solicitada não foi encontrada. Verifique a URL e o método HTTP.'
    ], 404);
});
