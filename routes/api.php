<?php

use App\Http\Controllers\Api\AccountsController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::prefix('accounts')->middleware('auth:sanctum')->controller(AccountsController::class)->group(function () {
    Route::post('/deposit', 'deposit');
    Route::post('/withdraw', 'withdraw');
});


Route::fallback(function () {
    return response()->json([
        'status' => 'erro',
        'message' => 'A rota solicitada não foi encontrada. Verifique a URL e o método HTTP.'
    ], 404);
});
