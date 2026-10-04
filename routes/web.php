<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BoxController;
use App\Http\Controllers\EncyclopediaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PairController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminUserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Layar loading. Kalau sudah login, langsung ke home.
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    }
    return view('splash');
})->name('splash');

// Hanya untuk tamu (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

// Hanya untuk yang sudah login
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/box/{box}', [BoxController::class, 'show'])->name('box.show');
    Route::post('/box/{box}/pair', [PairController::class, 'check'])->name('box.pair.check');
    Route::get('/ensiklopedia', [EncyclopediaController::class, 'index'])->name('encyclopedia');
   Route::get('/upgrade', [PaymentController::class, 'show'])->name('upgrade');
Route::post('/upgrade', [PaymentController::class, 'checkout'])->name('upgrade.checkout');
Route::get('/upgrade/order/{order}', [PaymentController::class, 'order'])->name('upgrade.order');
Route::post('/upgrade/order/{order}/proof', [PaymentController::class, 'uploadProof'])->name('upgrade.proof.upload');
Route::get('/upgrade/order/{order}/proof', [PaymentController::class, 'proof'])->name('upgrade.proof');
});

Route::middleware(['auth', 'can:admin'])->prefix('admin')->name('admin.')->group(function () {
  Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/proof', [AdminOrderController::class, 'proof'])->name('orders.proof');
   Route::patch('/orders/{order}/approve', [AdminOrderController::class, 'approve'])->name('orders.approve');
Route::patch('/orders/{order}/reject', [AdminOrderController::class, 'reject'])->name('orders.reject');
Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
Route::patch('/users/{user}/pro', [AdminUserController::class, 'pro'])->name('users.pro');
Route::patch('/users/{user}/admin', [AdminUserController::class, 'admin'])->name('users.admin');
Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
});