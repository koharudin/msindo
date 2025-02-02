<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

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
Route::get('/sales/print/{sale}', [SalesController::class, 'print'])->name('sales.print');
Route::post('/sales/edit/{id}', [SalesController::class, 'update'])->name('sales.update');
Route::delete('/sales/delete/{id}', [SalesController::class, 'update'])->name('sales.destroy');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
Route::get('/categories/{category}/stock', [CategoryController::class, 'stock'])->name('categories.stock');
Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
Route::resource('users', UserController::class);
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/inventory', [HomeController::class, 'inventory'])->name('inventory');
