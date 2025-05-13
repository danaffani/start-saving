<?php

use App\Http\Controllers\CategoryGroupController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::resource('category-groups', CategoryGroupController::class);
});
