<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::resource('products', ProductController::class);
Route::resource('sales', SalesController::class);
Route::get('/sales/mape', [SalesController::class, 'calculateMape'])->name('sales.mape');
Route::resource('users', UserController::class);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
