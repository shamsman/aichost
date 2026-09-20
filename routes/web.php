<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartCheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HostingController;
use App\Http\Controllers\VpsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

// Domains
Route::prefix('domains')->name('domains.')->group(function () {
    Route::get('/', [DomainController::class, 'index'])->name('index');
    Route::post('/check', [DomainController::class, 'check'])->name('check');
    Route::get('/check', [DomainController::class, 'check'])->name('check.get');
});

// Shared Hosting (CWP)
Route::prefix('shared-hosting')->name('hosting.')->group(function () {
    Route::get('/', [HostingController::class, 'index'])->name('index');
});

// Google Cloud VPS
Route::prefix('vps-hosting')->name('vps.')->group(function () {
    Route::get('/', [VpsController::class, 'index'])->name('index');
});

// Shopping Cart & Checkout
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartCheckoutController::class, 'cart'])->name('index');
    Route::post('/add', [CartCheckoutController::class, 'addToCart'])->name('add');
    Route::get('/remove/{id}', [CartCheckoutController::class, 'removeFromCart'])->name('remove');
});

Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CartCheckoutController::class, 'checkout'])->name('index');
    Route::post('/process', [CartCheckoutController::class, 'processCheckout'])->name('process');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'processLogin'])->name('login.process');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'processRegister'])->name('register.process');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout.get')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Customer Dashboard (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::get('/services', [DashboardController::class, 'services'])->name('services');
    Route::get('/services/{service}/cwp-sso', [HostingController::class, 'sso'])->name('services.cwp-sso');
    Route::post('/vps/{instance}/{action}', [VpsController::class, 'action'])->name('vps.action');
    Route::get('/domains', [DashboardController::class, 'domains'])->name('domains');
    Route::get('/invoices', [DashboardController::class, 'invoices'])->name('invoices');
    Route::get('/invoices/{invoice}', [DashboardController::class, 'invoiceShow'])->name('invoices.show');
});

/*
|--------------------------------------------------------------------------
| Public API Endpoints
|--------------------------------------------------------------------------
*/
Route::prefix('api/v1')->group(function () {
    Route::get('/domains/check', [DomainController::class, 'check']);
    Route::post('/domains/check', [DomainController::class, 'check']);
});
