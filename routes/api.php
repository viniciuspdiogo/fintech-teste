<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::ApiResource('accounts','AccountsController')->except([
    'show', 'create', 'edit', 'destroy'
])->middleware('auth:sanctum');


Route::ApiResource('login', 'LoginController');
