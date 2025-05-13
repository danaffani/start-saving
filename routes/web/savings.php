<?php

use App\Http\Controllers\SavingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::resource('savings', SavingController::class);
});
