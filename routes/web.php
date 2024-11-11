<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductScraperController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LogController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/scrape-products', [ProductScraperController::class, 'scrape'])->name('scrape.products');
Route::get('/products/{log?}', [ProductController::class, 'index'])->name('products.index');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('/logs/{log}', [LogController::class, 'show'])->name('logs.show');
Route::get('/logs/', [LogController::class, 'index'])->name('logs.index');