<?php

use App\Http\Controllers\SavingController;
use App\Http\Controllers\SavingTargetController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::resource('saving-targets', SavingTargetController::class);

    // Nested resource for savings
    Route::resource('saving-targets.savings', SavingController::class);
});
