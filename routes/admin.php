<?php

use App\Http\Controllers\AdminDashboard\AdminDashboardController;
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin-authorize'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('index');
});
