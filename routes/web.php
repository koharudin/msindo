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
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
Route::get('/products/rop', [ProductController::class, 'showRopFromSales'])->name('products.rop');
Route::get('/sales/predict', [SalesController::class, 'predict'])->name('sales.predict');
Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
Route::get('/sales/create', [SalesController::class, 'create'])->name('sales.create');
Route::post('/sales/create', [SalesController::class, 'store'])->name('sales.store');
Route::get('/sales/edit/{id}', [SalesController::class, 'edit'])->name('sales.edit');
Route::post('/sales/edit/{id}', [SalesController::class, 'update'])->name('sales.update');
Route::delete('/sales/delete/{id}', [SalesController::class, 'update'])->name('sales.destroy');
Route::resource('users', UserController::class);
Route::get('/home', [HomeController::class, 'index'])->name('home');
