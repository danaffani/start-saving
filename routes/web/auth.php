<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialLoginController;

Auth::routes();

// Google OAuth Routes
Route::get('login/google', [SocialLoginController::class, 'redirectToGoogle'])->name('login.google');
Route::get('login/google/callback', [SocialLoginController::class, 'handleGoogleCallback']);
