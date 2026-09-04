<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuoteRequestController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/es');

Route::prefix('{locale}')
    ->whereIn('locale', ['es', 'en'])
    ->group(function () {
        Route::get('/', HomeController::class)
            ->name('home');

        Route::post('/quotes', [QuoteRequestController::class, 'store'])
            ->middleware('throttle:10,1')
            ->name('quotes.store');
    });