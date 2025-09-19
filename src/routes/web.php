<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/yard/config-expander/resources/css/protection.css', function () {
	return response()->file(__DIR__.'/../../resources/css/protection.css', [
		'Content-Type' => 'text/css',
	]);
});

Route::get('/yard/config-expander/resources/css/login-style.css', function () {
	return response()->file(__DIR__.'/../../resources/css/login-style.css', [
		'Content-Type' => 'text/css',
	]);
});

Route::get('/yard/config-expander/resources/images/logo-yard-black.svg', function () {
	return response()->file(__DIR__.'/../../resources/images/logo-yard-black.svg');
});
