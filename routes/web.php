<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'auth'], function () {

});

Route::group(['middleware' => 'guest', 'as' => 'auth.social'], function () {
    Route::get('/login/{provider}', [SocialAuthController::class, 'handleRedirect'])->name('redirect');
    Route::get('/login/{provider}/callback', [SocialAuthController::class, 'handleCallback'])->name('callback');
});

