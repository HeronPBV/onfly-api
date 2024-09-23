<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\ExpenseController;

Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('despesas', ExpenseController::class);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('logout', [AuthController::class, 'logout']);

});

Route::post('login', [AuthController::class, 'login']);

Route::post('registrar', [AuthController::class, 'register']);
