<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/yard/config-expander/resources/styles/yard-y-icon', function () {
    return response()->file(__DIR__.'/../../resources/styles/yard-y-icon.css', [
        'Content-Type' => 'text/css',
    ]);
});

Route::get('/yard/config-expander/resources/fonts/yard-y-icon.woff', function () {
    return response()->file(__DIR__.'/../../resources/fonts/yard-y-icon.woff');
});

Route::get('/yard/config-expander/resources/fonts/yard-y-icon.ttf', function () {
    return response()->file(__DIR__.'/../../resources/fonts/yard-y-icon.ttf');
});
