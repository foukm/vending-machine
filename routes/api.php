<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/user', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/products', [ProductController::class, 'index']);
Route::post('/logout/all', [UserController::class, 'logoutAll']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/deposit', [TransactionController::class, 'deposit']);
    Route::post('/buy', [TransactionController::class, 'buy']);
    Route::post('/reset', [TransactionController::class, 'reset']);

    //users CRUD
    Route::get('/user/', [UserController::class, 'show']);
    Route::put('/user/', [UserController::class, 'update']);
    Route::get('/user/products', [UserController::class, 'products']);
    Route::delete('/user/delete', [UserController::class, 'destroy']);
    Route::post('/user/logout', [UserController::class, 'logout']);


    //products CRUD
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/products/{product}', [ProductController::class, 'show']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);
});
