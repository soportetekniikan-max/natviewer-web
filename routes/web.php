<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuoteRequestController;
use Illuminate\Support\Facades\Route;

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

            /*
            |--------------------------------------------------------------------------
            | Productos
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/products',
                [ProductController::class, 'index']
            )->name('products.index');

            Route::get(
                '/products/create',
                [ProductController::class, 'create']
            )->name('products.create');

            Route::post(
                '/products',
                [ProductController::class, 'store']
            )->name('products.store');

            Route::get(
                '/products/{product}/edit',
                [ProductController::class, 'edit']
            )->name('products.edit');

            Route::put(
                '/products/{product}',
                [ProductController::class, 'update']
            )->name('products.update');

            Route::patch(
                '/products/{product}/archive',
                [ProductController::class, 'archive']
            )->name('products.archive');

            /*
            |--------------------------------------------------------------------------
            | Variantes
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/products/{product}/variants',
                [ProductVariantController::class, 'index']
            )->name('products.variants.index');

            Route::post(
                '/products/{product}/variants',
                [ProductVariantController::class, 'store']
            )->name('products.variants.store');

            Route::get(
                '/products/{product}/variants/{variant}/edit',
                [ProductVariantController::class, 'edit']
            )->name('products.variants.edit');

            Route::put(
                '/products/{product}/variants/{variant}',
                [ProductVariantController::class, 'update']
            )->name('products.variants.update');

            /*
            |--------------------------------------------------------------------------
            | Imágenes
            |--------------------------------------------------------------------------
            */

            Route::post(
                '/products/{product}/images',
                [ProductImageController::class, 'store']
            )->name('products.images.store');

            Route::put(
                '/products/{product}/images/{image}',
                [ProductImageController::class, 'update']
            )->name('products.images.update');

            Route::patch(
                '/products/{product}/images/{image}/primary',
                [
                    ProductImageController::class,
                    'setPrimary',
                ]
            )->name('products.images.primary');

            Route::delete(
                '/products/{product}/images/{image}',
                [ProductImageController::class, 'destroy']
            )->name('products.images.destroy');

            Route::post(
                '/logout',
                [AuthController::class, 'logout']
            )->name('logout');
        });
    });