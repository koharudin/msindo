<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::resource('products', ProductController::class);
Route::get('/sales/predict', [SalesController::class, 'predict'])->name('sales.predict');
Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
Route::get('/sales/create', [SalesController::class, 'create'])->name('sales.create');
Route::post('/sales/create', [SalesController::class, 'store'])->name('sales.store');
Route::get('/sales/edit/{id}', [SalesController::class, 'edit'])->name('sales.edit');
Route::post('/sales/edit/{id}', [SalesController::class, 'update'])->name('sales.update');
Route::delete('/sales/delete/{id}', [SalesController::class, 'update'])->name('sales.destroy');
Route::resource('users', UserController::class);
Route::get('/home', [HomeController::class, 'index'])->name('home');
