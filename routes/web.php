<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuoteRequestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Sitio público
|--------------------------------------------------------------------------
*/

Route::redirect('/', '/es');

Route::prefix('{locale}')
    ->whereIn('locale', ['es', 'en'])
    ->group(function () {
        Route::get(
            '/',
            HomeController::class
        )->name('home');

        Route::post(
            '/quotes',
            [QuoteRequestController::class, 'store']
        )
            ->middleware('throttle:10,1')
            ->name('quotes.store');
    });

/*
|--------------------------------------------------------------------------
| Administración
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get(
            '/login',
            [AuthController::class, 'showLogin']
        )->name('login');

        Route::post(
            '/login',
            [AuthController::class, 'login']
        )
            ->middleware('throttle:5,1')
            ->name('login.store');

        Route::middleware([
            'auth',
            'admin',
        ])->group(function () {
            Route::get(
                '/',
                [DashboardController::class, 'index']
            )->name('dashboard');

            Route::get(
                '/products',
                [ProductController::class, 'index']
            )->name('products.index');

            Route::get(
                '/products/{product}/edit',
                [ProductController::class, 'edit']
            )->name('products.edit');

            Route::put(
                '/products/{product}',
                [ProductController::class, 'update']
            )->name('products.update');

            Route::post(
                '/logout',
                [AuthController::class, 'logout']
            )->name('logout');
        });
    });