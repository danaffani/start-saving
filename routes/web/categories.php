<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

Route::middleware(['auth'])->group(function () {
    Route::resource('categories', CategoryController::class);
});
