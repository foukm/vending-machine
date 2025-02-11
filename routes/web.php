<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\BuyerController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\SellerController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');


Route::get('/buyer', [BuyerController::class, 'index'])->name('buyer.index');
Route::get('/buyer/edit', [BuyerController::class, 'edit'])->name('buyer.edit');

Route::get('/seller', [SellerController::class, 'index'])->name('seller.index');
Route::get('/seller/edit', [SellerController::class, 'edit'])->name('seller.edit');

Route::get('/seller/products/add', [ProductController::class, 'add'])->name('seller.products.add');
Route::get('/seller/products/edit', [ProductController::class, 'edit'])->name('seller.products.edit');
Route::get('/seller/products/delete', [ProductController::class, 'delete'])->name('seller.products.delete');
