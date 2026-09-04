<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/es');

Route::prefix('{locale}')
    ->whereIn('locale', ['es', 'en'])
    ->group(function () {
        Route::get('/', function (string $locale) {
            App::setLocale($locale);

            return view('home', [
                'locale' => $locale,
            ]);
        })->name('home');
    });