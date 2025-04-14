<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SaleDetailController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Login and Logout Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Routes protected by 'auth' middleware
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('pages.dashboard');
    })->name('dashboard');

    // Product Routes
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::match(['post', 'put'], '/product/{id?}', [ProductController::class, 'save'])->name('products.save');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
});

// User Routes - hanya bisa diakses oleh admin
Route::middleware(['auth', 'role:admin'])->group(function () {

});

Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::match(['post', 'put'], '/user/{id?}', [UserController::class, 'save'])->name('users.save');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

// Sales Routes
Route::middleware('auth')->group(function () {
    Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
    Route::get('/sales/create', [SalesController::class, 'create'])->name('sales.create');
    Route::post('/sales', [SalesController::class, 'store'])->name('sales.store');

    Route::delete('/sales/{sale}', [SalesController::class, 'destroy'])->name('sales.destroy');

    Route::post('/sales/create/post', [SalesController::class, 'postCreate'])->name('sales.postCreate');

    Route::post('/sales/process-payment', [SalesController::class, 'processPayment'])->name('sales.processPayment');

    Route::get('/sales/detail-print/{id}', [SaleDetailController::class, 'printDetail'])->name('sales.detailPrint');


    Route::get('/sales/create/member/{id}', [SalesController::class, 'memberPayment'])->name('sales.memberPayment');
    Route::post('/sales/{id}/update-member-payment', [SalesController::class, 'updateMemberPayment'])
    ->name('sales.updateMemberPayment');

    Route::get('/sales/detail-print/{id}/pdf', [SalesController::class, 'exportPdf'])
    ->name('sales.exportPdf');


    Route::get('/sales/{id}/detail', [SalesController::class, 'detail'])->name('sales.detail');

    Route::get('/sales/export-excel', [SalesController::class, 'exportExcel'])->name('sales.exportExcel');

    Route::get('/sales/chart', [SalesController::class, 'getSalesChartData'])->name('sales.chart');

    Route::get('/sales/products-chart', [SalesController::class, 'getProductSalesData'])->name('sales.products.chart');
});

