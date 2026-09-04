<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/es');

Route::prefix('{locale}')
    ->whereIn('locale', ['es', 'en'])
    ->group(function () {
        Route::get('/', HomeController::class)
            ->name('home');
    });