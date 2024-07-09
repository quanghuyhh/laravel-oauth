<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\Users\HomeController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::post('/fetch-social-details', [SocialAuthController::class, 'refreshProfile'])->name('social.detail');
    Route::get('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/login/{provider}', [SocialAuthController::class, 'handleRedirect'])->name('social.redirect');
    Route::get('/login/{provider}/callback', [SocialAuthController::class, 'handleCallback'])->name('social.callback');
});

