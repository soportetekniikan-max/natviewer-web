<?php

use App\Http\Controllers\SeoController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactSettingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\QuoteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuoteRequestController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/es');

Route::get(
    '/sitemap.xml',
    [SeoController::class, 'sitemap']
)->name('sitemap');

Route::get(
    '/robots.txt',
    [SeoController::class, 'robots']
)->name('robots');

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
            | Catálogo
            |--------------------------------------------------------------------------
            */

            Route::middleware(
                'admin.role:catalog'
            )->group(function () {
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

                Route::get(
                    '/categories',
                    [CategoryController::class, 'index']
                )->name('categories.index');

                Route::post(
                    '/categories',
                    [CategoryController::class, 'store']
                )->name('categories.store');

                Route::get(
                    '/categories/{category}/edit',
                    [CategoryController::class, 'edit']
                )->name('categories.edit');

                Route::put(
                    '/categories/{category}',
                    [CategoryController::class, 'update']
                )->name('categories.update');

                Route::patch(
                    '/categories/{category}/toggle',
                    [CategoryController::class, 'toggleStatus']
                )->name('categories.toggle');

                Route::get(
                    '/brands',
                    [BrandController::class, 'index']
                )->name('brands.index');

                Route::post(
                    '/brands',
                    [BrandController::class, 'store']
                )->name('brands.store');

                Route::get(
                    '/brands/{brand}/edit',
                    [BrandController::class, 'edit']
                )->name('brands.edit');

                Route::put(
                    '/brands/{brand}',
                    [BrandController::class, 'update']
                )->name('brands.update');

                Route::patch(
                    '/brands/{brand}/toggle',
                    [BrandController::class, 'toggleStatus']
                )->name('brands.toggle');
            });

            /*
            |--------------------------------------------------------------------------
            | Comercial
            |--------------------------------------------------------------------------
            */

            Route::middleware(
                'admin.role:commercial'
            )->group(function () {
                Route::get(
                    '/quotes',
                    [QuoteController::class, 'index']
                )->name('quotes.index');

                Route::get(
                    '/quotes/{quote}',
                    [QuoteController::class, 'show']
                )->name('quotes.show');

                Route::put(
                    '/quotes/{quote}',
                    [QuoteController::class, 'update']
                )->name('quotes.update');

                Route::get(
                    '/contact-settings',
                    [ContactSettingController::class, 'edit']
                )->name('contact-settings.edit');

                Route::put(
                    '/contact-settings',
                    [ContactSettingController::class, 'update']
                )->name('contact-settings.update');
            });

            /*
            |--------------------------------------------------------------------------
            | Usuarios
            |--------------------------------------------------------------------------
            */

            Route::middleware(
                'admin.role:super_admin'
            )->group(function () {
                Route::get(
                    '/users',
                    [AdminUserController::class, 'index']
                )->name('users.index');

                Route::get(
                    '/users/create',
                    [AdminUserController::class, 'create']
                )->name('users.create');

                Route::post(
                    '/users',
                    [AdminUserController::class, 'store']
                )->name('users.store');

                Route::get(
                    '/users/{user}/edit',
                    [AdminUserController::class, 'edit']
                )->name('users.edit');

                Route::put(
                    '/users/{user}',
                    [AdminUserController::class, 'update']
                )->name('users.update');

                Route::patch(
                    '/users/{user}/toggle-access',
                    [
                        AdminUserController::class,
                        'toggleAccess',
                    ]
                )->name('users.toggle-access');
            });

            Route::post(
                '/logout',
                [AuthController::class, 'logout']
            )->name('logout');
        });
    });