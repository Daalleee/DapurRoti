<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Original home route with products (for authenticated users)
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Landing page for guests
Route::get('/', function () {
    if(auth()->check()) {
        if(auth()->user()->isAdmin()) {
            return redirect()->route('dashboard');
        } else {
            return redirect()->route('home');
        }
    }
    return view('landing-page');
})->name('landing');

// Product detail route accessible to guests
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Route for "pesan sekarang" click - handles guest to auth flow
Route::get('/product/{product}/pesan-sekarang', [HomeController::class, 'pesanSekarang'])->name('product.pesan-sekarang');

// Dashboard for authenticated users
Route::get('/dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return view('dashboard');
    }
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

// This route is for landing page access after login if needed
Route::get('/welcome', function () {
    return view('landing-page');
})->middleware(['auth'])->name('welcome');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Cart routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');



    Route::resource('products', ProductController::class)->middleware('admin');
    Route::resource('orders', OrderController::class)->except(['create', 'edit']);
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');

    // Admin routes
    Route::middleware('admin')->group(function () {
        Route::get('/admin/orders', [OrderController::class, 'index'])->name('admin.orders.index');
        Route::get('/admin/reports', [OrderController::class, 'reports'])->name('admin.reports');
    });
});

require __DIR__.'/auth.php';
