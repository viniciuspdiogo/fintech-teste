<?php

use App\Http\Controllers\Api\AccountsController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', function (){
    return 'teste';
});
Route::post('/register', [AuthController::class, 'register']);

Route::post('/deposit', [AccountsController::class, 'deposit'])->middleware('auth:sanctum');
Route::post('/withdraw', [AccountsController::class, 'withdraw'])->middleware('auth:sanctum');




Route::fallback(function () {
    return response()->json([
        'status' => 'erro',
        'message' => 'A rota solicitada não foi encontrada. Verifique a URL e o método HTTP.'
    ], 404);
});
