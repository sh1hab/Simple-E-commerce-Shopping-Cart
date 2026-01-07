<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('products.index');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/products', [ProductController::class, 'index'])
        ->name('products.index');
    
    Route::get('/cart', [CartController::class, 'index'])
        ->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])
        ->name('cart.store');
    Route::patch('/cart/{cartItem}', [CartController::class, 'update'])
        ->name('cart.update');
    Route::delete('/cart/{cartItem}', [CartController::class, 'destroy'])
        ->name('cart.destroy');
    Route::post('/checkout', [CartController::class, 'checkout'])
        ->name('cart.checkout');
    
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});


require __DIR__.'/auth.php';
